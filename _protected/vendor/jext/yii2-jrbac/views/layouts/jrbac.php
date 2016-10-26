<?php

/* @var $this \yii\web\View */
/* @var $content string */

use jext\jrbac\JrbacAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

JrbacAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Yii2 JRBAC',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => 'RBAC管理', 'url' => ['/jrbac/user/index'], 'items' => [
            ['label' => '资源管理', 'url' => ['/jrbac/permission/index']],
            ['label' => '角色管理', 'url' => ['/jrbac/role/index']],
            ['label' => '规则管理', 'url' => ['/jrbac/rule/index']],
            ['label' => '菜单管理', 'url' => ['/jrbac/menu/index']],
        ]];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?php echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php echo \yii\bootstrap\Alert::widget() ?>
        <?php echo $content ?>
    </div>
</div>

<?php
if (!function_exists('j_view_show_mask')) {
    echo $this->render('mask.php');
}
?>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <a href="http://ydc.jeen.wang">Jeen</a> All Rights Reserved. <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
