<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'tid') ?>

    <?= $form->field($model, 'assistant') ?>

    <?= $form->field($model, 'cid') ?>

    <?php // echo $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'starttime') ?>

    <?php // echo $form->field($model, 'endtime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
