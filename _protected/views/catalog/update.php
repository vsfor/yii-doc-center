<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Catalog */

$this->title = Yii::t('app', 'Update Catalog') .':'. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project'), 'url' => ['/project/view','project_id'=>$model->project_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Catalog List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'catalog_id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['left-menu'] = $leftMenu;

$parents = \yii\helpers\ArrayHelper::map($parents,'id','label');
unset($parents[$model->id]);
?>
<div class="catalog-update">

    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents,
    ]) ?>

</div>
