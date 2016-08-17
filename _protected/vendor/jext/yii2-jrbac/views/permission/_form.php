<div class="permission-form">
    <?php $form = \yii\bootstrap\ActiveForm::begin(); ?>

    <?php
        echo $form->field($model, 'name')
            ->textInput(['maxlength' => 64])
            ->label('资源唯一标识 (标准格式参考: /$moduleId/$controllerId/$actionId )')
            ->hint('注: 非标准格式的资源, 请注意设置其父级资源');
    ?>
    <?php
        echo $form->field($model, 'description')
            ->textInput(['maxlength' => 255])
            ->label('资源描述 (对应模块功能简要描述)');
    ?>
    <?php
        echo $form->field($model, 'ruleName')
            ->dropDownList($rules,['prompt'=>'无'])
            ->hint('注: 标准格式资源请谨慎设置');
    ?>
    <?php
        echo $form->field($model, 'parentPermission')
            ->textInput(['maxlength' => 64,'placeholder'=>'留空表示不设置'])
            ->label('父级资源标识 (标准格式参考: /$moduleId/$controllerId/$actionId ,多个使用 | 分隔)');
    ?>

    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
</div>