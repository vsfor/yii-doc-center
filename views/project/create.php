<?php

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = Yii::t('app', 'Create Project');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project List'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-create">
    <div id="section0" class="section">
        <div class="content" style="max-width: 500px;">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

            <div style="width:100%;height:20px;display: block;clear: both;"></div>
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