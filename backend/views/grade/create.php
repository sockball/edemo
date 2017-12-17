<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Grade */

$this->title = '新增年级';
$this->params['breadcrumbs'][] = ['label' => '年级管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
