<?php

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
    <div id="" class="section">
        <div class="content">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            <div style="width:100%;height:20px;display: block;clear: both;"></div>
        </div>
    </div>


</div>

    <div style="width:100%;height:1px;display: block;clear: both;"></div>
<?php
$this->registerCss('
.section { text-align:left; }
.section .content { text-align: left; }
.section .content .box { background: none; }
.section .content .box .with-border { border-bottom: 1px solid #a90070; }
');
?>