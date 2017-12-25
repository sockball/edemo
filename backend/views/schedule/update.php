<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Schedule */

$this->title = '修改课时';
$this->params['breadcrumbs'][] = ['label' => '课时管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
