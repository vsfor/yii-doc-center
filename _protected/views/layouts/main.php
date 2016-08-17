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
    
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
