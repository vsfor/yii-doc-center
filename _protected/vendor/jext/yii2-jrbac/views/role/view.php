<?php
/* @var $this yii\web\View */
$this->title = '角色:'. $item->name;
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-view">
    <h3><?php echo $this->title; ?></h3>
    <p>
        <?php echo \yii\helpers\Html::a('编辑', ['update', 'id' => $item->name], ['class' => 'btn btn-primary']) ?>
        <?php echo \yii\helpers\Html::a('删除', ['delete', 'id' => $item->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确认删除?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <p>基础信息</p>
    <?php echo \yii\widgets\DetailView::widget([
        'model' => $item,
        'attributes' => [
            [
                'attribute'=>'name',
                'label'=>'角色唯一标识',
            ],
            [
                'attribute' => 'description',
                'label' => '描述',
            ],
            [
                'attribute' => 'createdAt',
                'label' => '创建时间',
                'value' => ''.date("Y-m-d H:i:s", $item->createdAt),
            ],
            [
                'attribute' => 'updatedAt',
                'label' => '更新时间',
                'value' => ''.date("Y-m-d H:i:s", $item->updatedAt),
            ],
        ],
    ]) ?>
    <p>权限列表</p>

</div>