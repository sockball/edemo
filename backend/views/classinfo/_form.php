<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\FormWidget;
use backend\widgets\JsBlock;
use common\models\Teacher;
use common\models\Grade;
/* @var $this yii\web\View */
/* @var $model common\models\Classinfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="classinfo-form">

    <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal layui-form'],
            'fieldConfig' => [  
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
            ],
        ]);
        $techers = Teacher::find()->select(['name','id'])->indexBy('id')->column();
        $grades  = Grade::find()->select(['name','id'])->indexBy('id')->column();
    ?>

    <?= $form->field($model, 'parent')->dropDownList($grades, ['prompt'=>'请选择所属年级', 'lay-filter' => 'grade']) ?>

	<?php 
		$grade = empty($model->parent) ? null : $grades[ $model->parent ];
		$add   = Html::input('text', null, $grade, [
			'readonly' => true, 'placeholder' => '年级名称', 'id' => 'gradeName',
			'class' => 'form-control pull-left', 'style' => 'width:15%',   
			]);

		$template = [
			'template' => '{label}<div class="col-md-8">' . $add . '{input}{hint}{error}</div>',
			'inputOptions' => ['class' => 'form-control', 'style' => 'width:85%'],
		];
	?>

    <?= $form->field($model, 'name', $template)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tid')->dropDownList($techers, ['prompt'=>'请选择班级主任']) ?>

    <?= 
        $form->field($model, 'isvip')->widget(FormWidget::className(), 
            [
                'method'   => 'radio',
                'params' => ['controller' => 'classinfo', 'radios' => ['1' => '是','0' => '否']],
            ]);
    ?>

    <div class="form-group">
    	<label class='col-md-2'>
        <?= Html::submitButton($model->isNewRecord ? '新增' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </label>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php JsBlock::begin() ?>
    <script>
        layui.use(['form'], function(){
        	let form = layui.form;
        	form.on('select(grade)', function(data){
        		let content = '';
        		if(data.value > 0)
        			content = $(data.elem).find('option:selected').html();

        		$('#gradeName').val(content);
			});
        });

    </script>
<?php JsBlock::end() ?>