<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
use backend\assets\UploadAsset;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '教师管理';
$this->params['breadcrumbs'][] = $this->title;

UploadAsset::register($this);
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class="teacher-index">

    <p class='img-margin-bottom'>
        <?= Html::a('新增教师', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量导入教师', 'javascript:;', ['class' => 'btn btn-success', 'id' => 'export']) ?>
    </p>

    <p class='img-margin-bottom' style='display:none' id='exportBlock'>
        <span class='btn btn-info fileinput-button'>
            <i class='glyphicon glyphicon-plus'></i>
            <span>上传并导入</span>
            <input type='file' name='excel' id='uploadExcel'>
        </span>
        <?= Html::a('下载导入模板', IMG_PRE . 'excel/template_teacher.xls', ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'options' => ['id' => 'grid'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => ['class' => 'text-center'],
        'emptyText' => '没有教师数据',
        'emptyTextOptions' => ['style'=>'color:red;font-weight:bold'],
        'layout'    => '{items}{pager}',//{summary}
        // 'showOnEmpty'=> false, //默认开启表格
        'showFooter' => true,//默认关闭底部
        'pager'=>[
            'options'        => ['class'=>'pagination pull-right img-margin-right'],
            'firstPageLabel' => '首页',
            'prevPageLabel'  => '上一页',
            'nextPageLabel'  => '下一页',
            'lastPageLabel'  => '末页',
         ],
        'columns' => [
            [
                'class'         => 'yii\grid\CheckboxColumn',
                'name'          => 'id',
                'footerOptions' => ['colspan' => 9],
                'footer' => HTML::a('批量删除', 'javascript:;', ['class' => 'btn btn-warning deleteAll']),
            ],
            [
                'attribute' => 'id',
                'contentOptions' => ['width' => '50px'],
                'footerOptions'=>['class' => 'hide'],
            ],
            [
                'attribute' => 'name',
                'label'     => '姓名',
                'contentOptions' => ['width' => '60px'],
                'format' => 'raw',
                'footerOptions'=>['class'=>'hide'],//隐藏底部的当前列
                'value'     => function($model){
                    return  Html::img($model->avatar, ['class' => 'logo-normal']) . '<br>' . $model->name; 
                },
            ],
            [
                'attribute' => 'sex',
                'contentOptions' => ['width' => '80px'],
                'filter' =>['0' => '男', '1' => '女'],
                'footerOptions' => ['class'=>'hide'],
                'value'     => function($model) {
                    return $model->sex ? '女' : '男';
                }, 
            ],
            [
                'attribute'      => 'mobile',
                'label'          => '手机号',
                'contentOptions' => ['width' => '100px'],
                'footerOptions'  => ['class'=>'hide'],
            ],
            [
                'attribute'      => 'age',
                'label'          => '年龄',
                'contentOptions' => ['width' => '80px'],
                'footerOptions'  => ['class'=>'hide'],
            ],
            [
                'attribute'      => 'bindcode',
                'contentOptions' => ['width' => '80px'],
                'footerOptions'  => ['class'=>'hide'],
            ],
            [
                'attribute'     =>'main',
                'footerOptions' => ['class'=>'hide'],
            ],
            [
                'header'        => '操作',
                'template'      => '{update}&nbsp;{delete}',
                'class'         => 'yii\grid\ActionColumn',
                'footerOptions' => ['class'=>'hide'],
                'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', ['onclick' => "deleteTeacher({$model->id})",
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

        function deleteTeacher(id)
        {
            layer.confirm('确认删除该教师吗?', function(index)
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

        $('#export').on('click', function(){
            $('#exportBlock').slideToggle();
        });

        $('#uploadExcel').bind('fileuploadsubmit', function (e, data) {
            data.formData = {type: 'excel', name: 'teacher'};  //如果需要额外添加参数可以在这里添加
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

        $('.deleteAll').on('click', function(){
            layer.confirm('确认删除这些吗?', function(index)
            {
                layer.close(index);

                let deleteIds = $('#grid').yiiGridView('getSelectedRows');
                if(deleteIds.length < 1)
                {
                    layer.alert('请至少选择一项要删除的项！', {icon: 2});
                    return ;
                }

                $.post('./index.php?r=teacher/deleteall', {ids: deleteIds}, function(res) {
                    layer.msg(res, {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false});
                });
            });
        });
    </script>
<?php JsBlock::end();   ?>