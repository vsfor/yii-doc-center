<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/activate-account', 
    'token' => $user->account_activation_token]);
?>

<p>
    <?php echo Yii::t('app', 'Hello');?> <?php echo Html::encode($user->username) ?>,
</p>
<p>
    <?php echo Yii::t('app', 'Follow this link to activate your account:'); ?> <br/>
</p>
<p>
    <?php echo Html::a(Yii::t('app','Please, click here to activate your account.'), $resetLink) ?>
</p>
<p>
    <?php echo Yii::t('app','Or, view the link page by yourself.') .'<br/>'. $resetLink; ?>
</p>

