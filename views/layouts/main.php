<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

app\assets\AppAsset::register($this);

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="API文档管理工具,免费开源,api,document,yii2,php,php7">
    <meta name="description" content="免费开源易用的API接口文档管理工具,API在线测试,类似于微信开放平台,阿里开放平台的文档系统,样式清晰,简单实用.基于Yii2开发采用RBAC实现权限划分.">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="http://hm.baidu.com/hm.js?e271b786cb963c890165519c3c52ae0a"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">
    <?php
        echo $this->render('header.php', [
            'directoryAsset' => $directoryAsset
        ]);
    ?>
    <?php
        echo $this->render('left.php', [
                'directoryAsset' => $directoryAsset
            ]);
    ?>
    <?php
        echo $this->render('content.php', [
            'content' => $content,
            'directoryAsset' => $directoryAsset
        ]);
    ?>


    <?php
    if (!function_exists('j_view_show_mask')) {
        echo $this->render('mask.php');
    }
    ?>
    
    <?php 
    $this->registerJs('
        console.log("欢迎沟通交流\n 通过联系我们发送邮件 \n 或访问我的博客进行留言 \n\n有任何意见或建议都可以反馈给我噢 :)");
    ');
    ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
