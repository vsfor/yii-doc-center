<?php
/* @var $this yii\web\View */
$this->title = '资源:'. $item->name;
$this->params['breadcrumbs'][] = ['label' => '资源列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-view">
    <h3><?php echo $item->name; ?></h3>
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
    <?php
    $parentItemsDes = '';
    foreach ($parentItems as $parentItem) {
        $parentItemsDes .= $parentItem->name.':'.$parentItem->description . '<br/>';
    }
    echo \yii\widgets\DetailView::widget([
        'model' => $item,
        'attributes' => [
            [
                'attribute'=>'name',
                'label'=>'资源唯一标识',
            ],
            [
                'attribute' => 'description',
                'label' => '描述',
            ],
            [
                'label' => '父级资源',
                'format' => 'raw',
                'value' => $parentItemsDes,
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
</div>