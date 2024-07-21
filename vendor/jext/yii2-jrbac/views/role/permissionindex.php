<?php
/**
 * @var $this yii\web\View
 * @var $role yii\rbac\Role
 */
$this->title = '角色:'. $role->name.' 权限管理';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$itemsArr = \yii\helpers\ArrayHelper::getColumn($roleItems,'name');
$ownArr = \yii\helpers\ArrayHelper::getColumn($ownItems, 'name');
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
                'checkboxOptions' => function ($model, $key, $index, $column) use($itemsArr, $ownArr) {
                    $checked = in_array($model->name, $itemsArr);
                    $owned = in_array($model->name, $ownArr);
                    return [
                        'value' => $model->name,
                        'checked' => $checked,
                        'disabled' => $checked && !$owned,
                    ];
                },
            ],
            [
                'attribute'=>'name',
                'header'=>'资源唯一标识',
                'options' => ['class' => 'hidden-xs',],
                'headerOptions' => ['class' => 'hidden-xs',],
                'filterOptions' => ['class' => 'hidden-xs',],
                'contentOptions' => ['class' => 'hidden-xs',],
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
                },
                'options' => ['class' => 'hidden-xs',],
                'headerOptions' => ['class' => 'hidden-xs',],
                'filterOptions' => ['class' => 'hidden-xs',],
                'contentOptions' => ['class' => 'hidden-xs',],
            ],
        ],
    ]); ?>

</div>
<?php
$this->registerJs('

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

');
?>