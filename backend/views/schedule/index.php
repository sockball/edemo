<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
use common\models\Sundry;
use backend\assets\PickmeupAsset;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '课时管理';
$this->params['breadcrumbs'][] = $this->title;

$school = Yii::$app->session->get('school');
$periods = Sundry::find()->where(['school' => $school, 'type' => 'period'])->select(['name', 'id'])->indexBy('id')->column();

PickmeupAsset::register($this);
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class="schedule-index">

    <p class='img-margin-bottom'>
        <?= Html::a('新增课时', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'options'      => ['id' => 'grid'],
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'rowOptions'   => ['class' => 'text-center'],
        'layout'       => '{items}{pager}',//{summary}
        'pager' => [
            'options'          =>['class' => 'pagination pull-right img-margin-right'],
            'firstPageLabel'   =>'首页',
            'prevPageLabel'    =>'上一页',
            'nextPageLabel'    =>'下一页',
            'lastPageLabel'    =>'末页',
         ],
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => ['width' => '50px'],
            ],
            [
                'attribute' => 'relate',
                'label'     => '课程名',
                'value'     => 'course.name',
            ],
            [
                'attribute' => 'num',
                'contentOptions' => ['width' => '100px'],
            ],
            [
                'attribute' => 'date',
                'format'    => ['date','php:Y-m-d'],
            ],
            [
                'attribute' => 'period',
                'filter'    => $periods,
                'value'     => 'ownPeriod.name',
            ],
            [
                'attribute' => 'ifadjust',
                'filter' =>['0' => '否', '1' => '是'],
                'value'     => function($model)
                {
                    return $model->ifadjust == 0 ? '否' : '是'; 
                }
            ],
            [
                'header'        => '操作',
                'template'      => '{update}&nbsp;{delete}',
                'class'         => 'yii\grid\ActionColumn',
                'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', ['onclick' => "deleteSchedule({$model->id})",
                                    'title' => '删除',
                                    'class' => 'btn btn-xs',
                                ]);
                        },
                ],
            ],
        ],
    ]); ?>
</div>
<?php JsBlock::begin(); ?>
    <script>
        var layer;
        layui.use(['layer'], function(){
            layer = layui.layer;
        });

        function deleteSchedule(id)
        {
            layer.confirm('确认删除该课时吗?', function(index)
            {
                layer.close(index);

                // 写在函数内的参数只能在url中传递...
                $.post('./index.php?r=teacher/delete&id=' + id, {}, function(res) {
                    layer.msg(res, {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false}, function(){
                        location.reload();
                    });
                });
            });
        }

        var today   = (new Date()).toLocaleDateString().replace(/\//g, '-'),//今天日期 yyyy-mm-dd
            dateDom = $('input[name="ScheduleSearch[date]"]')[0];           //选择时间input框的js对象

        $(dateDom).prop({readonly: true, placeholder: '请选择时间区间'});

        pickmeup(dateDom, {
            default_date: false,
            position: 'bottom',
            locale: 'ch',
            format: 'Y-m-d',
            max: today,
            calendars : 2,
            mode: 'multiple',
        });

        dateDom.addEventListener('pickmeup-change', function (e) {
            let choosenTwo = e.detail.formatted_date,
                length = choosenTwo.length;
            if(length > 2)
            {
                let nowChoose = choosenTwo[2];
                choosenTwo.sort();
                let index = $.inArray(nowChoose, choosenTwo);
                if(index == 1)
                    choosenTwo.splice(0, 1);
                else
                    choosenTwo.splice(1, 1);

                pickmeup(dateDom).set_date(choosenTwo);
                $(dateDom).val(choosenTwo.join(' - '));
                pickmeup(dateDom).hide();
            }
            else if(length > 1)
            {
                choosenTwo.sort();
                $(dateDom).val(choosenTwo.join(' - '));
            }
            else if(length > 0)
            {
                $(dateDom).val(choosenTwo[0] + ' - ' + today);              
            }
        })

        dateDom.addEventListener('pickmeup-hide', function (e) {
            let choosenTwo = pickmeup(dateDom).get_date(true);
            if(choosenTwo.length > 0)
                $(dateDom).change();    //模拟触发事件..
        })

    </script>
<?php JsBlock::end();   ?>