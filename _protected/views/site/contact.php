<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Contact');

?>
<div class="site-contact">
    <div class="section" id="section0">
        <div class="content" style="max-width: 600px;margin-top: 20px;">
            <p>
                <?= Yii::t('app', 'If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.'); ?>
            </p>

<?php
if (isset(\Yii::$app->params['open_contact'])
    && (\Yii::$app->params['open_contact'] == false)
) : ?>
    <?php echo str_repeat('<br>',10);?>
    <p>临时关闭联系功能，如有需求请加Q: 692343993</p>
    <?php echo str_repeat('<br>',20);?>
<?php else: ?>
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?php echo $form->field($model, 'name')->textInput(
                ['placeholder' => '您的姓名或称呼', 'autofocus' => true]) ?>

            <?php echo $form->field($model, 'email')->input('email', ['placeholder' => '您的邮箱,方便我们与您联系']) ?>

            <?php echo $form->field($model, 'subject')->textInput(['placeholder' => '邮件主题']) ?>

            <?php echo $form->field($model, 'body')->textArea(['rows' => 6]) ?>

            <?php echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' =>
                    '<div class="row">
                    <div class="col-md-6 pull-left">{input}</div>
                    <div class="col-md-4 pull-left">{image}</div>
                </div>',
                'options' => ['placeholder' => '输入验证码','class' => 'form-control'],
            ])
            ?>

            <div class="form-group">
                <?php echo Html::submitButton(Yii::t('app', 'Submit'),
                    ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

<?php endif; ?>
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