<?php
/* @var $this yii\web\View */
/* @var $model app\models\Page */
if (!is_array($model)) {
    $model = $model->toArray();
}
?>
<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo \yii\helpers\Url::to('@web/static/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo \yii\helpers\Url::to('@web/static/css/bootstrap.css'); ?>" />
    <link rel="stylesheet" href="<?php echo \yii\helpers\Url::to('@web/static/editor.md/css/editormd.preview.css'); ?>" />
    <link rel="stylesheet" href="<?php echo \yii\helpers\Url::to('@web/static/css/newpdf.css'); ?>" />
</head>
<body>
<div class="page-view">
    <h3><?php echo $model['title']; ?></h3>
    <pre><?php echo $model['description']; ?></pre>
    <div class="markdown-body editormd-html-preview">
        <?php
//            echo (new \app\components\ParseDown())->text(trim($model['content']));
            echo (new \app\components\MDParser())->makeHtml(trim($model['content']));
        ?>
    </div>
</div>
</body>
</html>