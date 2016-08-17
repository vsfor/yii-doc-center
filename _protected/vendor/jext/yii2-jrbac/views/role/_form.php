<div class="role-form">
    <?php $form = \yii\bootstrap\ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => 32]) ?>
    <?php echo $form->field($model, 'description')->textInput(['maxlength' => 32]) ?>

    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
</div>