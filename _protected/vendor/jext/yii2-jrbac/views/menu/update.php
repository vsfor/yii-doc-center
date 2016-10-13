<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \jext\jrbac\models\JrbacMenu */

$this->title = '编辑菜单: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '菜单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="menu-update">

    <h3><?php echo Html::encode($this->title) ?></h3>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
