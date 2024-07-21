<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', Yii::$app->name);

?>
<div class="site-index">
    <div class="section" id="section0">
        <div class="content">
            <h3 class="lead text-bold">API项目文档在线管理及模拟测试工具</h3>
            <p class="lead">简单实用! 欢迎体验!</p>
            <p class="lead">Based on Yii2 Framework & Markdown </p>
            <p>
                <?php if(Yii::$app->getUser()->getIsGuest()) : ?>
                    <a class="btn btn-success" href="<?php echo \yii\helpers\Url::to(['/site/signup'])?>">注册并开始使用</a>
                <?php endif; ?>
                <a class="btn btn-primary" target="_blank" href="https://github.com/JeanWolf/yii-doc-center">View Source</a>
                <a class="btn btn-warning" href="<?php echo \yii\helpers\Url::to(['/rest/index'])?>">API在线测试</a>
            </p>
        </div>
    </div>
    <div class="section" id="section1">
        <div class="slide" id="slide1">
            <div class="content">
                <h3><span class="text-primary">多对多的微信关联绑定</span></h3>
                <p><br/></p>
                <p><span class="text-danger">使用微信快捷注册登录, 免去账号密码记忆成本. 还可以多对多的进行关联绑定.</span></p>
            </div>
        </div>
        <div class="slide" id="slide2">
            <div class="content">
                <h3><span class="text-success">自由的添加或移除文档项目</span></h3>
                <p><br/></p>
                <p><span class="text-warning">创建并管理自己的项目文档, 包含公开和私有模式. 实现项目成员的分级管理.</span></p>
            </div>
        </div>
        <div class="slide" id="slide3">
            <div class="content">
                <h3><span class="text-warning">清晰的目录层级结构</span></h3>
                <p><br/></p>
                <p><span class="text-primary">自定义文档目录结构, 三层目录使项目文档归类更清晰. 文档查阅更人性化.</span></p>
            </div>
        </div>
        <div class="slide" id="slide4">
            <div class="content">
                <h3><span class="text-primary">快捷的文档撰写方式</span></h3>
                <p><br/></p>
                <p><span class="text-danger">使用Markdown编辑器, 所见即所得的编辑预览模式. 让文档样式更简明更清晰更省心.</span></p>
            </div>
        </div>
        <div class="slide" id="slide5">
            <div class="content">
                <h3><span class="text-danger">方便的导出便携式文件</span></h3>
                <p><br/></p>
                <p><span class="text-success">将整个项目导出为Pdf格式便携文档, 随时随地离线查阅. 更有实用的书签目录.</span></p>
            </div>
        </div>
    </div>
    <div class="section" id="section2">
        <div class="content">
            <h3>免费测试体验</h3>
            <p></p>
            <div class="callout callout-success">
                <p class=""><span style="color:#fff;">测试账号信息:<br/> UserName: test /  PassWord: 123123</span></p>
            </div>
        </div>
    </div>
    <div class="section" id="section3">
        <div class="content">
            <h3>满足你的实际需求</h3>
            <p><br/></p>
            <p>
                没错, 这就是一款简单实用的项目文档管理工具! 添加项目|管理文档|设置成员权限 ... <br/>
                源于项目作者自身的实际需求, 没有多余的花哨功能!
            </p>
        </div>
    </div>
    <div class="section" id="section4">
        <div class="content">
            <h3>期待你的参与</h3>
            <p><br/></p>
            <p>
                是的, 它并不是一个私有的个人工具! <br/>
                API接口 在线测试功能... 开发测试中! <br/>
                文档/项目 搜索功能... 计划筹备中! <br/>
                如果您感兴趣,或者有任何问题或建议,都可以直接通过"联系我们"给我发送邮件.
            </p>
        </div>
    </div>
</div>
<?php
$this->registerCss('

    .section .content h3 { color:#236100; font-size:36px;font-family:SimSun, Serif; }
    .section .content p { color:#a90070; font-size:24px;font-family:SimHei, Serif; }

    #section2 .callout p {
		-webkit-transition: all 1.2s ease-in-out;
		-moz-transition: all 1.2s ease-in-out;
		-o-transition: all 1.2s ease-in-out;
		transition: all 1.2s ease-in-out;
		
		-webkit-transform: translate3d(-300px, 0px, 0px);
		-moz-transform: translate3d(-300px, 0px, 0px);
		-ms-transform:translate3d(-300px, 0px, 0px);
		transform: translate3d(-300px, 0px, 0px);
    }
    #section2 .callout p.active { 
		-webkit-transform: translate3d(0px, 0px, 0px);
		-moz-transform: translate3d(0px, 0px, 0px);
		-ms-transform:translate3d(0px, 0px, 0px);
		transform: translate3d(0px, 0px, 0px);
    }
');
$this->registerJs('
	$("#fullpage").fullpage({
            sectionsColor: ["#1bbc9b", "#fc3c4b", "#3ebe42", "#9aaba4", "#fab111"],
//            anchors: ["basic-info", "test-info", "intro-info", "project-info", "contact-info"],
            
            autoScrolling: true,
            animateAnchor:false, //need
            scrollOverflow: true,
            scrollingSpeed: 1000, 
            
            fitToSection: false, //need
            scrollBar: true,
            paddingTop: "50px", 
            paddingBottom: "0",
            
            continuousVertical:false, //section 循环
            
            verticalCentered: true,
            resize: false, 
            responsive: 900, 
					
            "navigation": true,
            "navigationPosition": "right",
            "navigationTooltips": ["Useful", "Powerful", "Amazing", "Simple", "OpenSource"],


            onLeave: function(index, nextIndex, direction){
                console.log("onLeave--" + "index: " + index + " nextIndex: " + nextIndex + " direction: " +  direction);
                
                if (nextIndex == 5) {
                    $.fn.fullpage.setAutoScrolling(false);
                }
               
            },
            
            afterLoad: function(anchorLink, index){
                console.log("afterLoad--" + "anchorLink: " + anchorLink + " index: " + index ); 
                if (index > 4) {
                    $.fn.fullpage.setAutoScrolling(false);
                } else {
                    if (index == 3) {
                        $("#section2 .callout p").addClass("active");
                    }
                    $.fn.fullpage.setAutoScrolling(true);
                }
                
            },
            afterSlideLoad: function(anchorLink, index, slideAnchor, slideIndex){
                console.log("afterSlideLoad--" + "anchorLink: " + anchorLink + " index: " + index + " slideAnchor: " + slideAnchor + " slideIndex: " + slideIndex);
            },
            onSlideLeave: function(anchorLink, index, slideIndex, direction){
                console.log("onSlideLeave--" + "anchorLink: " + anchorLink + " index: " + index + " slideIndex: " + slideIndex + " direction: " + direction);
            },
            afterRender: function(){
                console.log("afterRender");
            },
            afterResize: function(){
                console.log("afterResize");
            }
        });
');
?>

