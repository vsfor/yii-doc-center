<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $form yii\widgets\ActiveForm */
/* @var $catalogs */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row col-md-12">
        <div class="col-md-5">
            <?php echo $form->field($model, 'title')
                ->textInput(['maxlength' => true,'placeholder'=>'标题'])
                ->label(false);
            ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->field($model, 'catalog_id')
                ->dropDownList($catalogs,['prompt'=>'选择目录','encode'=>false])
                ->label(false);
            ?>
        </div>
        <div class="col-md-2">
            <?php echo $form->field($model, 'sort_number')
                ->textInput(['type'=>'number','placeholder'=>'排序号'])
                ->label(false); ?>
        </div>
        <div class="col-md-2">
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-bars"><?php echo Yii::t('app','Template Actions');?></i></button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="javascript:;"><?php echo Yii::t('app', 'Insert Template');?></a></li>
                    <li><a class="template-add" data-id="api" href="javascript:;"><i class="fa fa-paste">&nbsp;<?php echo Yii::t('app', 'Api Document'); ?></i></a></li>
                    <li><a class="template-add" data-id="table" href="javascript:;"><i class="fa fa-paste">&nbsp;<?php echo Yii::t('app', 'DB Table Info');?></i></a></li>
                    <?php
                    $tempLib = new \app\components\TemplateLib();
                    $templates = $tempLib->getListByUserId(\Yii::$app->getUser()->getId());
                    /** @var \app\models\Template $template */
                    $tempHtml = '';
                    foreach ($templates as $template) {
                        $tempHtml .= '<li>';
                        $tempHtml .= '<a class="template-add" data-id="'.$template->id.'" href="javascript:;">';
                        $tempHtml .= '<i class="fa fa-paste">&nbsp;'.$template->title.'</i>';
                        $tempHtml .= '</a>';
                        $tempHtml .= '</li>';
                    }
                    echo $tempHtml;
                    ?>
                    <li class="divider"></li>
                    <li>
                        <a class="save-to-template" href="javascript:;">
                            <i class="fa fa-save"></i>&nbsp;
                            <?php echo Yii::t('app', 'Save to Template'); ?>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li><a target="_blank" href="<?= \yii\helpers\Url::to(['template/index'])?>">
                            <?php echo Yii::t('app', 'Manage my Templates'); ?>
                        </a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <?php
        echo Html::beginTag('div', ['id' => 'page-content-div']);
        echo Html::textarea('Page[content]',$model->content,[
            'style' => 'display:none;',
            'id' => 'page-content',
        ]);
        echo Html::endTag('div');
        ?>
    </div>
    <div class="col-md-12 hide">
        <?php echo $form->field($model, 'description')
            ->textInput(['maxlength' => true])
            ->hint('注: 标题,内容及描述均不可为空,(描述可用于文档版本更新说明)');
        ?>
    </div>

    <div class="form-group col-md-12">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div id="save-template-modal" class="modal modal-success">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal-close-btn">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?php echo Yii::t('app','Save to Template');?></h4>
            </div>
            <div class="modal-body">
                <p><input
                        type="text"
                        style=" border: 1px solid #eee;
                                color:#000000;
                                font-size: 18px;
                                padding: 6px 20px;"
                        id="templateName"
                        placeholder="<?php echo Yii::t('app','Input Template Name...');?>"
                    /></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left modal-close-btn">Cancel</button>
                <button type="button" class="btn btn-outline" id="saveTemplateBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJsFile('@web/static/editor.md/editormd.js',[
    'depends' => [
        'yii\web\YiiAsset',
    ],
]);
$this->registerJs('
    var MDEditor = editormd("page-content-div", {
        width: "100%",
        height: 630,
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
        imageUpload : true,
        imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp", "ico"],
        imageUploadURL : "'.\yii\helpers\Url::to(['/user/upload-image']).'",
        onload : function() {
            $("#page-content-div .editormd-preview-close-btn").hide();
        }
    });
    MDEditor.show();
    
    $(".template-add").click(function(){
        var template_id = $(this).attr("data-id");
        $.ajax({
            url: "'.\yii\helpers\Url::to(['/template/get-content']).'",
            type: "GET",
            data: {id:template_id},
            success: function(tmpl) {
                MDEditor.insertValue(tmpl);
            }
        });
    });
    
    $(".save-to-template").click(function(){
        $("#save-template-modal").show();
    });
    
    $(".modal-close-btn").click(function(){
        $(".modal").hide();
    });
    
    $("#saveTemplateBtn").click(function(){
        var tempTitle = $("#templateName").val();
        var tempContent = $("#page-content").val();
        $.ajax({
            url: "'.\yii\helpers\Url::to(['/template/add-page']).'",
            type: "POST",
            data: {title: tempTitle,content: tempContent},
            success: function(res) {
                alert(res);
            }
        });
        $(".modal").hide();
    });
    
');
?>