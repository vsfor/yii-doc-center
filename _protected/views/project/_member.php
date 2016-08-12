<?php
use yii\helpers\Url;
use app\models\ProjectMember;

/* @var $this yii\web\View */
/* @var $model */
/* @var $index int index */

$bg_colors = [
    ProjectMember::LEVEL_OWNER => 'bg-aqua',
    ProjectMember::LEVEL_ADMIN => 'bg-green',
    ProjectMember::LEVEL_EDITOR => 'bg-orange',
    ProjectMember::LEVEL_READER => 'bg-red',
];
$member_icon = [
    ProjectMember::LEVEL_OWNER => 'fa-user-secret',
    ProjectMember::LEVEL_ADMIN => 'fa-user-md',
    ProjectMember::LEVEL_EDITOR => 'fa-edit',
    ProjectMember::LEVEL_READER => 'fa-eye',
];
$roleSetHtml = \app\components\ProjectLib::getInstance()->getMemberLevelSetHtml($model);
?>
<div class="col-lg-3 col-xs-6 member-item">
    <!-- small box -->
    <div class="small-box <?php echo $bg_colors[$model['user_level']];?>">
        <div class="inner">
            <h5><?php echo $model['username']; ?></h5>
            <p><?php echo $model['email']; ?></p>
        </div>
        <div class="icon">
            <i class="fa <?php echo $member_icon[$model['user_level']];?>"></i>
        </div>
        <?php if ($roleSetHtml): ?>
        <div class="box-tools small-box-footer">
            <div class="btn-group">
                <a href="javascript:;" title="<?php echo Yii::t('app','Set Role');?>" class="act-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                    <?php echo $roleSetHtml; ?>
                    <li class="divider"></li>
                    <li><a href="javascript:;"><?php echo Yii::t('app','Close');?></a></li>
                </ul>
            </div>
            <?php echo \yii\bootstrap\Html::a('<i class="fa fa-user-times"></i>',[
                '/project/del-member',
                'id' => $model['project_id'],
                'user_id' => $model['user_id'],
            ],['class'=>'act-btn', 'data-method'=>'post']); ?>
            <a href="javascript:;" title="<?php echo Yii::t('app','Delete');?>" class="act-btn"></a>
        </div>
        <?php else: ?>
            <a class="small-box-footer" href="javascript:;"><i class="fa fa-user-secret"></i> <?php echo Yii::t('app','Permission Denied'); ?></a>
        <?php endif;?>
    </div>
</div>