<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\JsBlock;


$this->title = "修改管理员角色";
$this->params['breadcrumbs'][] = ['label' => '管理员设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '$model->username'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('.layui-form-checkbox{margin-bottom:15px}');
?>
<div class='previlege-create'>

	<div class="alert alert-success" role="alert">
		<p>
			请选择 
			<font color='#FF5722'><?= $model->username ?></font> 
			的角色
		</p>
	</div>

	<div class='privilege-form'>
	    <?php
	        $form = ActiveForm::begin([
	            'options' => ['class' => 'form-horizontal layui-form'],
	            'fieldConfig' => [  
	                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
	                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
	            ],
	        ]);
	    ?>

		<?php
			$options = [
	            'tag'   => 'div',
	            'class' => 'relate-children img-margin-bottom',
            	'item'  => function ($index, $label, $name, $checked, $value)
	            {
	            	$check = $checked ? 'checked' : '';
	                $html  = "<input type='checkbox' value='{$value}' name='{$name}' title='{$value}' {$check}>";
					            	    
	                return $html;
	            }
		    ];

			echo Html::checkboxList('role', $roles, $allRoles, $options);
		?>

	    <div class='form-group'>
	        <label class='col-md-2'>
	        <?= Html::submitButton('保存选择', ['class' => 'btn btn-primary']) ?>
	        </label>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>

<?php JsBlock::begin() ?>
    <script>
    	layui.use(['form'], function(){});
    </script>
<?php JsBlock::end() ?>