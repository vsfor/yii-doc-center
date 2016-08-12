<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<header class="main-header">
    <?php
    echo Html::a('<span class="logo-mini">YDC</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']);
    ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="javascript:;" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?=
                \app\components\BreadcrumbsDiy::widget(
                    [
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]
                ) ?>
            <?php if(\Yii::$app->getUser()->getIsGuest()) : ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo \yii\helpers\Url::to('@web/static/images/jlogo.png'); ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs">
                            <?= Yii::t('app', 'Signup'); ?> /
                            <?= Yii::t('app', 'Login'); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    Yii::t('app', 'Signup'),
                                    ['/site/signup'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    Yii::t('app', 'Login'),
                                    ['/site/login'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            <?php else: ?> 
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo \yii\helpers\Url::to('@web/static/images/jlogo.png'); ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs">
                            <?php echo Yii::$app->getUser()->getIdentity()->username; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php echo \yii\helpers\Url::to('@web/static/images/jlogo.png'); ?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?php echo Yii::$app->getUser()->getIdentity()->email; ?><br/>
                                <small><?php echo Yii::t('app', 'Member since {dateString}',[
                                        'dateString' => date('Y-m-d',Yii::$app->getUser()->getIdentity()->created_at)
                                    ]);?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?php echo Html::a(
                                    Yii::t('app','Profile'),
                                    ['/user/profile'],
                                    ['class' => 'btn btn-default btn-flat']
                                ); ?>
                            </div>
                            <div class="pull-right">
                                <?php echo Html::a(
                                    Yii::t('app','Logout'),
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ); ?>
                            </div>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>
