<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper" style="position: relative;">
    <?php if (isset($this->blocks['content-header'])): ?>
    <section class="content-header">
            <?php echo $this->blocks['content-header'] ?>
    </section>
    <?php endif; ?>

    <section class="content">
        <?php echo Alert::widget() ?>
        <?php echo $content ?>
    </section>
    <div style="display: block;width: 100%;height: 1px;clear: both;"></div>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b><?= Yii::powered() ?></b>
    </div>
    <strong>&copy; <?= date('Y') ?> <?= Yii::t('app', Yii::$app->name) ?>.</strong>Jeen All rights
    reserved.
</footer>
<!-- up to top button -->
<div id="uptop">
    <div style="opacity:0;display:block;" class="level-2"></div>
    <div class="level-3"></div>
</div>
