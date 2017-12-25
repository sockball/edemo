<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\JsBlock;
use common\models\Sundry;
use common\models\Course;
/* @var $this yii\web\View */
/* @var $model common\models\Schedule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-form">

    <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal layui-form'],
            'fieldConfig' => [  
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
            ],
        ]);
        $school  = Yii::$app->session->get('school');
        $courses = Course::find()->select(['course.name', 'course.id'])->where(['type' => 'period'])
                                 ->indexBy('id')
                                 ->where(['school' => $school])
                                 ->leftJoin('sundry', 'sundry.id = course.subject')
                                 ->column();

        $periods = Sundry::find()->select(['name', 'id'])->where(['type' => 'period'])->indexBy('id')->column();
    ?>

    <?= $form->field($model, 'relate')->dropDownList($courses, ['prompt'=>'请选择课程']) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'period')->dropDownList($periods, ['prompt'=>'请选择时段']) ?>

    <?= $form->field($model, 'num')->textInput(['placeholder' => '请输入数字, 第几节课']) ?>

    <?= $form->field($model, 'info')->widget('kucha\ueditor\UEditor',[]) ?>

    <div class="form-group">
        <label class='col-md-2'>
        <?= Html::submitButton($model->isNewRecord ? '新增课时' : '修改课时', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </label>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php JsBlock::begin() ?>
    <script>
        layui.use(['laydate', 'form'], function(){
            var laydate = layui.laydate;

            laydate.render({
                elem: '#schedule-date',
                max: 0,
                value: new Date(<?= $model->date * 1000 ?>),
            });
        });

    </script>
<?php JsBlock::end() ?>