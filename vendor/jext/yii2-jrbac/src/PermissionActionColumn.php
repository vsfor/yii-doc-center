<?php
namespace jext\jrbac\src;

use yii\grid\ActionColumn;
use yii\helpers\Html;

class PermissionActionColumn extends ActionColumn
{
    public $template = '{view} {update} {delete} {subindex}';

    public function init()
    {
        parent::init();
        if (!isset($this->buttons['subindex'])) {
            $this->initSubIndexButton();
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
                    'title' => \Yii::t('yii', '子权限管理'),
                    'data-pjax' => '0',
                ], $this->buttonOptions));
            };
        }
    }
}
