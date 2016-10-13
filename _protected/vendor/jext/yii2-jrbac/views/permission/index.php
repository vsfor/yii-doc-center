<?php
/**
 * @var $this yii\web\View
 * @var $role yii\rbac\Role
 */
$this->title = '资源列表管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-index">
    <h3><?php echo $this->title; ?></h3>

    <p>
        <?php echo \yii\helpers\Html::a('添加资源', ['create'], ['class' => 'btn btn-success']) ?>
        <?php echo \yii\helpers\Html::a('批量删除', ['delete'], ['class' => 'btn btn-success','id'=>'batchDelete']) ?>
        <?php echo \yii\helpers\Html::a('初始化', ['init'], ['class' => 'btn btn-danger','id'=>'initBtn']) ?>
        <span id="lastInitTime">
            <?php
            if(isset($lastTime) && $lastTime) {
                echo '上次初始化时间:'.date("Y-m-d H:i", $lastTime);
            }
            ?>
        </span>
    </p>

    <?php echo \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn','multiple'=>true],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'name',
                'header'=>'资源唯一标识',
            ],
            [
                'attribute' => 'description',
                'header' => '描述',
            ],
            [
                'attribute' => 'ruleName',
                'header' => '关联规则',
                'value' => function($model) {
                    return $model->ruleName ? : '-';
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
                'class' => '\yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}'
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
    
    $("#initBtn").click(function(){
        showMask();
        if (confirm("初始化时间可能较长\n确认开始自动扫描并添加资源项? ")) {
            $("#lastInitTime").slideUp();
            $.ajax({
                url:"'.\yii\helpers\Url::to(['init']).'",
                type:"post",
                success:function(data) {
                    hideMask();
                    if (data != "error") {
                        $().toastmessage("showSuccessToast","操作成功");
                        $("#lastInitTime").html(data);    
                        $("#lastInitTime").slideDown();
                    } else {
                        $().toastmessage("showErrorToast","失败,请联系管理员");
                    } 
                }
            });
        } else {
            hideMask();
        }
        return false;
    });
    
});');
?>