<?php
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::$app->name) .' '. Yii::t('app', 'news');
$this->params['breadcrumbs'][] = Yii::t('app', 'Articles');
?>
<div class="article-index">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-spinner"></i>
                <?php echo $this->title;?>
                <span class="small"> - <?= Yii::t('app', 'The latest news available') ?></span>
            </h3>

            <?php if (Yii::$app->user->can('adminArticle')): ?>
                <?php echo Html::a(Yii::t('app', 'Manage'), ['admin'], ['class' => 'pull-right']); ?>
            <?php endif ?>

        </div>
        <div class="box-body">

            <?= ListView::widget([
                'summary' => false,
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('app', 'We haven\'t created any articles yet.'),
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_index', ['model' => $model]);
                },
            ]) ?>

        </div>
    </div>


</div>
