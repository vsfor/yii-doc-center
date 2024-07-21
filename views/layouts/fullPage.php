<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAssetFullPage;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

dmstr\web\AdminLteAsset::register($this);

AppAssetFullPage::register($this);
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
<div id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::t('app', Yii::$app->name),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default',// navbar-fixed-top
            'data' => [
                'spy' => 'affix',
                'offset-top' => 60,
            ],
        ],
    ]);

    // everyone can see Home page
    $menuItems[] = ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']];

    // display Signup and Login pages to guests of the site
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'Articles'), 'url' => ['/article/index']];
        $menuItems[] = ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']];
        $menuItems[] = ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']];
        $menuItems[] = ['label' => Yii::t('app', 'Signup'), 'url' => ['/site/signup']];
        $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => '项目列表', 'url' => ['/project/index']];
        $menuItems[] = [
            'label' => '其他',
            'url' => ['/site/about'],
            'items' => [
                ['label' => Yii::t('app', 'Articles'), 'url' => ['/article/index']],
                ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
                ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']],
            ],
        ];

        if (Yii::$app->user->can('siteAdmin')) {
            $menuItems[] = [
                'label' => '管理',
                'url' => ['/user/index'],
                'items' => [
                    ['label' => '文章管理', 'url' => ['/article/admin']],
                    ['label' => '用户管理', 'url' => ['/user/index']],
                    ['label' => '角色管理', 'url' => ['/jrbac/role/index']],
                    ['label' => '资源管理', 'url' => ['/jrbac/permission/index']],
                    ['label' => '规则管理', 'url' => ['/jrbac/rule/index']],
                ],
            ];
        }

        $menuItems[] = [
            'label' => 'Hi ('.Yii::$app->user->identity->username.')',
            'url' => 'javascript:;',
            'items' => [
                ['label' => '查看个人信息', 'url' => '/user/profile'],
                ['label' => '编辑个人信息', 'url' => ['/user/update-profile']],
                [
                    'label' => Yii::t('app', 'Logout'),
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
            ],
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);

    NavBar::end();
    ?>
</div>
<div id="fullpage">
    <?php
    echo Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]);
    ?>
    <?php
    echo Alert::widget([
        'options' => [
            'class' => 'flashAlertMsg',
        ],
    ]);
    ?>
    <?= $content ?>
</div>

<div id="footer">
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy;
                <?= Yii::t('app', Yii::$app->name) ?>
                <?= date('Y') ?>
                .&nbsp; Jeen All Rights Reserved.
                <a href="https://beian.miit.gov.cn//" rel="nofollow" target="_blank">京ICP备15058100号-2</a>
            </p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
