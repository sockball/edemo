<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Classinfo */

$this->title = '修改班级';
$this->params['breadcrumbs'][] = ['label' => '班级管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classinfo-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
