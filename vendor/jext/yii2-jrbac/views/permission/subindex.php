<?php
/**
 * @var $this yii\web\View
 * @var $permission yii\rbac\Permission
 */
$this->title = '权限:'. $permission->name.' 子权限管理';
$this->params['breadcrumbs'][] = ['label' => '权限列表', 'url' => ['index']];
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
                'header' => '设为子权限',
                'checkboxOptions' => function ($model, $key, $index, $column) use ($permission, $itemsArr) {
                    $am = \Yii::$app->getAuthManager();
                    return [
                        'value' => $model->name,
                        'disabled' => ($model->name == $permission->name) || $am->hasChild($model, $permission),
                        'checked' => in_array($model->name, $itemsArr),
                    ];
                }
            ],
            [
                'attribute'=>'name',
                'header'=>'权限',
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
                url:"'.\yii\helpers\Url::to(['setsub','name'=>$permission->name]).'",
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
                url:"'.\yii\helpers\Url::to(['setsub','name'=>$permission->name]).'",
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