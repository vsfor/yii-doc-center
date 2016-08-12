<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContactForm */

?>
<div style="padding: 30px 20px; border:1px solid #ddd;">
    <p>
        <span>Name: </span><?php echo Html::encode($model->name); ?>
    </p>
    <p>
        <span>Email: </span><?php echo Html::encode($model->email); ?>
    </p>
    <p>
        <span>Subject: </span><?php echo Html::encode($model->subject); ?>
    </p>
    <p>
        <span>Body: </span><?php echo Html::encode($model->body); ?>
    </p>
</div>



