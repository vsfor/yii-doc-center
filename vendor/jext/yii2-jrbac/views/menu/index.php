<?php

use yii\helpers\Html;
use yii\grid\GridView;
use jext\jrbac\src\JMenu;
use jext\jrbac\src\JrbacMenu;

/* @var $this yii\web\View */
/* @var $searchModel \jext\jrbac\src\JrbacMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '菜单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h3><?php echo Html::encode($this->title) ?></h3>

    <p>
        <?php echo Html::a('添加菜单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['class' => 'hidden-xs',],
                'headerOptions' => ['class' => 'hidden-xs',],
                'filterOptions' => ['class' => 'hidden-xs',],
                'contentOptions' => ['class' => 'hidden-xs',],
            ],
            [
                'attribute' => 'pid',
                'filter' => JMenu::getInstance()->getPidFilter(),
                'value' => function ($model) {
                    if ($model->pid) {
                        $p = JrbacMenu::findOne($model->pid);
                    } else {
                        $p = false;
                    }
                    $mDes = $p ? $p->label . "({$model->pid})" : '顶级菜单';
                    return $mDes;
                }
            ],
            [
                'attribute' => 'label',
                'format' => 'raw',
                'value' => function ($model) {
                    $prefix = JMenu::getInstance()->iconPrefix;
                    $t = '<i class="' . $prefix . $model->icon . '">&nbsp;</i>';
                    return $t . $model->label;
                }
            ],
            [
                'attribute' => 'url',
                'options' => ['class' => 'hidden-xs',],
                'headerOptions' => ['class' => 'hidden-xs',],
                'filterOptions' => ['class' => 'hidden-xs',],
                'contentOptions' => ['class' => 'hidden-xs',],
            ],
            [
                'attribute' => 'sort_order',
                'options' => ['class' => 'hidden-xs',],
                'headerOptions' => ['class' => 'hidden-xs',],
                'filterOptions' => ['class' => 'hidden-xs',],
                'contentOptions' => ['class' => 'hidden-xs',],
            ],
            [
                'header' => '操作',
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>

</div>