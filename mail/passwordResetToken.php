<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 
    'token' => $user->password_reset_token]);
?>

<p>
    <?php echo Yii::t('app', 'Hello').' '.Html::encode($user->username); ?>,
</p>
<p>
    <?php echo Yii::t('app', 'Follow this link to reset your password:'); ?>
</p>
<p>
    <?= Html::a(Yii::t('app', 'Please, click here to reset your password.'), $resetLink) ?>
</p>
<p>
    <?php echo Yii::t('app','Or, view the link page by yourself.') .'<br/>'. $resetLink; ?>
</p>
