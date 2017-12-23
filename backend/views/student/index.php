<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
use backend\assets\UploadAsset;
/* @var $this yii\web\View */
/* @var $searchModel common\models\StudentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学生管理';
$this->params['breadcrumbs'][] = $this->title;

UploadAsset::register($this);
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class="student-index">

    <p class='img-margin-bottom'>
        <?= Html::a('新增学生', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量导入学生', 'javascript:;', ['class' => 'btn btn-success', 'id' => 'export']) ?>
    </p>

    <p class='img-margin-bottom' style='display:none' id='exportBlock'>
        <span class='btn btn-info fileinput-button'>
            <i class='glyphicon glyphicon-plus'></i>
            <span>上传并导入</span>
            <input type='file' name='excel' id='uploadExcel'>
        </span>
        <?= Html::a('下载导入模板', IMG_PRE . 'excel/template_student.xls', ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'options' => ['id' => 'grid'],
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'rowOptions'   => ['class' => 'text-center'],
        'layout'    => '{items}{pager}',//{summary}
        'pager'=>[
            'options'        => ['class'=>'pagination pull-right img-margin-right'],
            'firstPageLabel' => '首页',
            'prevPageLabel'  => '上一页',
            'nextPageLabel'  => '下一页',
            'lastPageLabel'  => '末页',
         ],
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => ['width' => '90px'],
            ],
            [
                'attribute' => 'name',
                'label'     => '姓名',
                'contentOptions' => ['width' => '150px'],
                'format' => 'raw',
                'value'     => function($model){
                    return  Html::img($model->avatar, ['class' => 'logo-normal']) . '<br>' . $model->name; 
                },
            ],
            [
                'attribute'      => 'age',
                'label'          => '年龄',
                'contentOptions' => ['width' => '70px'],
            ],
            [
                'attribute' => 'sex',
                'filter' =>['0' => '男', '1' => '女'],
                'value'     => function($model) {
                    return $model->sex ? '女' : '男';
                }, 
            ],
            [
                'attribute' => 'cid',
                'contentOptions' => ['width' => '150px'],
                'value'     => 'class.name',
            ],
            [
                'attribute' =>'code',
                'contentOptions' => ['width' => '100px'],
            ],
            'mobile',
            [
                'header'        => '操作',
                'template'      => '{update}&nbsp;{delete}',
                'class'         => 'yii\grid\ActionColumn',
                'footerOptions' => ['class'=>'hide'],
                'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', ['onclick' => "deleteStudent({$model->id})",
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

        function deleteStudent(id)
        {
            layer.confirm('确认删除该学生吗?', function(index)
            {
                layer.close(index);

                $.post('./index.php?r=student/delete&id=' + id, {}, function(res) {
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
            data.formData = {type: 'excel', name: 'student'};  //如果需要额外添加参数可以在这里添加
        });

        $('#uploadExcel').fileupload({
            url: './index.php?r=upload/init',
            dataType: 'JSON',
        }).bind('fileuploadprogress', function (e, data) {
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