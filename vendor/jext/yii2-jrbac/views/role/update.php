<?php
/* @var $this yii\web\View */
$this->title = '编辑角色:'. $model->name;
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-update">
    <h3><?php echo $this->title; ?></h3>
    <?php
        echo $this->render('_form',[
            'model'=>$model
        ]);
    ?>
</div>