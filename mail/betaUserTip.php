<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$loginLink = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);
?>

<p>
    <?php echo Yii::t('app', 'Hello');?> <?php echo Html::encode($user->username) ?>,
</p>
<p>
    <?php echo '感谢使用 YDC 文档管理系统 :) '; ?> <br/>
</p>
<p>
    <?php echo '您已获得内测使用资格,欢迎体验使用.期待您的反馈交流.'; ?>
</p>
<p><?php echo Html::a('点我去体验',$loginLink);?></p>
<p>
    <?php echo '这就去看看吧 ->' .'<br/>'. $loginLink; ?>
</p>

