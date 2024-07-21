<?php
/**
 * @var $this yii\web\View
 * @var $role yii\rbac\Role
 */
$this->title = '角色:'. $role->name.' 用户管理';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">
    <h3><?php echo $this->title; ?></h3>

    <p>
        <?php echo \yii\helpers\Html::a('返回', ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'multiple'=>false,
                'header' => '授予',
            ],
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'username',
                'header'=>'用户名',
            ],
            [
                'attribute' => 'email',
                'header' => '邮箱',
            ],    
        ],
    ]); ?>

</div>
<?php
$itemsStr = implode('","',$roleUserIds);
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
                url:"'.\yii\helpers\Url::to(['setuser','name'=>$role->name]).'",
                type:"post",
                data:{act:"add",val:this.value},
                success:function(data) {
                    hideMask();
                    if(data === "1") $().toastmessage("showSuccessToast","操作成功");
                    else $().toastmessage("showSuccessToast","操作失败，请刷新页面重试");
                }
            });
        }
        if(!this.checked) {
            $.ajax({
                url:"'.\yii\helpers\Url::to(['setuser','name'=>$role->name]).'",
                type:"post",
                data:{act:"del",val:this.value},
                success:function(data) {
                    hideMask();
                    if(data === "1") $().toastmessage("showSuccessToast","操作成功");
                    else $().toastmessage("showSuccessToast","操作失败，请刷新页面重试");
                }
            });
        }
        hideMask();
    });

});');
?>