<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = Yii::t('app','Project').':'.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['left-menu'] = $leftMenu;

$auth = Yii::$app->getAuthManager();

?>
<div class="project-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i><?php echo Yii::t('app','Project') . ": " . $model->name;?></h3>

            <?php if ($auth->allow('/project/getpdf', ['project_id'=>$model->id])) : ?>
            <div class="pull-right box-tools">
                <a target="_blank" href="<?php
                echo \yii\helpers\Url::to(['getpdf', 'project_id'=>$model->id]);
                ?>" class="btn btn-success btn-sm" title="输出为pdf"><i class="fa fa-print"></i></a>
            </div>
            <?php endif; ?>
        </div>
        <div class="box-body">

            <div class="callout callout-success">
                <p><i class="fa fa-info-circle"></i> <?php echo $model->description; ?></p>
            </div>

            <?php if ($auth->allow('/project/manage', ['project_id'=>$model->id])) : ?>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h5><?php echo Yii::t('app','Manage Project Document'); ?></h5>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-folder-open-o"></i>
                    </div>
                    <a href="<?php echo \yii\helpers\Url::to(['manage','project_id'=>$model->id])?>" class="small-box-footer">
                        <?php echo Yii::t('app','More info');?>
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($auth->allow('/project/member', ['project_id'=>$model->id])) :?>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h5><?php echo Yii::t('app','Manage Project Member'); ?></h5>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="<?php echo \yii\helpers\Url::to(['member','project_id'=>$model->id])?>" class="small-box-footer">
                        <?php echo Yii::t('app','More info');?>
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
        <div class="box-footer">

            <?php if ($auth->allow('/project/update', ['project_id' => $model->id])) : ?>
            <div class="col-md-2 pull-left">
                <?php
                echo Html::a(Yii::t('app','Update'), ['update','project_id'=>$model->id],[
                    'class'=>'btn btn-primary',
                ]);
                ?>
            </div>
            <?php endif; ?>

            <?php if ($auth->allow('/project/delete', ['project_id' => $model->id])) : ?>
            <div class="col-md-2 pull-right">
                <?php
                echo Html::a(Yii::t('app','Delete'), ['delete', 'project_id'=>$model->id],[
                    'class'=>'btn btn-danger',
                    'data' => [
                        'confirm' => '删除项目,将会删除所有关联目录及文档,请谨慎操作,确认删除?',
                        'method' => 'post',
                    ],
                ]);
                ?>
            </div>
            <?php endif; ?>

        </div>
    </div>


    <div class="callout callout-warning">
        <h4>提示!</h4>
        <p>管理操作需要对应的权限.</p>
    </div>

</div>
