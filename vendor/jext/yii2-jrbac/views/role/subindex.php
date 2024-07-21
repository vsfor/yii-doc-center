<?php
/**
 * @var $this yii\web\View
 * @var $role yii\rbac\Role
 */
$this->title = '角色:'. $role->name.' 子角色管理';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$itemsArr = \yii\helpers\ArrayHelper::getColumn($subItems,'name');
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
                'header' => '设为子角色',
                'checkboxOptions' => function ($model, $key, $index, $column) use ($role, $itemsArr) {
                    $am = \Yii::$app->getAuthManager();
                    return [
                        'value' => $model->name,
                        'disabled' => ($model->name == $role->name) || $am->hasChild($model, $role),
                        'checked' => in_array($model->name, $itemsArr),
                    ];
                }
            ],
            [
                'attribute'=>'name',
                'header'=>'角色',
            ],
            [
                'attribute' => 'description',
                'header' => '描述',
            ],

        ],
    ]); ?>

</div>
<?php
$itemsStr = implode('","',$itemsArr);
$this->registerJs('

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

');
?>