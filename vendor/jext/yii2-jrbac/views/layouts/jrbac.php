<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use jext\jrbac\JrbacAsset;

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
    if (Yii::$app->getUser()->getIsGuest()) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => 'RBAC', 'url' => ['/jrbac/user/index'], 'items' => [
            ['label' => 'Permission', 'url' => ['/jrbac/permission/index']],
            ['label' => 'Role', 'url' => ['/jrbac/role/index']],
            ['label' => 'Rule', 'url' => ['/jrbac/rule/index']],
            ['label' => 'Menu', 'url' => ['/jrbac/menu/index']],
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
        <?php //echo \app\widgets\Alert::widget() ?>
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
        <p class="pull-left">Jeen &copy; <?= date('Y') ?> All Rights Reserved.</p>

        <p class="pull-right"><?= \Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
