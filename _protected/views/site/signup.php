<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Signup');

?>
<div class="site-signup">
    <div class="section" id="section0">
        <div class="content" style="max-width: 560px;">
            <p>
                <?= '请填写注册信息: '; ?>
                <?= ' (<span style="color:#777;">已经有账号了?</span> '.Html::a('去登录&gt;', ['login'],['class' => 'text text-success']).')'; ?>
            </p>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')->textInput(
                ['placeholder' => Yii::t('app', 'Create your username'), 'autofocus' => true]) ?>

            <?= $form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter your e-mail')]) ?>

            <?= $form->field($model, 'password')->widget(PasswordInput::classname(),
                ['options' => ['placeholder' => Yii::t('app', 'Create your password')]]) ?>

            <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
                'template' =>
                    '<div class="row">
                        <div class="col-md-6 pull-left">{input}</div>
                        <div class="col-md-4 pull-left">{image}</div>
                    </div>',
                'options' => ['placeholder' => '请输入验证码','class' => 'form-control'],
            ])
            ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Signup'),
                    ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php if ($model->scenario === 'rna'): ?>
                <div style="color:#666;margin:1em 0">
                    <i>*<?= Yii::t('app', 'We will send you an email with account activation link.') ?></i>
                </div>
            <?php endif ?>


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