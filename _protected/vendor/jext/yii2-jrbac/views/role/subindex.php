<?php
/**
 * @var $this yii\web\View
 * @var $role yii\rbac\Role
 */
$this->title = '角色:'. $role->name.' 子角色管理';
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
                'header' => '关联为子角色',
            ],
            [
                'attribute'=>'name',
                'header'=>'角色唯一标识',
            ],
            [
                'attribute' => 'description',
                'header' => '描述',
            ],

        ],
    ]); ?>

</div>
<?php
$itemsArr = \yii\helpers\ArrayHelper::getColumn($subItems,'name');
$itemsStr = implode('","',$itemsArr);
$this->registerJs('$(function(){
    var subItems = ["'.$itemsStr.'"];

    $("input[name=\"selection[]\"]").each(function(i,obj){
        if($.inArray(obj.value,subItems) !== -1) {
            obj.checked = true;
        }
    });



    $("input[name=\"selection[]\"]").change(function(){
        showMask();
        if(this.checked) {
            $.ajax({
                url:"'.\yii\helpers\Url::to(['setsub','name'=>$role->name]).'",
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
                url:"'.\yii\helpers\Url::to(['setsub','name'=>$role->name]).'",
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

});');
?>