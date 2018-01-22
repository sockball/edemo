<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\FormWidget;
use backend\assets\UploadAsset;
use backend\widgets\JsBlock;
/* @var $this yii\web\View */
/* @var $model common\models\School */
/* @var $form yii\widgets\ActiveForm */
UploadAsset::register($this);
$template = ['template' => '{label}{input}'];
?>

<div class="school-form">

    <?php 
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],//'enctype' => 'multipart/form-data',
            'fieldConfig' => [  
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
            ],
        ]); 
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notice')->textInput(['maxlength' => true]) ?>

    <?= 
        $form->field($model, 'logo', $template)->widget(FormWidget::className(), 
            [
                'method'   => 'upload',
                'params' => ['controller' => 'school', 'hint' => '上传学校logo'],
            ]);
    ?>
    
    <?= 
        $form->field($model, 'receipt', $template)->widget(FormWidget::className(), 
            [
                'method'   => 'upload',
                'params' => ['controller' => 'school', 'hint' => '上传收据logo'],
            ]);
    ?>

    <?= 
        $form->field($model, 'teacher', $template)->widget(FormWidget::className(), 
            [
                'method'   => 'upload',
                'params' => ['controller' => 'school', 'hint' => '上传教师头像'],
            ]);
    ?>

    <?= 
        $form->field($model, 'student', $template)->widget(FormWidget::className(), 
            [
                'method'   => 'upload',
                'params' => ['controller' => 'school', 'hint' => '上传学生头像'],
            ]);
    ?>

    <?= $form->field($model, 'info')->widget('kucha\ueditor\UEditor',[
            'clientOptions' => [
                //'toolbars' => [['fontsize',]],
            ]
        ])
    ?>

    <div class='form-group'>
        <label class='col-md-2'>
            <?= Html::submitButton($model->isNewRecord ? 'Create' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>          
        </label>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php JsBlock::begin() ?>
    <script>
        layui.use(['layer'], function(){
            var layer = layui.layer;

            $('input[type=file]').each(function(i, v) {
                //上传前传递参数
                $(v).bind('fileuploadsubmit', function (e, data) {
                    data.formData = {type: 'img', column: $(this).attr('name')};
                });

                $(v).fileupload({
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
                        $('#school-' + data.result.column).val(data.result.url);
                        layer.msg('上传成功', {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false});
                    }
                });
            });
        });
    </script>
<?php JsBlock::end() ?>
