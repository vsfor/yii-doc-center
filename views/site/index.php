<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', Yii::$app->name);
?>
<div class="site-index">
    <div class="section" id="section0">
        <div class="content">
            <h3 class="lead text-bold">文档在线管理工具</h3>
            <p class="lead">简单实用! 欢迎体验!</p>
            <p class="lead">Based on Yii2 & Markdown </p>
            <p>
                <?php if(Yii::$app->getUser()->getIsGuest()) : ?>
                    <a class="btn btn-success" href="<?php echo \yii\helpers\Url::to(['/site/signup'])?>">注册并开始使用</a>
                <?php endif; ?>
                <a class="btn btn-primary" target="_blank" href="https://github.com/vsfor/yii-doc-center">源码查阅</a>
            </p>

            <p><br/></p>
            <p><br/></p>
            <p><br/></p>
            <p><br/></p>
            <p><br/></p>
            <div class="callout callout-success">
                <p class=""><span style="color:#fff;">测试账号信息:<br/> UserName: test /  PassWord: 123123</span></p>
            </div>
        </div>
    </div>
</div>
