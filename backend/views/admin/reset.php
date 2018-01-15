<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = "重置管理员 {$model->username} 密码";
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

	    <?= $form->field($model, 'old_password')->passwordInput()->label('原始密码') ?>

	    <?= $form->field($model, 'password')->passwordInput()->label('新密码') ?>

	    <?= $form->field($model, 'repeat_pwd')->passwordInput(['placeholder' => '请再次输入新密码'])->label('新密码确认') ?>

	    <div class='form-group'>
	        <label class='col-md-2'>
	        <?= Html::submitButton('重置密码', ['class' => 'btn btn-primary']) ?>
	        </label>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
