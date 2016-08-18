<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', Yii::$app->name);

if (\Yii::$app->getUser()->getIsGuest()) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blog'), 'url' => 'http://blog.jeen.wang','target'=>'_blank'];
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project List'), 'url' => ['/project/index']];
} 

$this->params['left-menu'] = [];

?>
<div class="site-index">

    <div class="jumbotron">
        <h2>欢迎使用!</h2>
        <p class="lead">简易的项目文档管理工具</p>
        <p class="lead">基于Yii2 Basic 加强版 + AdminLTE + MarkDown(Editor.MD)开发</p>
        <p>
        <?php if(Yii::$app->getUser()->getIsGuest()) : ?>
        <a class="btn btn-success" href="<?php echo \yii\helpers\Url::to(['/site/signup'])?>">注册并开始使用</a>
        <?php endif; ?>
        <a class="btn btn-primary" target="_blank" href="https://github.com/JeanWolf/yii-doc-center">Fork on GitHub</a>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-3">
                <h3>Freetuts.org</h3>

                <p></p>

                <p><a class="btn btn-default" href="http://www.freetuts.org/">Freetuts.org &raquo;</a></p>
            </div>
            <div class="col-lg-3">
                <h3>AdminLTE</h3>

                <p></p>

                <p><a class="btn btn-default" href="https://github.com/dmstr/yii2-adminlte-asset">Yii2 AdminLTE &raquo;</a></p>
            </div>
            <div class="col-lg-3">
                <h3>Editor.MD</h3>

                <p></p>

                <p><a class="btn btn-default" href="https://pandao.github.io/editor.md/examples/index.html">Editor.MD &raquo;</a></p>
            </div>
            <div class="col-lg-3">
                <h3>Yii docs</h3>

                <p></p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
        </div>

    </div>
</div>

