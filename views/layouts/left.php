<aside class="main-sidebar">
    <section class="sidebar">
        <?php if(Yii::$app->getUser()->getIsGuest()): ?>
            <??>
            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' =>  isset($this->params['left-menu']) && $this->params['left-menu'] ? $this->params['left-menu'] : [
                        ['label' => Yii::t('app','Menu'), 'options' => ['class' => 'header']],
                        ['label' => Yii::t('app','Home'), 'icon' => 'fa fa-home', 'url' => ['/site/index']],
                        ['label' => Yii::t('app','News'), 'icon' => 'fa fa-newspaper-o', 'url' => ['/article/index']],
                        ['label' => Yii::t('app','About'), 'icon' => 'fa fa-info', 'url' => ['/site/about']],
                        ['label' => Yii::t('app','Contact'), 'icon' => 'fa fa-bug', 'url' => ['/site/contact']],
                        ['label' => Yii::t('app','Login'), 'icon' => 'fa fa-user', 'url' => ['/site/login']],
                    ],
                ]
            ); ?>
        <?php else: ?>
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo \yii\helpers\Url::to('@web/static/images/jlogo.png'); ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>
                    <?php echo Yii::$app->getUser()->getIdentity()->username; ?>
                </p>

                <a href="javascript:;"><i class="fa fa-circle text-success"></i> <?php echo Yii::t('app','Online');?></a>
            </div>
        </div>
            <?php if (isset($_GET['project_id']) && $_GET['project_id']): ?>

        <!-- search form -->
        <form action="<?php echo \yii\helpers\Url::to(['/page/search']); ?>" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="hidden" name="project_id" value="<?= $_GET['project_id'];?>">
                <input type="text" name="doc_text" <?= isset($_GET['doc_text']) ? "value=\"{$_GET['doc_text']}\"":'';?> class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

            <?php endif; ?>
        <?php
            if (Yii::$app->getAuthManager()->isRoot()) {
                $extMenu = [
                    'label' => 'RBAC',
                    'url' => ['/jrbac/user/index'],
                    'icon' => 'fa fa-share',
                    'items' => [
                        ['label' => 'Permission', 'icon'=>'fa fa-circle-o', 'url' => ['/jrbac/permission/index']],
                        ['label' => 'Role', 'icon'=>'fa fa-circle-o', 'url' => ['/jrbac/role/index']],
                        ['label' => 'Rule', 'icon'=>'fa fa-circle-o', 'url' => ['/jrbac/rule/index']],
                    ]
                ];
            } else {
                $extMenu = [];
            }

            echo dmstr\widgets\Menu::widget(
            [
//                'options' => ['class' => 'sidebar-menu'],
                'items' => isset($this->params['left-menu']) && $this->params['left-menu'] ? $this->params['left-menu'] : [
                    ['label' => Yii::t('app','Menu'), 'options' => ['class' => 'header']],
                    ['label' => Yii::t('app','Home'), 'icon' => 'fa fa-home', 'url' => ['/site/index']],
                    ['label' => Yii::t('app','Project'), 'icon' => 'fa fa-book', 'url' => ['/project/index']],
                    ['label' => Yii::t('app','News'), 'icon' => 'fa fa-newspaper-o', 'url' => ['/article/index']],
                    ['label' => Yii::t('app','About'), 'icon' => 'fa fa-info', 'url' => ['/site/about']],
                    ['label' => Yii::t('app','Contact'), 'icon' => 'fa fa-bug', 'url' => ['/site/contact']],
                    $extMenu,
                ],
            ]
        ); ?>
        <?php endif; ?>
    </section>
</aside>
