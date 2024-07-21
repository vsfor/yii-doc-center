<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'About');

?>
<div class="site-about">
    <div class="section" id="section0">
        <div class="content" style="margin-top:30px;">
            <h3><?php echo Html::encode($this->title) ?></h3>

            <p>基于 Yii2 Basic 加强版开发的一款简易项目文档管理工具</p>

            <p>本站仅用于学习分享及团队内部沟通协作!内容均源于互联网!</p>

            <p>如发现有泄漏隐私或侵权内容,请及时通过各种渠道联系站长进行处理,如"联系我们"</p>

            <p>功能尚在完善中, 如有任何意见或建议, 请通过"联系我们"进行反馈 !</p>

            <div class="row">
                <div class="col-md-12">
                    <div class="callout callout-info">
                        您可以通过如下方式赞助我!
                        <p><span class="small">&nbsp;&nbsp; 请备注您的邮箱或在本站的用户名 :)</span></p>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <p>支付宝</p>
                    <p>
                        <img style="width: 200px; height:200px;" src="<?php echo \yii\helpers\Url::to('@web/static/images/alipay.jpg'); ?>" alt="alipay donate" />
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <p>微信</p>
                    <p>
                        <img style="width: 200px; height:200px;" src="<?php echo \yii\helpers\Url::to('@web/static/images/wxpay.jpg'); ?>" alt="weixin donate" />
                    </p>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <i class="fa fa-spinner"></i>
                        赞助列表
                        <span class="small"> - 感谢有你</span>
                    </h4>
                </div>
                <div class="box-body">
                    <div class="callout callout-success">还没有噢, ...</div>
                </div>
            </div>


            <div style="width:100%;height:20px;display: block;clear: both;"></div>
        </div>
    </div>

</div>

<div style="width:100%;height:1px;display: block;clear: both;"></div>

<?php
$this->registerCss('
.section { text-align:left; }
.section .content { text-align: left; }
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