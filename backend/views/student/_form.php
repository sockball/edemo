<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\FormWidget;
use backend\assets\UploadAsset;
use backend\widgets\JsBlock;
use common\models\Classinfo;

UploadAsset::register($this);
?>

<div class="student-form">

    <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal layui-form'],
            'fieldConfig' => [  
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
            ],
        ]);

        $school = Yii::$app->session->get('school');
        $classes = Classinfo::find()->select(['classinfo.name', 'classinfo.id'])->join('INNER JOIN', 'grade', 'classinfo.parent = grade.id')->where(['school' => $school])->indexBy('id')->column();
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= 
        $form->field($model, 'avatar', ['template' => '{label}{input}'])->widget(FormWidget::className(), 
            [
                'method'   => 'upload',
                'params' => ['controller' => 'student', 'hint' => '上传学生头像'],
            ]);
    ?>

    <?= $form->field($model, 'cid')->dropDownList($classes, ['prompt'=>'请选择班级']) ?>

    <?= 
        $form->field($model, 'sex')->widget(FormWidget::className(), 
            [
                'method'   => 'radio',
                'params' => ['controller' => 'student', 'radios' => ['0' => '男','1' => '女']],
            ]);
    ?>

    <?= $form->field($model, 'birthdate')->textInput(['readonly' => true, 'class' => 'layui-input']) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class='col-md-2'>
        <?= Html::submitButton($model->isNewRecord ? '新增' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </label>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php JsBlock::begin() ?>
    <script>
        layui.use(['layer', 'laydate', 'form'], function(){
            var layer   = layui.layer,
                laydate = layui.laydate;

            laydate.render({
                elem: '#student-birthdate',
                max: 0,
                value: new Date(<?= $model->birthdate * 1000 ?>),
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
                    $('#student-' + data.result.column).val(data.result.url);
                    layer.msg('上传成功', {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false});
                }
            });
        });

    </script>
<?php JsBlock::end() ?>