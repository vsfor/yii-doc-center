<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
$this->title = 'Articles';
?>
<div class="callout callout-success">

    <h5>
        <a href=<?= Url::to(['article/view', 'id' => $model->id]) ?>><?= $model->title ?></a>
    </h5>

    <p><?php echo $model->summary ?></p>

    <p class="time">
        <?php echo $model->getCategoryName(); ?>
        <span class="glyphicon glyphicon-time"></span>
        <?php echo Yii::t('app','Published on') ?> 
        <?php echo date('Y-m-d H:i', $model->created_at) ?>

        <a href=<?= Url::to(['article/view', 'id' => $model->id]) ?>>
            <?= Yii::t('app','Read more') ?>
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </p>
</div>
