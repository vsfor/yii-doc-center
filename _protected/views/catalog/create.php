<?php

/* @var $this yii\web\View */
/* @var $model app\models\Catalog */

$this->title = Yii::t('app', 'Create Catalog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project'), 'url' => ['/project/view','project_id'=>$model->project_id]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['left-menu'] = $leftMenu;

$parents = \yii\helpers\ArrayHelper::map($parents,'id','label');
?>
<div class="catalog-create">

    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents,
    ]) ?>

</div>
