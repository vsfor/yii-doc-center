<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = Yii::t('app', 'Project Member List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Project').':'.$model->name, 'url' => ['view','project_id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['left-menu'] = $leftMenu;
$addMemberAllow = Yii::$app->getAuthManager()->allow('/project/add-member', ['project_id'=>$model->id]);
?>
<div class="project-member">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list"></i><?php echo Yii::t('app','Project Member List');?></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="<?php echo Yii::t('app','Collapse')?>"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <?php
            $addMemberHtml = $addMemberAllow ? '
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h5>'.Yii::t('app', 'Member UserName').'</h5>
                            <p>'.Yii::t('app', 'Member Email').'</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>'.
                    Html::a(Yii::t('app', 'Add Member').' <i class="fa fa-arrow-circle-right"></i>', 'javascript:;', ['class' => 'small-box-footer add-member'])
                    .'</div>
                </div>
            ' : '';
            echo $addMemberHtml;
            ?>
            <?php echo \yii\widgets\ListView::widget([
                'summary' => false,
                'dataProvider' => $dataProvider,
                'emptyText' => "",//$addMemberHtml,
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_member', ['model' => $model,'index'=>$index]);
                },
            ]) ?>
        </div>
    </div>

<?php if ($addMemberAllow) : ?>
    <div id="add-member-modal" class="modal modal-success">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close modal-close-btn">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?php echo Yii::t('app','Add Project Member');?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input
                                class="form-control"
                                type="text"
                                style=" border: 1px solid #eee;
                                    font-size: 18px;
                                    padding: 6px 20px;"
                                id="memberUserName"
                                placeholder="<?php echo Yii::t('app','Input the UserName...');?>"
                            />
                        </div>
                        <div class="col-md-4">
                            <?php
                            $levelOptionList = [];
                            $pm = \app\components\ProjectLib::getInstance()->getMemberLevel($model->id, Yii::$app->getUser()->getId());
                            foreach (\app\models\ProjectMember::$levelList as $k=>$level) {
                                if ($k > $pm['level']) {
                                    continue;
                                }
                                $levelOptionList[$k] = $level;
                            }
                            echo Html::dropDownList('project-member-level',null, $levelOptionList,[
                                'id' => 'memberLevel',
                                'class' => 'form-control',
                            ]); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left modal-close-btn"><?php echo Yii::t('app','Cancel');?></button>
                    <button type="button" class="btn btn-outline" id="addMemberBtn"><?php echo Yii::t('app','Add');?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <div class="callout callout-warning">
        <h4>提示!</h4>

        <p>管理项目成员需要对应的权限.</p>
    </div>
</div>
<?php
$_css = <<<CSSSTR
.member-item .act-btn { 
    padding: 1px 10px; 
    margin:0 5px; border:0; 
    background: none;
    color: #fff; 
}
.member-item .btn-xs:hover {
    color: #eee; 
}
CSSSTR;
$this->registerCss($_css);

if ($addMemberAllow) {
    $this->registerJs('
        $(".add-member").click(function(){
            $("#add-member-modal").show();
        });
        
        $(".modal-close-btn").click(function(){
            $(".modal").hide();
        });
        
        $("#addMemberBtn").click(function(){
            var projectId = "'.$model['id'].'";
            var memberUserName = $("#memberUserName").val();
            var memberLevel = $("#memberLevel").val();
            $.ajax({
                url: "'.\yii\helpers\Url::to(['/project/add-member']).'",
                type: "POST",
                data: {project_id: projectId, username: memberUserName, level: memberLevel},
                success: function(res) {
                    console.log(res);
                }
            });
            $(".modal").hide();
        });
    ');
}
