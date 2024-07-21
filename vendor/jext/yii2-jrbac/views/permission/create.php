<?php
/* @var $this yii\web\View */
$this->title = '添加资源';
$this->params['breadcrumbs'][] = ['label' => '资源列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-create">
    <h3><?php echo $this->title; ?></h3>
    <?php
        echo $this->render('_form',[
            'model'=>$model,
            'rules'=>$rules,
        ]);
    ?>
</div>