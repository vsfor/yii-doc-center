<?php
use app\helpers\CssHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project List'), 'url' => ['/project/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Update Profile'), 'url' => ['update-profile']];
?>
<div class="user-view">

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            //'password_hash',
            [
                'attribute'=>'status',
                'value' => '<span class="'.CssHelper::userStatusCss($model->status).'">
                                '.$model->getStatusName($model->status).'
                            </span>',
                'format' => 'raw'
            ],
            [
                'attribute'=>'item_name',
                'value' => '<span class="'.CssHelper::roleCss($model->getRoleName()).'">
                                '.$model->getRoleName().'
                            </span>',
                'format' => 'raw'
            ],
            //'auth_key',
            //'password_reset_token',
            //'account_activation_token',
            'created_at:date',
            'updated_at:date',
        ],
    ]); ?>

</div>
