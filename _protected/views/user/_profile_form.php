<?php
use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-form">

    <?php $form = ActiveForm::begin(['id' => 'form-user']); ?>

        <?php echo $form->field($user, 'username')
                    ->textInput([
                        'placeholder' => Yii::t('app', 'input username'),
                        'autofocus' => true]
                    ); ?>
        
        <?php echo $form->field($user, 'email')
            ->input('email', [
                'placeholder' => Yii::t('app', 'Enter e-mail')
            ]); ?>

        <?php if ($user->scenario === 'create'): ?>

            <?= $form->field($user, 'password')->widget(PasswordInput::classname(), 
                ['options' => ['placeholder' => Yii::t('app', 'Create password')]]) ?>

        <?php else: ?>

            <?= $form->field($user, 'password')->widget(PasswordInput::classname(),
                     ['options' => ['placeholder' => Yii::t('app', 'Change password ( if you want )')]]) ?> 

        <?php endif ?>

    <div class="form-group">     
        <?= Html::submitButton($user->isNewRecord ? Yii::t('app', 'Create') 
            : Yii::t('app', 'Update'), ['class' => $user->isNewRecord 
            ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('app', 'Cancel'), ['user/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
 
</div>
