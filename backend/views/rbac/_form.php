<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\JsBlock;
?>

<div class="grade-form">

    <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal layui-form'],
            'fieldConfig' => [  
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
            ],
        ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(['1' => '角色','2' => '权限']); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class='form-group'>
    	<label class='col-md-2'>
       		<?= Html::submitButton($model->isNewRecord ? '新增项目' : '修改项目', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </label>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php JsBlock::begin() ?>
    <script>
        layui.use(['form'], function(){});

    </script>
<?php JsBlock::end() ?>