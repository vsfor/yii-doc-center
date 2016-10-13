<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel jext\jrbac\models\JrbacMenuSearch */
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
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'pid',
                'filter' => \jext\jrbac\vendor\JMenu::getInstance()->getPidFilter(),
                'value' => function($model) {
                    $mDes = '';
                    if($model->pid) {
                        $p = \jext\jrbac\models\JrbacMenu::findOne($model->pid);
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
                'value' => function($model) {
                    $prefix = \jext\jrbac\vendor\JMenu::getInstance()->iconPrefix;
                    $t = '<i class="'.$prefix.$model->icon.'">&nbsp;</i>';
                    return $t . $model->label;
                }
            ],
            'url',
            'sortorder',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
