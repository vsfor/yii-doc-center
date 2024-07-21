<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2022
 * @version   3.0.5
 */

namespace kartik\base;

use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

/**
 * WidgetTrait manages all methods used by Krajee widgets and input widgets.
 *
 * @method View getView()
 * @property string|false $baseSourcePath Get parsed base source path based on [[sourcePath]] setting. If [[sourcePath]]
 * is not set, it will return the current working directory of this widget class.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
trait WidgetTrait
{
    /**
     * @var string the module identifier if this widget is part of a module.
     *
     * If not set, the module identifier will be auto derived based on the \yii\base\Module::getInstance method. This can be useful, if you are setting
     * multiple module identifiers for the same module in your Yii configuration file. To specify children or grand
     * children modules you can specify the module identifiers relative to the parent module (e.g. `admin/content`).
     */
    public $moduleId;

    /**
     * @var string directory path to the original widget source. If not set, will default to the working directory for
     * the current widget's class. Setting this property can be useful in specific cases, like when you are extending
     * the Krajee widget with your own custom namespaced class. In that case, set this property to the original Krajee
     * Widget Base path. Yii path alias parsing is supported (using `@` symbols). For example:
     *
     * ```php
     * // your custom extended widget
     * namespace myapp\widgets;
     * class MyDateRangePicker extends kartik\daterange\DateRangePicker {
     *     // directly set the property to the original Krajee base widget directory
     *     // you can use Yii path aliases
     *     public $sourcePath = '@vendor/kartik-v/yii2-date-range/src';
     * }
     *
     * // Alternatively you can also override this property while rendering the widget
     * // view.php: where widget is rendered
     * use myapp\widgets\MyDateRangePicker;
     *
     * echo MyDateRangePicker::widget([
     *     'name' => 'custom',
     *     'sourcePath' => '@vendor/kartik-v/yii2-date-range/src'
     * ]);
     * ```
     */
    public $sourcePath;

    /**
     * @var boolean prevent duplication of pjax containers when browser back & forward buttons are pressed.
     *
     * - If this property is not set, it will be defaulted from Yii::$app->params['pjaxDuplicationFix'].
     * - If `Yii::$app->params['pjaxDuplicationFix']` is not set, then this property will default to `true`.
     */
    public $pjaxDuplicationFix;

    /**
     * @var string the plugin name
     */
    public $pluginName = '';

    /**
     * @var string the javascript that will be used to destroy the jQuery plugin
     */
    public $pluginDestroyJs;

    /**
     * @var array widget JQuery events.
     *
     * You must define events in `event-name => event-function` format. For example:
     *
     * ~~~
     * pluginEvents = [
     *     'change' => 'function() { log("change"); }',
     *     'open' => 'function() { log("open"); }',
     * ];
     * ~~~
     */
    public $pluginEvents = [];

    /**
     * @var array widget plugin options.
     */
    public $pluginOptions = [];

    /**
     * @var array default plugin options for the widget
     */
    public $defaultPluginOptions = [];

    /**
     * @var array default HTML attributes or other settings for widgets.
     */
    public $defaultOptions = [];

    /**
     * @var string the identifier for the PJAX widget container if the widget is to be rendered inside a PJAX container.
     *
     * This will ensure the any jQuery plugin using the widget is initialized correctly after a PJAX request is completed.
     * If this is not set, no re-initialization will be done for pjax.
     */
    public $pjaxContainerId;

    /**
     * @var integer the position where the client JS hash variables for the input widget will be loaded.
     *
     * Defaults to `View::POS_HEAD`. This can be set to `View::POS_READY` for specific scenarios like when
     * rendering the widget via `renderAjax`.
     */
    public $hashVarLoadPosition = View::POS_HEAD;

    /**
     * @var string the generated hashed variable name that will store the JSON encoded pluginOptions in
     * [[View::POS_HEAD]].
     */
    protected $_hashVar;

    /**
     * @var string the JSON encoded plugin options.
     */
    protected $_encOptions = '';

    /**
     * @var string the HTML5 data variable name that will be used to store the Json encoded pluginOptions within the
     * element on which the jQuery plugin will be initialized.
     */
    protected $_dataVar;

    /**
     * Sets a HTML5 data variable.
     *
     * @param string $name the plugin name
     */
    protected function setDataVar($name)
    {
        $this->_dataVar = "data-krajee-{$name}";
    }

    /**
     * Merge default options
     */
    protected function mergeDefaultOptions()
    {
        $this->pluginOptions = ArrayHelper::merge($this->defaultPluginOptions, $this->pluginOptions);
        $this->options = ArrayHelper::merge($this->defaultOptions, $this->options);
    }

    /**
     * Generates the `pluginDestroyJs` script if it is not set.
     */
    protected function initDestroyJs()
    {
        if (isset($this->pluginDestroyJs)) {
            return;
        }
        if (empty($this->pluginName)) {
            $this->pluginDestroyJs = '';
            return;
        }
        $id = "jQuery('#" . $this->options['id'] . "')";
        $plugin = $this->pluginName;
        $this->pluginDestroyJs = "if ({$id}.data('{$this->pluginName}')) { {$id}.{$plugin}('destroy'); }";
    }

    /**
     * Adds an asset to the view.
     *
     * @param View $view the View object
     * @param string $file the asset file name
     * @param string $type the asset file type (css or js)
     * @param string $class the class name of the AssetBundle
     */
    protected function addAsset($view, $file, $type, $class)
    {
        if ($type == 'css' || $type == 'js') {
            $asset = $view->getAssetManager();
            $bundle = $asset->bundles[$class];
            if ($type == 'css') {
                $bundle->css[] = $file;
            } else {
                $bundle->js[] = $file;
            }
            $asset->bundles[$class] = $bundle;
            $view->setAssetManager($asset);
        }
    }

    /**
     * Generates a hashed variable to store the pluginOptions.
     *
     * The following special data attributes will also be setup for the input widget, that can be accessed through javascript :
     *
     * - `data-krajee-{name}` will store the hashed variable storing the plugin options. The `{name}` token will be
     *   replaced with the plugin name (e.g. `select2`, `typeahead` etc.).
     *
     * @see https://github.com/kartik-v/yii2-krajee-base/issues/6
     *
     * @param string $name the name of the plugin
     */
    protected function hashPluginOptions($name)
    {
        $this->_encOptions = empty($this->pluginOptions) ? '' : Json::htmlEncode($this->pluginOptions);
        $this->_hashVar = $name . '_' . hash('crc32', $this->_encOptions);
        $this->options['data-krajee-' . $name] = $this->_hashVar;
    }

    /**
     * Registers plugin options by storing within a uniquely generated javascript variable.
     *
     * @param  string  $name  the plugin name
     * @throws Exception
     */
    protected function registerPluginOptions($name)
    {
        $this->hashPluginOptions($name);
        $encOptions = empty($this->_encOptions) ? '{}' : $this->_encOptions;
        $this->registerWidgetJs("window.{$this->_hashVar} = {$encOptions};\n", $this->hashVarLoadPosition);
    }

    /**
     * Returns the plugin registration script.
     *
     * @param  string  $name  the name of the plugin
     * @param  string  $element  the plugin target element
     * @param  string  $callback  the javascript callback function to be called after plugin loads
     * @param  string  $callbackCon  the javascript callback function to be passed to the plugin constructor
     *
     * @return string the generated plugin script
     * @throws Exception
     */
    protected function getPluginScript($name, $element = null, $callback = null, $callbackCon = null)
    {
        $id = $element ?: "jQuery('#{$this->options['id']}')";
        $script = '';
        /** @noinspection PhpStrictComparisonWithOperandsOfDifferentTypesInspection */
        if ($this->pluginOptions !== false) {
            $this->registerPluginOptions($name);
            $script = "{$id}.{$name}({$this->_hashVar})";
            if ($callbackCon != null) {
                $script = "{$id}.{$name}({$this->_hashVar}, {$callbackCon})";
            }
            if ($callback != null) {
                $script = "jQuery.when({$script}).done({$callback})";
            }
            $script .= ";\n";
        }
        $script = $this->pluginDestroyJs . "\n" . $script;
        if (!empty($this->pluginEvents)) {
            foreach ($this->pluginEvents as $event => $handler) {
                $function = $handler instanceof JsExpression ? $handler : new JsExpression($handler);
                $script .= "{$id}.on('{$event}', {$function});\n";
            }
        }
        return $script;
    }

    /**
     * Registers a specific plugin and the related events
     *
     * @param  string  $name  the name of the plugin
     * @param  string  $element  the plugin target element
     * @param  string  $callback  the javascript callback function to be called after plugin loads
     * @param  string  $callbackCon  the javascript callback function to be passed to the plugin constructor
     * @throws Exception
     */
    protected function registerPlugin($name, $element = null, $callback = null, $callbackCon = null)
    {
        $script = $this->getPluginScript($name, $element, $callback, $callbackCon);
        $this->registerWidgetJs($script);
    }

    /**
     * Fix for weird PJAX container duplication behavior on pressing browser back and forward buttons.
     * @param  View  $view
     * @throws Exception
     */
    protected function fixPjaxDuplication($view)
    {
        if (!isset($this->pjaxDuplicationFix)) {
            $this->pjaxDuplicationFix = ArrayHelper::getValue(Yii::$app->params, 'pjaxDuplicationFix', true);
        }
        if ($this->pjaxDuplicationFix === true) {
            $view->registerJs('jQuery&&jQuery.pjax&&(jQuery.pjax.defaults.maxCacheLength=0);');
        }
    }

    /**
     * Registers a JS code block for the widget.
     *
     * @param  string  $js  the JS code block to be registered
     * @param  integer  $pos  the position at which the JS script tag should be inserted in a page. The possible values
     * are:
     * - [[View::POS_HEAD]]: in the head section
     * - [[View::POS_BEGIN]]: at the beginning of the body section
     * - [[View::POS_END]]: at the end of the body section
     * - [[View::POS_LOAD]]: enclosed within jQuery(window).load(). Note that by using this position, the method will
     *   automatically register the jQuery js file.
     * - [[View::POS_READY]]: enclosed within jQuery(document).ready(). This is the default value. Note that by using
     *   this position, the method will automatically register the jQuery js file.
     * @param  string  $key  the key that identifies the JS code block. If null, it will use `$js` as the key. If two JS
     * code blocks are registered with the same key, the latter will overwrite the former.
     * @throws Exception
     */
    public function registerWidgetJs($js, $pos = View::POS_READY, $key = null)
    {
        $view = $this->getView();
        WidgetAsset::register($view);
        $this->fixPjaxDuplication($view);
        if (empty($js)) {
            return;
        }
        $view->registerJs($js, $pos, $key);
        if (!empty($this->pjaxContainerId) && ($pos === View::POS_LOAD || $pos === View::POS_READY)) {
            $pjax = 'jQuery("#' . $this->pjaxContainerId . '")';
            $evComplete = 'pjax:complete.' . hash('crc32', $js);
            $script = "setTimeout(function(){ {$js} }, 100);";
            $view->registerJs("{$pjax}.off('{$evComplete}').on('{$evComplete}',function(){ {$script} });");
        }
    }

    /**
     * Get parsed base source path based on [[sourcePath]] setting. If [[sourcePath]] is not set, it will return the
     * current working directory of this widget class.
     *
     * @return string|false
     */
    public function getBaseSourcePath()
    {
        return isset($this->sourcePath) ? Yii::getAlias($this->sourcePath) : Config::getCurrentDir($this);
    }
}
