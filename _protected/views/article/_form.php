<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'summary')->textarea(['rows' => 2]) ?>

        <div class="from-group field-article-content">
            <label class="control-label" for="article-content"><?php echo Yii::t('app','Content');?></label>
            <?php
            echo Html::beginTag('div', ['id' => 'article-content-div']);
            echo Html::textarea('Article[content]',$model->content,[
                'style' => 'display:none;',
                'id' => 'article-content',
            ]);
            echo Html::endTag('div');
            ?>
        </div>
    <div class="row">
    <div class="col-lg-6">

        <?= $form->field($model, 'status')->dropDownList($model->statusList) ?>

        <?= $form->field($model, 'category')->dropDownList($model->categoryList) ?>

    </div>
    </div> 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') 
            : Yii::t('app', 'Update'), ['class' => $model->isNewRecord 
            ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('app', 'Cancel'), ['article/index'], ['class' => 'btn btn-default']) ?>
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
    var MDEditor = editormd("article-content-div", {
        width: "100%",
        height: 740,
        path : "'.\yii\helpers\Url::to('@web/static/editor.md/lib/').'",
        markdown : "",
        codeFold : true,
        searchReplace : true,
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
        imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
        imageUploadURL : "'.\yii\helpers\Url::to(['/user/upload-image']).'",
        onload : function() {
            $("#article-content-div .editormd-preview-close-btn").hide();
        }
    });
    MDEditor.show();
     
    
');
?>