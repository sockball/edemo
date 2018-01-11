<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\JsBlock;
/* @var $this yii\web\View */
/* @var $model common\models\Exam */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exam-form">

    <?php 
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal layui-form'],
            'fieldConfig' => [  
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',  
                'labelOptions' => ['class' => 'col-md-2 control-label font-left'],  
            ],
        ]);
        $school = Yii::$app->session->get('school');
        $schedulesArray = (new yii\db\Query)
                            ->select(['course.name AS course', 'sundry.name AS period',
                                      'schedule.id', 'schedule.num','schedule.date',
                                    ])
                            ->from('schedule')
                            ->innerJoin('course', 'course.id = schedule.relate')
                            ->innerJoin('sundry', 'schedule.period = sundry.id')
                            ->where(['school' => $school])
                            ->all();
        $schedules = [];
        foreach ($schedulesArray as $k => $v)
        {
            $schedules[$v['id']] = date('Y-m-d', $v['date']) . " {$v['period']} {$v['course']}第{$v['num']}节";
        }
     ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'relate')->dropDownList($schedules, ['prompt' => '请选择课时'])->label('课时') ?>

    <?= $form->field($model, 'pages')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(['0' => '练习', '1' => '考试']) ?>

    <?= $form->field($model, 'passscore')->textInput() ?>

    <?= $form->field($model, 'totalscore')->textInput() ?>

    <div class='form-group'>
        <label class='col-md-2'>
        <?= Html::submitButton($model->isNewRecord ? '新增考试' : '修改考试', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </label>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php JsBlock::begin() ?>
    <script>
        layui.use(['form'], function(){

        });

    </script>
<?php JsBlock::end() ?>