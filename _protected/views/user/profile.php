<?php
use app\helpers\CssHelper;
use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$oAuthLib = new \app\components\OauthLib();
$userWxs = $oAuthLib->userWxRows($model->id);

?>
<div class="user-view">
    <div id="section0" class="section">
        <div class="content" style="max-width: 500px;">
            <?php echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'email:email',
                    [
                        'attribute'=>'status',
                        'value' => '<span class="'.CssHelper::userStatusCss($model->status).'">
                                '.$model->getStatusName($model->status).'
                            </span>',
                        'format' => 'raw'
                    ],
                    'created_at:date',
                    'updated_at:date',
                ],
            ]); ?>
            <p>绑定三方平台账号,实现快捷登录&nbsp;<i class="fa fa-rocket"></i></p>
            <p><?php
                if (!$userWxs) {
                    echo Html::a('<i class="fa fa-weixin">&nbsp;绑定微信账号</i>', $oAuthLib->wxCodeUrl($model->id));
                } else {
                    echo '<ul>';
                    foreach ($userWxs as $userWx) {
                        $info = \yii\helpers\Json::decode($userWx['info_data']);
                        $t = '<li class="bindItem">已绑定账号:';
                        if (isset($info['headimgurl']) && $info['headimgurl']) {
                            $t .= Html::img($info['headimgurl'], ['style'=>'width:20px;height:20px;']);
                        }
                        if (isset($info['nickname']) && $info['nickname']) {
                            $t .= $info['nickname'];
                        } else {
                            $t .= substr($userWx['openid'],0,8);
                        }
                        $t .= '&nbsp;&nbsp; '.Html::a('解除绑定',['/oauth/unbind','type'=>'wx','id'=>$userWx['id']],['class'=>'unbindBtn']);
                        echo $t . '</li>';
                    }
                    echo '</ul>';
                    echo Html::a('<i class="fa fa-weixin">&nbsp;绑定其他微信账号</i>', $oAuthLib->wxCodeUrl($model->id));
                }
                ?></p>
        </div>
    </div>
</div>

    <div style="width:100%;height:1px;display: block;clear: both;"></div>

<?php
$this->registerCss('
.section { text-align:left; }
.section .content { text-align: left; }
.section .content .box { background: none; }
.section .content .box .with-border { border-bottom: 1px solid #a90070; }
');
$this->registerJs('
	$("#fullpage").fullpage({
            autoScrolling: false,
            animateAnchor:false, //need
            scrollOverflow: true,
            scrollingSpeed: 1000, 
            
            paddingTop: "50px", 
            paddingBottom: "0",
            
            verticalCentered: true,
            resize: false, 
            responsive: 900
        });
        
    $(".unbindBtn").click(function(){
        if(confirm("确认解除绑定?")) {
            var t_obj = $(this);
            $.ajax({
                url: this.href,
                type: "POST",
                dataType: "json",
                success: function(d) {
                    if (d.code == 1) {
                        t_obj.parents(".bindItem").remove();
                    } else {
                        alert(d.msg);
                    }
                }
            });
        }
        return false;
    });    
');
?>