<div class="rule-form">
    <?php $form = \yii\bootstrap\ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => 64,'placeholder'=>'留空则使用规则类中定义的 $name 属性值']) ?>
    <?php echo $form->field($model, 'className')->textInput(['maxlength' => 255])->hint('必填项, eg: jext\jrbac\rules\AdminSelfRule ') ?>

    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
</div>