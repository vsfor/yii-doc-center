<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Project List');
?>
<div class="project-index">
    <div id="section0" class="section">
        <div class="content">

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

        </div>
    </div>
    <div id="section1" class="section">
        <div class="content">
            <div class="callout callout-warning">
                <h4>提示!</h4>
                <p>项目添加暂处于测试阶段, 内测用户可创建3个文档项目. </p>
                <p>如有需要请联系我们, 并说明相关需求.</p>
            </div>
        </div>
    </div>

</div>

    <div style="width:100%;height:1px;display: block;clear: both;"></div>

<?php
$this->registerCss('
.section { text-align:left; }
.section .content { text-align: left; }
.section .content .box { background: none; }
.section .content .box .with-border { border-bottom: 1px solid #a90070; }
');
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
?>