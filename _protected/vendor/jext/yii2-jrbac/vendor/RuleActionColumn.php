<?php
namespace jext\jrbac\vendor;
use yii\helpers\Html;

class RuleActionColumn extends \yii\grid\ActionColumn
{
    public $template = '{view} {update} {delete} {permissionindex}';

    public function init()
    {
        parent::init();
        if (!isset($this->buttons['permissionindex'])) {
            $this->initPermissionIndexButton();
        }
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initPermissionIndexButton()
    {
        if (!isset($this->buttons['permissionindex'])) {
            $this->buttons['permissionindex'] = function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-wrench"></span>', $url, array_merge([
                    'title' => \Yii::t('yii', '规则权限关联管理'),
                    'data-pjax' => '0',
                ], $this->buttonOptions));
            };
        }
    }
}
