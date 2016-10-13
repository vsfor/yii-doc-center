<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = Yii::t('app', Yii::$app->name);

?>
<div class="rest-index" style="margin-top:50px;">
    <div class="content">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-spinner"></i>
                    API接口在线测试工具
                    <span class="small"> - 简单实用! 欢迎体验!</span>
                </h3>

                <?php //echo Html::a(Yii::t('app', 'Manage'), ['admin'], ['class' => 'pull-right']); ?>

            </div>
            <div class="box-body">
                <div id="urlblock" class="row">
                    <div class="col-xs-2">
                        <?php
                        echo Html::dropDownList('request-type', 'get', [
                            'get' => 'Get',
                            'post' => 'Post',
                            'delete' => 'Delete',
                            'put' => 'Put',
                            'other' => '待完善'
                        ],['id'=>'requestType']);
                        echo Html::dropDownList('params-type', 'get', [
                            'form' => 'Form',
                            'json' => 'Json',
                        ],['id'=>'paramsType']);
                        ?>
                    </div>
                    <div class="col-xs-4">
                        <?php echo Html::input('text', 'request-url', '',[
                            'placeholder' => '接口请求Url',
                            'style' => 'width:100%',
                            'id'=>'requestUrl'
                        ]); ?>
                    </div>
                    <div class="col-xs-2">
                        <?php echo Html::button('发送请求',[
                                'class' => 'btn btn-xs btn-success sendRequestBtn',
                            ]); ?>
                    </div>
                    <div class="col-xs-2">
                        <?php
                        echo Html::checkbox('addHeaderCheck',false,['id' => 'addHeaderCheck']);
                        echo Html::label('自定义Header','addHeaderCheck');
                        ?>
                    </div>
                    <div class="col-xs-2">
                        <?php
                        echo Html::checkbox('addParamCheck',true,['id' => 'addParamCheck']);
                        echo Html::label('添加参数','addParamCheck');
                        ?>
                    </div>
                </div>
                <div id="headersBlock" class="col-xs-12 itemsBlock" style="display: none;">
                    <div class="row blockItem">
                        <div class="col-xs-4">
                            <?php echo 'Header键名'; ?>
                        </div>
                        <div class="col-xs-6">
                            <?php echo 'Header键值'; ?>
                        </div>
                        <div class="col-xs-1">
                            <?php echo '操作'; ?>
                        </div>
                    </div>
                    <div class="row blockItem">
                        <div class="col-xs-4">
                            <?php echo Html::input('text','headerKeys[]','',['style'=>'width:100%;']); ?>
                        </div>
                        <div class="col-xs-6">
                            <?php echo Html::input('text','headerValues[]','',['style'=>'width:100%;']); ?>
                        </div>
                        <div class="col-xs-1">
                            <?php echo Html::button('移除',[
                                'class' => 'btn btn-xs btn-danger delBtn',
                            ]); ?>
                        </div>
                    </div>
                    <div class="row" id="headersBlockBtn">
                        <?php echo Html::button('添加自定义Header',[
                            'class' => 'btn btn-xs btn-warning',
                            'id' => 'addHeadersBtn',
                        ]); ?>
                    </div>
                </div>
                <div id="paramsBlock" class="col-xs-12 itemsBlock" style="display: block;">
                    <div class="row blockItem">
                        <div class="col-xs-2">
                            <?php echo '类型'; ?>
                        </div>
                        <div class="col-xs-4">
                            <?php echo '参数名'; ?>
                        </div>
                        <div class="col-xs-4">
                            <?php echo '参数值'; ?>
                        </div>
                        <div class="col-xs-1">
                            <?php echo '操作'; ?>
                        </div>
                    </div>
                    <div class="row blockItem">
                        <div class="col-xs-2">
                            <?php echo Html::dropDownList('paramTypes[]', 'get', [
                                'get' => 'Get',
                                'post' => 'Post',
                            ]); ?>
                        </div>
                        <div class="col-xs-4">
                            <?php echo Html::input('text','paramKeys[]','',['style'=>'width:100%;']); ?>
                        </div>
                        <div class="col-xs-4">
                            <?php echo Html::input('text','paramValues[]','',['style'=>'width:100%;']); ?>
                        </div>
                        <div class="col-xs-1">
                            <?php echo Html::button('移除',[
                                'class' => 'btn btn-xs btn-danger delBtn',
                            ]); ?>
                        </div>
                    </div>
                    <div class="row" id="paramsBlockBtn">
                        <?php echo Html::button('添加参数',[
                            'class' => 'btn btn-xs btn-primary',
                            'id' => 'addParamsBtn',
                        ]); ?>
                    </div>
                </div>
                <div class="col-xs-12 itemsBlock">
                    <div class="row text-center">
                        <?php echo Html::button('发送请求',[
                            'class' => 'btn btn-sm btn-success sendRequestBtn',
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <h4 class="explainer">接口测试简介 | Introduction</h4>
                <ul class="new_tools_list">
                    <li>接口测试一般以用于多系统间交互开发.主要测试这些系统对外部提供的接口,验证其正确性及稳定性.</li>
                    <li>一般应用使用Web-http的方式,为APP提供数据接口,这些接口具有一定的动态性,接口通过参数获取不同的数据返回给使用者.</li>
                    <li>本工具提供特定接口的HTTP测试,并且提供测试返回值,并对接口请求的异常状态进行获取,反馈给用户.</li>
                    <li>请求参数传递方式包含form及json两种形式,当选择json时,post类型的参数会以json的形式进行传递.</li>
                    <li>备注：具体结果与本站服务器及网络因素有关，仅供参考。</li>
                </ul>
            </div>
        </div>

    </div>
</div>
<?php
$this->registerCss('
    .box-body .row { padding: 3px 0; }
    #urlblock input[type="checkbox"] { margin:0 5px; }
    .itemsBlock .row { border-top: 1px solid #ddd; }
');
$this->registerJs('
    $("#addHeaderCheck").change(function(){
        if (this.checked) {
            $(this).attr("checked",true);
            $("#headersBlock").show();
        } else {
            $("#headersBlock").hide();
            $(this).attr("checked",false);
        }
    });
    $("#addHeadersBtn").click(function(){
        var tHeaderHtml = "<div class=\"row blockItem\"><div class=\"col-xs-4\"><input type=\"text\" name=\"headerKeys[]\" value=\"\" style=\"width:100%;\"></div><div class=\"col-xs-6\"><input type=\"text\" name=\"headerValues[]\" value=\"\" style=\"width:100%;\"></div><div class=\"col-xs-1\"><button type=\"button\" class=\"btn btn-xs btn-danger delBtn\">移除</button></div></div>";
        $("#headersBlockBtn").before(tHeaderHtml); 
    });
    
    $("#addParamCheck").change(function(){
        if (this.checked) {
            $(this).attr("checked",true);
            $("#paramsBlock").show();
        } else {
            $("#paramsBlock").hide();
            $(this).attr("checked",false);
        }
    });
    $("#addParamsBtn").click(function(){
        var tParamHtml = "<div class=\"row blockItem\"><div class=\"col-xs-2\"><select name=\"paramTypes[]\"><option value=\"get\" selected=\"\">Get</option><option value=\"post\">Post</option></select></div><div class=\"col-xs-4\"><input type=\"text\" name=\"paramKeys[]\" style=\"width:100%;\"></div><div class=\"col-xs-4\"><input type=\"text\" name=\"paramValues[]\" style=\"width:100%;\"></div><div class=\"col-xs-1\"><button type=\"button\" class=\"btn btn-xs btn-danger delBtn\">移除</button></div></div>";
        $("#paramsBlockBtn").before(tParamHtml);
    });
    
    $(".itemsBlock").on("click",".delBtn",function(){
        console.log($(this).parents(".blockItem"));
        $(this).parents(".blockItem").remove();
    });
    
    $(".sendRequestBtn").click(function(){
        var reqData = {};
        reqData.requestType = $("#requestType").val();
        reqData.paramsType = $("#paramsType").val();
        reqData.requestUrl = $.trim($("#requestUrl").val());
        if (reqData.requestUrl == "") {
            return false;
        }
        if ($("#addHeaderCheck").attr("checked") == "checked") {
            reqData.diyHeader = "yes";   
            reqData.headers = [];
            $("input[name=\"headerKeys[]\"]").each(function(i,e){
                var tItem = {
                    key: e.value,
                    value: $("input[name=\"headerValues[]\"]").eq(i).val()
                };
                reqData.headers.push(tItem);
            });
        } else {
            reqData.diyHeader = "no";
        }
        if ($("#addParamCheck").attr("checked") == "checked") {
            reqData.diyParam = "yes";
            reqData.params = [];
            $("select[name=\"paramTypes[]\"]").each(function(i,e){
                var tItem = {
                    type: e.value,
                    key: $("input[name=\"paramKeys[]\"]").eq(i).val(),
                    value: $("input[name=\"paramValues[]\"]").eq(i).val()
                };
                reqData.params.push(tItem);
            });
        } else {
            reqData.diyParam = "no";
        }
        console.log(reqData);
        console.log("start");
        $.ajax({
            url: "'.\yii\helpers\Url::to(['sendrest']).'",
            type: "POST",
            data: reqData,
            dataType: "json",
            success: function(r) {
                console.log(r);
            }
        });
        console.log("done");
    });
');
?>
