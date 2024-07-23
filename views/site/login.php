<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');

?>
<div class="site-login">
    <div class="section" id="section0">
        <div class="content" style="max-width: 360px;">

            <p><?php echo Yii::t('app', 'Please fill out the following fields to login:'); ?></p>
            <p>体验账号: <b>test</b> 密码: <b>123123</b></p>
            <p><?php echo Html::a('<i class="fa fa-weixin">&nbsp;使用微信登录</i>', (new \app\components\OauthLib())->wxCodeUrl()); ?></p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?php //-- use email or username field depending on model scenario --// ?>
            <?php if ($model->scenario === 'lwe'): ?>

                <?= $form->field($model, 'email')->input('email',
                    ['placeholder' => Yii::t('app', 'Enter your e-mail'), 'autofocus' => true]) ?>

            <?php else: ?>

                <?= $form->field($model, 'username')->textInput(
                    ['placeholder' => Yii::t('app', 'Enter your username'), 'autofocus' => true]) ?>

            <?php endif ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Enter your password')]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
                <?= Yii::t('app', 'If you forgot your password you can') ?>
                <?= Html::a(Yii::t('app', 'reset it'), ['site/request-password-reset']) ?>.
                <?php if (isset($needActivate) && $needActivate) : ?>
                    <p>
                        <?= Yii::t('app', 'If your did not got activate email.') ?>
                        <?= Html::a(Yii::t('app', 'resend activate email'), ['site/request-activate']) ?>.
                    </p>
                <?php endif; ?>
                <p><?= '还没有账号? '.Html::a('去注册&gt;', ['signup'],['class' => 'text text-success']) ?></p>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
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

