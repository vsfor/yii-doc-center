<?php
/** @var $this \yii\web\View */

$asset = jext\jrbac\JrbacAsset::register($this);

if (!function_exists('j_view_show_mask')) {
    function j_view_show_mask() { }
}

//遮罩层  防止重复点击等操作 - start
echo '<div id="ll-mask-layer" style="display: none;"><span>处理中，请稍候...<br><b>操作提示：切勿频繁刷新页面 ！</b><br>如有异常或长时间无反应,请联系技术人员</span></div>';
$this->registerCss('
        #ll-mask-layer { width:100%; height:100%; display:block; position:fixed;
            background:rgba(0, 0, 0, 0.5); z-index:11000; left:0; top:0;
         }
        #ll-mask-layer span { position:absolute; left:35%; top:35%; color:#fff;
            padding:60px 10px 10px 10px; text-align:center; border-radius:10px;
            background:#000 url("'.$asset->baseUrl.'/images/ajax-loader.gif") top center no-repeat;
         }
        #ll-mask-layer span b { color:#F404F1; }
    ');
$this->registerJs('
        function showMask() {
            $("#ll-mask-layer").show();
            setTimeout("$(\"#ll-mask-layer\").hide();console.log(\"mask auto hide after 10s\");",10000);
        }
        function hideMask() {
            $("#ll-mask-layer").hide();
        }',$this::POS_HEAD);
$this->registerJs('$(function(){
        $("a,button").click(function(){
            console.log("Probably, you may need to add a mask layer when click this link button :)");
            return true;
    //        $("#ll-mask-layer").show();
    //        setTimeout("$(\"#ll-mask-layer\").hide();console.log(\"mask auto hide after 3s\");",3000);
         });

        $(".jbtnmask").click(function(){
            showMask();
            $(this).hide();
        });
     });');
//遮罩层  防止重复点击等操作 - end


/* <?php echo $this->render('/mask.php'); ?> */
