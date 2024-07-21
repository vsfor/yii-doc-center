<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h2>Error:<?= Html::encode($this->title) ?></h2>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p> <?= Yii::t('app', 'The above error occurred while the Web server was processing your request.') ?> </p>
    <p> <?= '如发现问题,请通过"联系我们"进行反馈!   谢谢 :)'; ?> </p>

</div>
<?php
$this->registerCss('
    .site-error {  padding: 80px 20px 20px 20px;}
');
?>