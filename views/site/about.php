<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'About');

?>
<div class="site-about">
    <div class="section" id="section0">
        <div class="content" style="margin-top:30px;">
            <h3><?php echo Html::encode($this->title) ?></h3>

            <p class="callout">基于 Yii2 开发的一款简易项目文档管理工具</p>

            <p class="callout">基于editor.md提供markdown富文本编辑器</p>

            <p class="callout">支持导出包含目录结构的PDF文档</p>

            <p class="callout">支持本站账号+微信多对多绑定</p>

            <p class="callout">本站仅用于学习分享及团队内部沟通协作!内容均源于互联网!</p>

            <p class="callout">如发现有泄漏隐私或侵权内容,请及时通过各种渠道联系站长进行处理,如"联系我们"</p>

            <div style="width:100%;height:20px;display: block;clear: both;"></div>
        </div>
    </div>

</div>

<div style="width:100%;height:1px;display: block;clear: both;"></div>

<?php
$this->registerCss('
.section { text-align:left; }
.section .content { text-align: left; }
');
