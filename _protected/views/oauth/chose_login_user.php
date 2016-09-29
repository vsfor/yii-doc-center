<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $users app\models\UserIdentity[] */

$this->title = Yii::t('app', 'Project List');
?>
    <div class="chose-user-index">
        <div id="section0" class="section">
            <div class="content">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i><?php echo '绑定的用户列表';?></h3>
                    </div>
                    <div class="box-body">
                        <?php
                        $bg_colors = [
                            'bg-aqua','bg-green','bg-orange','bg-purple','bg-red'
                        ];
                        foreach ($users as $index=>$model):
                            /** @var \app\models\UserIdentity $model */
                            ?>
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box <?php echo $bg_colors[$index%5];?>">
                                    <div class="inner">
                                        <h5><?php echo $model->username; ?></h5>
                                        <p><?php echo $model->email; ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <?php if ($model->status == \app\models\User::STATUS_ACTIVE) : ?>
                                    <?php echo Html::a('登录<i class="fa fa-arrow-circle-right"></i>',['login','id'=>$model->id],[
                                            'class' => 'small-box-footer',
                                            'data-method' => 'post',
                                        ]);?>
                                    <?php else: ?>
                                    <a href="javascript:;" class="small-box-footer">
                                        <?php echo '状态异常';?>
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                    <?php endif;?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

            </div>
        </div>
        <div id="section1" class="section">
            <div class="content">
                <div class="callout callout-warning">
                    <h4>提示!</h4>
                    <p>无法登录的账号,请确认是否激活,或反馈给网站管理员. </p>
                    <p>同一账号可绑定多个微信号. </p>
                    <p>同一个微信号也可绑定多个账号.</p>
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