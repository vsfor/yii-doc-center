<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Catalog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'parent_id')
        ->dropDownList($parents,['prompt'=>'无','encode'=>false])
        ->label(Yii::t('app','Parent Catalog'));
    ?>

    <?php echo $form->field($model, 'name')
        ->textInput(['maxlength' => true])
        ->label(Yii::t('app','Catalog Name'));
    ?>

    <?php echo $form->field($model, 'sort_number')
        ->textInput()
        ->label(Yii::t('app', 'Sort Number'));
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
