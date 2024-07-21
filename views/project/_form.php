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
        ->label('项目名称');
    ?>

    <?php echo $form->field($model, 'description')
        ->textInput(['maxlength' => true])
        ->label('项目描述');
    ?>

    <?php echo $form->field($model, 'open_type')
        ->dropDownList($model->openList)
        ->label('开放类型');
    ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
