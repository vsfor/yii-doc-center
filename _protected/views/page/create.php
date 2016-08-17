<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = Yii::t('app', 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project'), 'url' => ['/project/view','project_id'=>$model->project_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Page List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['left-menu'] = $leftMenu;

$catalogs = \yii\helpers\ArrayHelper::map($catalogs, 'id', 'label');
?>
<div class="page-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'catalogs' => $catalogs,
    ]) ?>

</div>
