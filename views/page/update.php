<?php


/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = Yii::t('app', 'Update Page') .':'. $model->title;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Project'),
    'url' => ['/project/view', 'project_id' => $model->project_id]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->title,
    'url' => ['view', 'page_id' => $model->id, 'project_id' => $model->project_id]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['left-menu'] = $leftMenu;

$catalogs = \yii\helpers\ArrayHelper::map($catalogs, 'id', 'label');
?>
<div class="page-update">
    
    <?php echo $this->render('_form', [
        'model' => $model,
        'catalogs' => $catalogs,
    ]); ?>

</div>
