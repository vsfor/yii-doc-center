<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $index int index */

$bg_colors = [
    'bg-aqua','bg-green','bg-orange','bg-purple','bg-red'
];
?>
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box <?php echo $bg_colors[$index%5];?>">
        <div class="inner">
            <h5><?php echo $model->name; ?></h5>
            <p><?php echo $model->description; ?></p>
        </div>
        <div class="icon">
            <i class="fa fa-bookmark-o"></i>
        </div>
        <a href="<?php echo Url::to(['view','id'=>$model->id])?>" class="small-box-footer">
            <?php echo Yii::t('app','More info');?>
            <i class="fa fa-arrow-circle-right"></i>
        </a>
    </div>
</div>