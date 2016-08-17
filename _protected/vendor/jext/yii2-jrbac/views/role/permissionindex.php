<?php
/**
 * @var $this yii\web\View
 * @var $role yii\rbac\Role
 */
$this->title = '角色:'. $role->name.' 权限管理';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">
    <h3><?php echo $this->title; ?></h3>

    <p>
        <?php echo \yii\helpers\Html::a('返回', ['index'], ['class' => 'btn btn-success']) ?>
        <?php //echo \yii\helpers\Html::a('提交', ['setpermission','name'=>$role->name], ['class' => 'btn btn-success','id'=>'batchSet']) ?>
    </p>

    <?php echo \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'multiple'=>false,
                'header' => '授予',
            ],
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

        ],
    ]); ?>

</div>
<?php
$itemsArr = \yii\helpers\ArrayHelper::getColumn($roleItems,'name');
$itemsStr = implode('","',$itemsArr);
$this->registerJs('$(function(){
    var roleItems = ["'.$itemsStr.'"];

    $("input[name=\"selection[]\"]").each(function(i,obj){
        if($.inArray(obj.value,roleItems) !== -1) {
            obj.checked = true;
        }
    });



    $("input[name=\"selection[]\"]").change(function(){
        showMask();
        if(this.checked) {
            $.ajax({
                url:"'.\yii\helpers\Url::to(['setpermission','name'=>$role->name]).'",
                type:"post",
                data:{act:"add",val:this.value},
                success:function(data) {
                    hideMask();
                    if(data === "1") $().toastmessage("showSuccessToast","操作成功");
                    else $().toastmessage("showErrorToast","操作失败，请刷新页面重试");
                }
            });
        }
        if(!this.checked) {
            $.ajax({
                url:"'.\yii\helpers\Url::to(['setpermission','name'=>$role->name]).'",
                type:"post",
                data:{act:"del",val:this.value},
                success:function(data) {
                    hideMask();
                    if(data === "1") $().toastmessage("showSuccessToast","操作成功");
                    else $().toastmessage("showErrorToast","操作失败，请刷新页面重试");
                }
            });
        }
        hideMask();
    });


//    $("#batchSet").click(function(){
//        showMask();
//        var selectedNames = [];
//        $("input[name=\"selection[]\"]").each(function(i,obj){
//            if(obj.checked) {
//                selectedNames.push(obj.value);
//            }
//        });
//        if(selectedNames.length === 0) selectedNames=["清空所有权限"];
//        if(confirm("确认提交:"+selectedNames.toString()+" ?")) {
//            $.ajax({
//                url:this.href,
//                type:"post",
//                data:{names:selectedNames},
//                success:function(data) {
//                    hideMask();
//                    if(data === "1") $().toastmessage("showSuccessToast","保存成功");
//                    else $().toastmessage("showErrorToast","操作失败，请刷新页面重试");
//                }
//            });
//        }
//        hideMask();
//        return false;
//    });

});');
?>