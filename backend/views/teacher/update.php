<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */

$this->title = '修改教师' . $model->name . '信息';
$this->params['breadcrumbs'][] = ['label' => '教师管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
?>
<div class="teacher-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
