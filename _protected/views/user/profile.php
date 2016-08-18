<?php
use app\helpers\CssHelper;
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
            [
                'attribute'=>'status',
                'value' => '<span class="'.CssHelper::userStatusCss($model->status).'">
                                '.$model->getStatusName($model->status).'
                            </span>',
                'format' => 'raw'
            ],
            'created_at:date',
            'updated_at:date',
        ],
    ]); ?>

</div>
