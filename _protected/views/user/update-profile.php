<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

$this->title = Yii::t('app', 'Update Profile') . ': ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project List'), 'url' => ['/project/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile').':'.$user->username, 'url' => ['profile', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <div class="col-md-5 well bs-component">

        <?= $this->render('_profile_form', ['user' => $user]) ?>

    </div>

</div>
