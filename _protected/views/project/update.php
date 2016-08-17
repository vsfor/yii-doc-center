<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = Yii::t('app', 'Update Project') .': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Project').':'.$model->name, 'url' => ['view', 'project_id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Delete'),
    'url' => ['delete', 'project_id' => $model->id],
    'data-method' => 'post',
    'data-confirm' => '删除项目,将会删除所有关联目录及文档,请谨慎操作,确认删除?',
];

?>
<div class="project-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
