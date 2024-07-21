<?php //useful

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $historyList app\models\PageHistory[] */
/* @var $leftMenu [] */

$this->title = Yii::t('app','Page').':'.$model->title;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Project'),
    'url' => ['/project/view', 'project_id'=>$model->project_id]
];

$this->params['breadcrumbs'][] = $this->title;

$auth = Yii::$app->getAuthManager();
if ($auth->allow('/page/update', ['project_id'=>$model->project_id])) {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'Update'),
        'url' => ['update', 'page_id' => $model->id, 'project_id' => $model->project_id],
    ];
}
if ($auth->allow('/page/delete', ['project_id'=>$model->project_id])) {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'Delete'),
        'url' => ['delete', 'page_id' => $model->id, 'project_id' => $model->project_id],
        'data-method' => 'post',
        'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
    ];
}

$this->params['left-menu'] = $leftMenu;
?>
<div class="page-view">
    <div class="box box-solid bg-green-gradient" id="page-title-block">
        <div class="box-header ui-sortable-handle" style="cursor: move;">
            <i class="fa fa-file-pdf-o"></i>
            <h3 class="box-title"><?php echo $model->title; ?></h3>
            <!-- tools box -->
            <div class="pull-right box-tools">
                <?php if(!\Yii::$app->getUser()->getIsGuest()): ?>
                <a target="_blank" href="<?php
                echo \yii\helpers\Url::to(['getpdf','page_id'=>$model->id, 'project_id'=>$model->project_id]);
                ?>" class="btn btn-success btn-sm" title="输出为pdf"><i class="fa fa-print"></i></a>
                <?php endif; ?>
                <!-- button with a dropdown -->
                <?php if($historyList): ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bars"></i></button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li class="box-header"><i class="fa fa-code-fork">&nbsp;</i><?php echo Yii::t('app', 'Click To View History'); ?></li>
                        <li>
                            <ul style="max-height:120px;list-style: none;padding:0;overflow-y: scroll;overflow-x: hidden;">
                                <?php foreach ($historyList as $history) : ?>
                                    <li style="padding:3px 5px; border-top:1px solid #eee;">
                                        <a href="javascript:;" class="view-history" style="display: block;" data-id="<?php echo $history['id']; ?>">
                                            <i class="fa fa-clock-o text-sm">&nbsp;<?php echo date("Y-m-d H:i:s",$history['updated_at']);?></i>
                                            <br/>
                                            <?php echo $history['description']; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="box-footer"><a href="javascript:;" class="view-current"><i class="fa fa-refresh">&nbsp;</i><?php echo Yii::t('app', 'Back To View Current'); ?></a></li>
                    </ul>
                </div>
                <?php endif; ?>
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
            </div>
            <!-- /. tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="col-md-12"><?php echo $model->description; ?></div>
        </div>
        <div class="box-footer text-center">
            <?php if ($preNext['pre']) :
                echo Html::a('<i class="fa fa-chevron-left"></i> '.$preNext['pre']['title'],[
                    '/page/view',
                    'page_id'=>$preNext['pre']['id'],
                    'project_id'=>$preNext['pre']['project_id']
                ],['class'=>'text-default']);
            else:
                ?>
                <a href="javascript:;"><i class="fa fa-chevron-left"></i> 无</a>
            <?php endif; ?>
            <i class="fa fa-pause" style="color:#ddd;"></i>
            <?php if ($preNext['next']) :
                echo Html::a($preNext['next']['title'].' <i class="fa fa-chevron-right"></i>',[
                    '/page/view',
                    'page_id' => $preNext['next']['id'],
                    'project_id' => $preNext['next']['project_id']
                ]);
            else: ?>
                <a href="javascript:;">无 <i class="fa fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>
    </div>


    <div id="page-history-div" style="display: none;"></div>
    <?php
    echo Html::beginTag('div', ['id' => 'page-content-div', 'class'=>'box box-solid']);
    echo Html::textarea('page-content',$model->content,['style'=>'display:none;']);
    echo Html::endTag('div');
    $this->registerJs('
        EditorMDView = editormd.markdownToHTML("page-content-div", {
            htmlDecode      : "style,script,iframe",  // you can filter tags decode
            emoji           : true,
            taskList        : true,
            tex             : false,  // 默认不解析
            flowChart       : true,  // 默认不解析
            sequenceDiagram : true,  // 默认不解析
        }); 
    ');
    ?>

    <div class="box text-center">
        <div class="box-body">
            <?php if ($preNext['pre']) :
                echo Html::a('<i class="fa fa-chevron-left"></i> '.$preNext['pre']['title'],[
                    '/page/view',
                    'page_id'=>$preNext['pre']['id'],
                    'project_id'=>$preNext['pre']['project_id']
                ]);
            else:
            ?>
            <a href="javascript:;"><i class="fa fa-chevron-left"></i> 无</a>
            <?php endif; ?>
            <i class="fa fa-pause" style="color:#ddd;"></i>
            <?php if ($preNext['next']) :
                echo Html::a($preNext['next']['title'].' <i class="fa fa-chevron-right"></i>',[
                    '/page/view',
                    'page_id' => $preNext['next']['id'],
                    'project_id' => $preNext['next']['project_id']
                ]);
                else: ?>
            <a href="javascript:;">无 <i class="fa fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php
$this->registerJs('

        $(".view-history").click(function(){ 
            var history_id = $(this).attr("data-id");
            $.ajax({
                url:"'.\yii\helpers\Url::to(['/page/gethistory']).'",
                method:"GET",
                data:{id:history_id},
                dataType:"json",
                success:function(history){ 
                    console.log(history);
                    $("#page-history-div").html(\'\
                        <div class="callout callout-info">\
                            <h4>\'+ history.title +\'<span class="fa fa-clock-o text-sm pull-right"> \'+ history.time +\'</span></h4>\
                            <p><i class="fa fa-code-fork"></i> \'+ history.description +\'</p>\
                        </div>\
                    \');
                    EditorMDViewHistory = editormd.markdownToHTML("page-history-div", {
                        markdown        : history.content,
                        //htmlDecode      : true,       // 开启 HTML 标签解析，为了安全性，默认不开启
                        htmlDecode      : "style,script,iframe",  // you can filter tags decode
                        //toc             : false,
                        tocm            : true,    // Using [TOCM]
                        //tocContainer    : "#custom-toc-container", // 自定义 ToC 容器层
                        //gfm             : false,
                        //tocDropdown     : true,
                        // markdownSourceCode : true, // 是否保留 Markdown 源码，即是否删除保存源码的 Textarea 标签
                        emoji           : true,
                        taskList        : true,
                        tex             : false,  // 默认不解析
                        flowChart       : true,  // 默认不解析
                        sequenceDiagram : true,  // 默认不解析
                    });

                    $("#page-history-div").show();
                    EditorMDView.hide();
                }
            }); 
        });
        
        $(".view-current").click(function(){
            $("#page-history-div").hide();
            EditorMDView.show();
        });
        
        $(".nav .active a").click(function(){
            $("#page-title-block").show();
        });
        
');
?>
