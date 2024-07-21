<?php
/**
 * @var $this yii\web\View
 * @var $rule yii\rbac\Rule
 */
$this->title = '规则:'. $rule->name.' 权限关联管理';
$this->params['breadcrumbs'][] = ['label' => '规则列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">
    <h3><?php echo $this->title; ?></h3>

    <p>
        <?php echo \yii\helpers\Html::a('返回', ['index'], ['class' => 'btn btn-success']) ?>
        <span><b>注</b>: 每个权限资源只可关联一个规则,若需要重置,请先解除原有关联规则</span>
    </p>

    <?php echo \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'multiple'=>false,
                'header' => '关联',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return [
                        'value' => $model->name,
                        'data-rulename' => $model->ruleName ? : '-',
                    ];
                }
            ],
            [
                'attribute'=>'name',
                'header'=>'资源唯一标识',
            ],
            [
                'attribute' => 'ruleName',
                'header' => '已关联规则',
                'value' => function($model) {
                    return $model->ruleName ? : '-';
                }
            ],
            [
                'attribute' => 'description',
                'header' => '描述',
            ],
        ],
    ]); ?>

</div>
 
<?php
$itemsArr = \yii\helpers\ArrayHelper::getColumn($ruleItems,'name');
$itemsStr = implode('","',$itemsArr);
$this->registerJs('$(function(){
    var ruleItems = ["'.$itemsStr.'"];
    var ruleName = "'.$rule->name.'";

    $("input[name=\"selection[]\"]").each(function(i,obj){ 
        if($.inArray(obj.value,ruleItems) !== -1) {
            obj.checked = true;
        }
        var t_ruleName = $(obj).data("rulename");
        if(t_ruleName != "-" && t_ruleName != ruleName) {
            obj.disabled = true;
        }
    }); 

    $("input[name=\"selection[]\"]").change(function(){
        showMask();
        if(this.checked) {
            $.ajax({
                url:"'.\yii\helpers\Url::to(['setpermission','name'=>$rule->name]).'",
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
                url:"'.\yii\helpers\Url::to(['setpermission','name'=>$rule->name]).'",
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