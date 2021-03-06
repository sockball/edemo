<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = '新增管理员';
$this->params['breadcrumbs'][] = ['label' => '管理员设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-create">

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

	    <?= $form->field($model, 'password')->passwordInput() ?>

	    <?= $form->field($model, 'repeat_pwd')->passwordInput(['placeholder' => '请再次输入密码']) ?>

	    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

	    <div class='form-group'>
	        <label class='col-md-2'>
	        <?= Html::submitButton('新增管理员', ['class' => 'btn btn-success']) ?>
	        </label>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
