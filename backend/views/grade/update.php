<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Grade */

$this->title = '修改年级信息';
$this->params['breadcrumbs'][] = ['label' => '年级管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
?>
<div class="grade-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
