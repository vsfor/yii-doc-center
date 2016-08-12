<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'name')
        ->textInput(['maxlength' => true])
        ->label(Yii::t('app','Project Name'));
    ?>

    <?php echo $form->field($model, 'description')
        ->textInput(['maxlength' => true])
        ->label(Yii::t('app','Project Description'));
    ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
