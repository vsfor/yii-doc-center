<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h3><?php echo Html::encode($this->title) ?></h3>

    <p>基于 Yii2 Basic 加强版开发的一款简易项目文档管理工具</p>

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
            <div class="callout callout-success">还没有噢, 这只猿还在孤军奋战...</div>
        </div>
    </div>

</div>
