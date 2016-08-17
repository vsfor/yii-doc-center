<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Project List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i><?php echo Yii::t('app','Project List');?></h3>
        </div>
        <div class="box-body">
            <?php
            if (Yii::$app->getAuthManager()->allow('/project/create')) {
                $addProjectHtml = '
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h5>'.Yii::t('app', 'Project Name').'</h5>
                                <p>'.Yii::t('app', 'Project Description').'</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-book"></i>
                            </div>'.
                    Html::a(Yii::t('app', 'Create Project').' <i class="fa fa-arrow-circle-right"></i>', ['create'], ['class' => 'small-box-footer'])
                    .'</div>
                    </div>
                ';
                echo $addProjectHtml;
            }
            ?>

            <?php echo \yii\widgets\ListView::widget([
                'summary' => false,
                'dataProvider' => $dataProvider,
                'emptyText' => "",//$addProjectHtml,
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_index', ['model' => $model,'index'=>$index]);
                },
            ]); ?>

        </div>

    </div>

    <div class="callout callout-warning">
        <h4>提示!</h4>
        <p>项目添加暂处于测试阶段, 内测用户可创建3个文档项目. </p>
        <p>如有需要请联系我们, 并说明相关需求.</p>
    </div>

</div>
