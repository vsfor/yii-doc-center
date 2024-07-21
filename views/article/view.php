<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = $model->title;

?>
<div class="article-view">
    <div id="section0" class="section">
        <div class="content">
            <div class="box-body">
                <?php echo Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']); ?>

                <?php if (Yii::$app->user->can('updateArticle', ['model' => $model])): ?>
                    <?php echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
                <?php endif ?>

                <?php if (Yii::$app->user->can('deleteArticle', ['model' => $model])): ?>
                    <?php echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this article?'),
                            'method' => 'post',
                        ],
                    ]); ?>
                <?php endif ?>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-article"></i><?php echo $model->title;?></h3>
                </div>
                <div class="box-body">

                    <div class="callout callout-success">
                        <p><i class="fa fa-info-circle"></i> <?php echo $model->summary; ?></p>
                    </div>

                    <div class="callout">
                        <?php
                        echo Html::beginTag('div', ['id' => 'article-content-div']);
                        echo Html::textarea('article-content',$model->content,['style'=>'display:none;']);
                        echo Html::endTag('div');
                        $this->registerJs('
                        EditorMDView = editormd.markdownToHTML("article-content-div", {
                            htmlDecode      : "style,script,iframe",  // you can filter tags decode
                            emoji           : true,
                            taskList        : true,
                            tex             : false,  // 默认不解析
                            flowChart       : true,  // 默认不解析
                            sequenceDiagram : true,  // 默认不解析
                        }); ');
                        ?>
                    </div>

                </div>
                <div class="box-footer">
                    <?php echo $model->getCategoryName(); ?>
                    <span class="glyphicon glyphicon-time"></span>
                    <?php echo Yii::t('app','Published on') ?>
                    <?php echo date('Y-m-d H:i', $model->created_at) ?>
                </div>
            </div>

            <div style="width:100%;height:20px;display: block;clear: both;"></div>
        </div>
    </div>

</div>
<div style="width:100%;height:1px;display: block;clear: both;"></div>
<?php
$this->registerCss('
.section { text-align:left; padding-top:80px; }
.section .content { text-align: left; }
');

/*
$this->registerJs('
	$("#fullpage").fullpage({
            autoScrolling: false,
            animateAnchor:false, //need
            scrollOverflow: true,
            scrollingSpeed: 1000, 
            
            paddingTop: "50px", 
            paddingBottom: "0",
            
            verticalCentered: true,
            resize: false, 
            responsive: 900
        });
');
*/

?>
