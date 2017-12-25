<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\JsBlock;
use common\models\Teacher;
use common\models\Classinfo;
use common\models\Sundry;

/* @var $this yii\web\View */
/* @var $model common\models\Course */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-form">

    <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal layui-form'],
            'fieldConfig' => [  
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
            ],
        ]);
        $techers  = Teacher::find()->select(['name','id'])->indexBy('id')->column();
        $subjects = Sundry::find()->select(['name', 'id'])->indexBy('id')->where(['type' => 'subject'])->column();
        $school   = Yii::$app->session->get('school');
        $classes  =  Classinfo::find()->select(['classinfo.name', 'classinfo.id'])
                                      ->indexBy('id')
                                      ->where(['school' => $school])
                                      ->join('INNER JOIN', 'grade', 'grade.id = classinfo.parent')
                                      ->column();
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tid')->dropDownList($techers, ['prompt'=>'请选择授课教师']) ?>

    <?= $form->field($model, 'assistant')->dropDownList($techers, ['prompt'=>'请选择助教']) ?>

    <?= $form->field($model, 'cid')->dropDownList($classes, ['prompt'=>'请选择授课班级']) ?>

    <?= $form->field($model, 'subject')->dropDownList($subjects, ['prompt'=>'请选择科目']) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'starttime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'endtime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info')->widget('kucha\ueditor\UEditor',[]) ?>


    <div class='form-group'>
        <label class='col-md-2'>
        <?= Html::submitButton($model->isNewRecord ? '新增课程' : '修改课程', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </label>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php JsBlock::begin() ?>
    <script>
        layui.use(['laydate', 'form'], function(){
            var laydate = layui.laydate;

            laydate.render({
                elem: '#course-starttime',
                max: 0,
                value: new Date(<?= $model->starttime * 1000 ?>),
            });

            laydate.render({
                elem: '#course-endtime',
                max: 0,
                value: new Date(<?= $model->endtime * 1000 ?>),
            });

        });

    </script>
<?php JsBlock::end() ?>