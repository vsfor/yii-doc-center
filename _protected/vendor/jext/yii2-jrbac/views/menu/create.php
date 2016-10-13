<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \jext\jrbac\models\JrbacMenu */

$this->title = '添加菜单';
$this->params['breadcrumbs'][] = ['label' => '菜单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h3><?php echo Html::encode($this->title) ?></h3>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
