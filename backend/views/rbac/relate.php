<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\JsBlock;
use yii\helpers\Url;

$this->title = '关联下级';
$this->params['breadcrumbs'][] = ['label' => '角色权限管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('.layui-form-checkbox{margin-bottom:15px}');

?>
<div class="relate-create">

	<div class="relate-form">

	    <?php
	        $form = ActiveForm::begin([
	            'options' => ['class' => 'form-horizontal layui-form'],
	            'fieldConfig' => [  
	                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
	                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
	            ],
	        ]);
	    ?>
<div class="alert alert-success" role="alert">
	<p>
		请选择 
		<?= ($type == 'role') ? '角色' : '权限' ?> 
		<font color='#FF5722'><?= $model->name ?></font> 
		的下级
	</p>
</div>
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

			if($type == 'role')
			{
				// checkboxList参数3 key为checkbox value... value为checkbox label
				echo Html::checkboxList('children', $children, $allItems['roles'], $options);

				echo Html::checkboxList('children', $children, $allItems['permissions'], $options);
			} 
			else
				echo Html::checkboxList('children', $children, $allItems, $options);
		?>
		
	    <?php ActiveForm::end(); ?>

	</div>

</div>

<?php JsBlock::begin() ?>
    <script>

    	var flag = true; //用于监听一次ajax是否完成;
    	var url  = '<?= Url::to(['updatechild']) ?>';
    	var item = {
    		id 	 :  '<?= $model->name ?>',
    		type :  '<?= $model->type ?>',
    	}

        layui.use(['form', 'layer'], function(){
        	var form  = layui.form,
        		layer = layui.layer; 

        	form.on('checkbox', function(data){
        		let child   = data.elem;

        		if(flag)
        		{
        			flag = false;
        			let	checked = child.checked ? 1 : 0;  
	        		$.post(url, {name: child.value, checked: checked, id: item.id, type: item.type}, function(res) {
	        			if(res.error > 0)
	        			{
		        			child.checked = !(child.checked);
		        			form.render();
	        				layer.alert(res.msg, {icon: 2});
	        			}

	        			flag = true;
	        		}, 'json');
        		}
        		else
        		{
        			//还原到之前的点击....
        			child.checked = !(child.checked);
        			form.render();
        			layer.alert('您操作过快！', {icon: 2});
        		}
        	})
        });

    </script>
<?php JsBlock::end() ?>
