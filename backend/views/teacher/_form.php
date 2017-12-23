<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\FormWidget;
use backend\assets\UploadAsset;
use backend\widgets\JsBlock;
/* @var $this yii\web\View */
/* @var $model common\models\Teacher */
/* @var $form yii\widgets\ActiveForm */
UploadAsset::register($this);
?>

<div class="teacher-form">

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

    <?php
/*        $form->field($model, 'sex')->radioList(['0' => '男','1' => '女'], 
        [
            //label即 男女,一定要写在title里。。(layui要用)  value即0,1
        'item' => function($index, $label, $name, $checked, $value) 
             {
              $checked = $checked ? 'checked' : '';
              $return = "<input type='radio' id='teacher-{$name}' name='{$name}' title='{$label}' value='{$value}' {$checked}>";
              $return .= "<div class='layui-unselect layui-form-radio'><i class='layui-anim layui-icon'></i><div></div></div>";
              return $return;
             }
        ]);*/
    ?>

    <?= 
        $form->field($model, 'sex')->widget(FormWidget::className(), 
            [
                'method'   => 'radio',
                'params' => ['controller' => 'teacher', 'radios' => ['0' => '男','1' => '女']],
            ]);
    ?>

    <?= $form->field($model, 'mobile')->textInput() ?>

    <?= 
        $form->field($model, 'avatar', ['template' => '{label}{input}'])->widget(FormWidget::className(), 
            [
                'method'   => 'upload',
                'params' => ['controller' => 'teacher', 'hint' => '上传教师头像'],
            ]);
    ?>

    <?= $form->field($model, 'main')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthdate')->textInput(['readonly' => true, 'class' => 'layui-input']) ?>

    <?= $form->field($model, 'hiredate')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'layui-input']) ?>

    <?=
        $form->field($model, 'ischairman')->widget(FormWidget::className(), 
            [
                'method'   => 'radio',
                'params' => ['controller' => 'teacher', 'radios' => ['1' => '是','0' => '否']],
            ]);
    ?>

    <?= $form->field($model, 'experience')->widget('kucha\ueditor\UEditor',[]) ?>

    <?= $form->field($model, 'result')->widget('kucha\ueditor\UEditor',[]) ?>

    <?= $form->field($model, 'special')->widget('kucha\ueditor\UEditor',[]) ?>

    <div class="form-group">
        <lable class='col-md-2'>
            <?= Html::submitButton($model->isNewRecord ? '新增' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </lable>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php JsBlock::begin() ?>
    <script>
        layui.use(['layer', 'laydate', 'form'], function(){
            var layer   = layui.layer,
                laydate = layui.laydate;

            laydate.render({
                elem: '#teacher-birthdate',
                max: 0,
                value: new Date(<?= $model->birthdate * 1000 ?>),
            });

            laydate.render({
                elem: '#teacher-hiredate',
                max: 0,
                value: new Date(<?= $model->hiredate * 1000 ?>),
            });

            $('input[type=file]').bind('fileuploadsubmit', function (e, data) {
                data.formData = {type: 'img', column: $(this).attr('name')};
            });

            $('input[type=file]').fileupload({
                url: './index.php?r=upload/init',
                dataType: 'JSON',
            }).bind('fileuploadprogress', function (e, data) {
                //进度条
                layer.msg('上传中', {shade:0.3, time:0, scrollbar: false});
            }).bind('fileuploaddone', function (e, data) {
                //上传完成
                if(data.result.error > 0)
                    layer.alert(data.result.msg, {icon: 2, scrollbar: false});
                else
                {
                    $('#preview-' + data.result.column).attr('src', data.result.url);
                    $('#teacher-' + data.result.column).val(data.result.url);
                    layer.msg('上传成功', {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false});
                }
            });
        });

    </script>
<?php JsBlock::end() ?>