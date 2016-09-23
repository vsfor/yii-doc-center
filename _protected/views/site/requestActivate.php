<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Request Activate');

?>
<div class="site-request-activate">
    <div class="section" id="section0">
        <div class="content">
            <h2><?= Html::encode($this->title) ?></h2>

            <p><?= Yii::t('app', 'A link to activate will be sent to your email.') ?></p>

            <?php $form = ActiveForm::begin(['id' => 'request-activate-form']); ?>

            <?= $form->field($model, 'email')->input('email',
                ['placeholder' => Yii::t('app', 'Please fill out your email.'), 'autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <div style="width:100%;height:20px;display: block;clear: both;"></div>
        </div>
    </div>

</div>
    <div style="width:100%;height:1px;display: block;clear: both;"></div>
<?php
$this->registerCss('
.section { text-align:left; }
.section .content { text-align: left; }
');


$this->registerJs('
	$("#fullpage").fullpage({
            autoScrolling: false,
            animateAnchor:false, //need
            scrollOverflow: true,
            scrollingSpeed: 1000, 
            
            paddingTop: "50px", 
            paddingBottom: "0",
            
            verticalCentered: true,
            resize: false, 
            responsive: 900
        });
');


?>