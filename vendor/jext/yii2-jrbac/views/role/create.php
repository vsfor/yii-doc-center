<?php
/* @var $this yii\web\View */
$this->title = '添加角色';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">
    <h3><?php echo $this->title; ?></h3>
    <?php
        echo $this->render('_form',[
            'model'=>$model
        ]);
    ?>
</div>