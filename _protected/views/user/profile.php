<?php
use app\helpers\CssHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;

?>
<div class="user-view">
    <div id="section0" class="section">
        <div class="content" style="max-width: 500px;">
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
    </div>
</div>

    <div style="width:100%;height:1px;display: block;clear: both;"></div>

<?php
$this->registerCss('
.section { text-align:left; }
.section .content { text-align: left; }
.section .content .box { background: none; }
.section .content .box .with-border { border-bottom: 1px solid #a90070; }
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