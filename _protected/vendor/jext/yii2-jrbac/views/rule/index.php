<?php
/**
 * @var $this yii\web\View
 * @var $role yii\rbac\Role
 */
$this->title = '规则列表管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">
    <h3><?php echo $this->title; ?></h3>

    <p>
        <?php echo \yii\helpers\Html::a('添加规则', ['create'], ['class' => 'btn btn-success']) ?>
        <?php echo \yii\helpers\Html::a('批量删除', ['delete'], ['class' => 'btn btn-success','id'=>'batchDelete']) ?>
    </p>

    <?php echo \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn','multiple'=>true],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'name',
                'header'=>'规则唯一标识',
            ],
            [
                'attribute'=>'className',
                'header'=>'规则类名',
                'value' => function($model) {
                    return $model::className();
                }
            ],
//            [
//                'attribute' => 'createdAt',
//                'header' => '创建时间',
//                'value' => function($model) {
//                    return date("Y-m-d H:i",$model->createdAt);
//                }
//            ],
//            [
//                'attribute' => 'updatedAt',
//                'header' => '更新时间',
//                'value' => function($model) {
//                    return date("Y-m-d H:i",$model->updatedAt);
//                }
//            ],

            [
                'header' => '操作',
                'class' => 'jext\jrbac\vendor\RuleActionColumn',
//                'template' => '{view} {update} {delete}'
            ],
        ],
    ]); ?>

</div>
<?php
$this->registerJs('$(function(){
    $("#batchDelete").click(function(){
        showMask();
        var delNames = [];
        $("input[name=\"selection[]\"]").each(function(i,obj){
            if(obj.checked) {
                delNames.push(obj.value);
            }
        });
        if(delNames.length !== 0 && confirm("确认删除:"+delNames.toString()+" ?")) {
            $.ajax({
                url:this.href,
                type:"post",
                data:{names:delNames},
                success:function(data) {
                    hideMask();
                    if(data === "1") window.location.href = window.location.href;
                    else alert("删除失败，请刷新页面重试");
                }
            });
        }
        hideMask();
        return false;
    });
});');
?>