<?php
namespace jext\jrbac\vendor;
use yii\helpers\Html;

class RoleActionColumn extends \yii\grid\ActionColumn
{
    public $template = '{view} {update} {delete} {userindex} {permissionindex} {subindex}';

    public function init()
    {
        parent::init();
        if (!isset($this->buttons['userindex'])) {
            $this->initUserIndexButton();
        }
        if (!isset($this->buttons['permissionindex'])) {
            $this->initPermissionIndexButton();
        }
        if (!isset($this->buttons['subindex'])) {
            $this->initSubIndexButton();
        }
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initUserIndexButton()
    {
        if (!isset($this->buttons['userindex'])) {
            $this->buttons['userindex'] = function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, array_merge([
                    'title' => \Yii::t('yii', '角色用户管理'),
                    'data-pjax' => '0',
                ], $this->buttonOptions));
            };
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
                    'title' => \Yii::t('yii', '角色权限管理'),
                    'data-pjax' => '0',
                ], $this->buttonOptions));
            };
        }
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initSubIndexButton()
    {
        if (!isset($this->buttons['subindex'])) {
            $this->buttons['subindex'] = function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-filter"></span>', $url, array_merge([
                    'title' => \Yii::t('yii', '子角色管理'),
                    'data-pjax' => '0',
                ], $this->buttonOptions));
            };
        }
    }
}
