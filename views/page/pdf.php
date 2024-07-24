<?php
/* @var $this yii\web\View */
/* @var $model app\models\Page */
if (!is_array($model)) {
    $model = $model->toArray();
}
?>
<h3><?php echo $model['title']; ?></h3>
<div class="markdown-body editormd-html-preview">
<?php
    echo (new \app\components\MDParser())->makeHtml($model['content']);
?>
</div>