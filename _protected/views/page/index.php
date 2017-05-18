<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文档搜索';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Project'),
    'url' => ['/project/view', 'project_id'=>$_GET['project_id']]
];
$this->params['breadcrumbs'][] = $this->title;

$this->params['left-menu'] = $leftMenu;

$dataProvider->setSort(false);
?>
<div class="page-index">
 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'header' => '位置',
                'value' => function($model) {
                    return \app\components\ProjectLib::getInstance()->getCatPath($model->project_id, $model->catalog_id);
                }
            ],
//            'title',
            [
                'header' => '描述',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->description,['view','project_id'=>$model->project_id,'page_id'=>$model->id]);
                }
            ],
            [
                'header' => '操作',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a('查看',['view','project_id'=>$model->project_id,'page_id'=>$model->id]);
                }
            ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
