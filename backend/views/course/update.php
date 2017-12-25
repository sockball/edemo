<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Course */

$this->title = '修改课程';
$this->params['breadcrumbs'][] = ['label' => '课程管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
