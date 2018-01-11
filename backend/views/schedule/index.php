<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
use common\models\Sundry;
use backend\assets\UploadAsset;
use backend\assets\PickmeupAsset;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '课时管理';
$this->params['breadcrumbs'][] = $this->title;

$school = Yii::$app->session->get('school');
$periods = Sundry::find()->where(['school' => $school, 'type' => 'period'])->select(['name', 'id'])->indexBy('id')->column();

PickmeupAsset::register($this);
UploadAsset::register($this);
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class="schedule-index">

    <p class='img-margin-bottom'>
        <?= Html::a('新增课时', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量导入课表', 'javascript:;', ['class' => 'btn btn-success', 'id' => 'export']) ?>
    </p>

    <p class='img-margin-bottom' style='display:none' id='exportBlock'>
        <span class='btn btn-info fileinput-button'>
            <i class='glyphicon glyphicon-plus'></i>
            <span>上传并导入</span>
            <input type='file' name='excel' id='uploadExcel'>
        </span>
        <?= Html::a('下载导入模板', IMG_PRE . 'excel/template_schedule.xls', ['class' => 'btn btn-info']) ?>
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
                $.post('./index.php?r=schedule/delete&id=' + id, {}, function(res) {
                    layer.msg(res, {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false}, function(){
                        location.reload();
                    });
                });
            });
        }

        var today      = (new Date()).toLocaleDateString().replace(/\//g, '-'),//今天日期 yyyy-mm-dd
            dateDom    = $('input[name="ScheduleSearch[date]"]')[0],           //选择时间input框的js对象
            dateSearch = <?= $dateSearch ?>;

        $(dateDom).prop({readonly: true, placeholder: '请选择时间区间'});
        $(dateDom).addClass('text-center');

        pickmeup(dateDom, {
            default_date: false,
            position: 'bottom',
            locale: 'ch',
            max: today,
            calendars : 2,
            mode: 'multiple',
            format: 'Y-m-d',
        });
        //实在没法 初始化datepicker
        pickmeup(dateDom).set_date([]);

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
                pickmeup(dateDom).hide();
            }
/*            else if(length > 0)
            {
                choosenTwo.push(today);
                $(dateDom).val(choosenTwo.join(' - '));
            }*/
        })

        dateDom.addEventListener('pickmeup-hide', function (e) {
            let choosenTwo = pickmeup(dateDom).get_date(true);
            //dateSearch为0代表此时已经使用了时间区间作为条件 为1则没有使用, 应至少选用一天后才触发
            if(dateSearch == 0)
                $(dateDom).change();

            else if(choosenTwo.length > 0)
                $(dateDom).change();    //模拟触发事件..                

        })


        $('#export').on('click', function(){
            $('#exportBlock').slideToggle();
        });

        $('#uploadExcel').bind('fileuploadsubmit', function (e, data) {
            data.formData = {type: 'excel', name: 'schedule'};  //如果需要额外添加参数可以在这里添加
        });

        $('#uploadExcel').fileupload({
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
                layer.msg(data.result.msg, 
                    {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false},
                    function(){
                        location.reload();
                    }
                );
        });

    </script>
<?php JsBlock::end();   ?>