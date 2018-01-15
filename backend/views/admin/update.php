<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = "修改管理员 {$model->username} 信息";
$this->params['breadcrumbs'][] = ['label' => '管理员设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-update">

	<div class='admin-form'>

	    <?php
	        $form = ActiveForm::begin([
	            'options' => ['class' => 'form-horizontal layui-form'],
	            'fieldConfig' => [  
	                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
	                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
	            ],
	        ]);
	    ?>

	    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

	    <div class='form-group'>
	        <label class='col-md-2'>
	        <?= Html::submitButton('修改信息', ['class' => 'btn btn-success']) ?>
	        </label>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
