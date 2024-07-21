<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use jext\jrbac\src\JMenu;

/* @var $this yii\web\View */
/* @var $model \jext\jrbac\src\JrbacMenu */
/* @var $form yii\widgets\ActiveForm */

if(is_null($model->status)) $model->status = 1;
if(is_null($model->sort_order)) $model->sort_order = 0;
$pMenuItems = JMenu::getInstance()->getOptionList(0,0,1);
$pMenuList = ['0'=>'顶级菜单'];
foreach($pMenuItems as $item) {
    if($item['id'] != $model->id) $pMenuList[$item['id']] = $item['label'];
}
$menuLib = JMenu::getInstance();
$iconArray = $menuLib->getMenuIconOptionItems();
if (!$model->icon) {
    $model->icon = $menuLib->defaultIcon;
}
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'pid')->dropDownList($pMenuList)->label('父级菜单'); ?>

    <?php echo $form->field($model, 'label')->textInput(['maxlength' => 32]); ?>

    <?php echo $form->field($model, 'icon')->radioList($iconArray,[
        'encode'=>false,
    ]); ?>

    <?php echo $form->field($model, 'url')->textInput(['maxlength' => 255]); ?>

    <?php echo $form->field($model, 'sort_order')->textInput(['type'=>'number']); ?>

    <?php echo $form->field($model, 'content')->textarea(['maxlength' => 255]); ?>

    <?php echo $form->field($model, 'status')->radioList([1=>'开启',2=>'关闭']); ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
