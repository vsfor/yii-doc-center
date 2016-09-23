<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Reset password');
?>
<div class="site-reset-password">
    <div class="section" id="section0">
        <div class="content">
            <h2><?= Html::encode($this->title) ?></h2>

            <p><?= Yii::t('app', 'Please choose your new password:') ?></p>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->widget(PasswordInput::classname(),
                ['options' => ['placeholder' => Yii::t('app', 'Enter new password'), 'autofocus' => true]]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
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
