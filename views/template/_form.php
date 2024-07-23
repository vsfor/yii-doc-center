<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="col-md-12">
        <?php
        echo Html::beginTag('div', ['id' => 'template-content-div']);
        echo Html::textarea('Template[content]',$model->content,[
            'style' => 'display:none;',
            'id' => 'template-content',
        ]);
        echo Html::endTag('div');
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJsFile('@web/static/editor.md/editormd.js',[
    'depends' => [
        'yii\web\YiiAsset',
    ],
]);


$this->registerJs('
    var MDEditor = editormd("template-content-div", {
        width: "100%",
        height: 530,
        path : "'.\yii\helpers\Url::to('@web/static/editor.md/lib/').'",
//        theme : "dark",
//        previewTheme : "dark",
//        editorTheme : "pastel-on-dark",
        markdown : "",
        codeFold : true,
        //syncScrolling : false,
//        saveHTMLToTextarea : true,    // 保存 HTML 到 Textarea
        searchReplace : true,
        //watch : false,                // 关闭实时预览
        htmlDecode : "style,script,iframe|on*",            // 开启 HTML 标签解析，为了安全性，默认不开启    
        //toolbar  : false,             //关闭工具栏
        toolbarAutoFixed : false,
        //previewCodeHighlight : false, // 关闭预览 HTML 的代码块高亮，默认开启
        emoji : true,
//        taskList : true,
//        tocm            : true,         // Using [TOCM]
//        tex : true,                   // 开启科学公式TeX语言支持，默认关闭
//        flowChart : true,             // 开启流程图支持，默认关闭
        sequenceDiagram : true,       // 开启时序/序列图支持，默认关闭,
        //dialogLockScreen : false,   // 设置弹出层对话框不锁屏，全局通用，默认为true
        //dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为true
        //dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为true
        //dialogMaskOpacity : 0.4,    // 设置透明遮罩层的透明度，全局通用，默认值为0.1
        //dialogMaskBgColor : "#000", // 设置透明遮罩层的背景颜色，全局通用，默认为#fff
        imageUpload : false, //模板禁用图片
        onload : function() {
            $("#template-content-div .editormd-preview-close-btn").hide();
        }
    });
    MDEditor.show();

');