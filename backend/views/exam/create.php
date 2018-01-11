<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Exam */

$this->title = '新增考试';
$this->params['breadcrumbs'][] = ['label' => '考试管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exam-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
