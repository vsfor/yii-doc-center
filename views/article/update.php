<?php
/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = Yii::t('app', 'Update Article') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="article-update">
    <div class="col-lg-8 well bs-component">
        <?php echo $this->render('_form', ['model' => $model]) ?>
    </div>
</div>
