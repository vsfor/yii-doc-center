<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Templates');
$this->params['breadcrumbs'][] = Yii::t('app', 'Templates');
?>
<div class="template-index">
        <div class="content">

    <h2><?= Html::encode($this->title) ?></h2>
    <p>每个用户至多创建5个模板</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'layout' => "{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'author_id',
            'title',
//            'content:ntext',
            'created_at:datetime',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>
