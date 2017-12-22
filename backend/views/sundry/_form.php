<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Sundry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class='sundry-form'>

    <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],//'enctype' => 'multipart/form-data',
            'fieldConfig' => [  
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
            ],
        ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label("{$title}名") ?>

    <?= $form->field($model, 'type')->textInput()->hiddenInput(['value' => $type])->label(false) ?>

    <div class='form-group'>
        <label class='col-md-2'>
        <?= Html::submitButton($model->isNewRecord ? "新增$title" : "更新$title", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </label>
    </div>

    <?php ActiveForm::end(); ?>

</div>
