<?php
/* @var $this yii\web\View */
$this->title = '规则:'. $item->name;
$this->params['breadcrumbs'][] = ['label' => '规则列表', 'url' => ['index']];
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
                'label'=>'规则唯一标识',
            ],
            [
                'attribute'=>'className',
                'label'=>'规则类名',
                'value'=>$item::className(),
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