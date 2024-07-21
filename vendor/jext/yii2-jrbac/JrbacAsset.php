<?php
namespace jext\jrbac;

use yii\web\AssetBundle;
use yii\web\View;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;

/**
 * Just for jquery.toastmessage plugin
 * Usages:
 * $().toastmessage('showNoticeToast', 'some message here');
 * $().toastmessage('showSuccessToast', "some message here");
 * $().toastmessage('showWarningToast', "some message here");
 * $().toastmessage('showErrorToast', "some message here");
 *
 * $().toastmessage('showToast', {
 *      text     : 'Hello World',
 *      sticky   : true,
 *      position : 'top-right',
 *      type     : 'success',
 *      close    : function () {console.log("toast is closed ...");}
 * });
 *
 * Class JrbacAsset
 * @package common\module\jrbac\asset
 */
class JrbacAsset extends AssetBundle
{
    public $sourcePath = '@vendor/jext/yii2-jrbac/asset';

    public $css = [
        'css/jquery.toastmessage.css',
    ];
    public $js = [
        'js/jquery.toastmessage.js',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

}