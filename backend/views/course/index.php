<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
use backend\assets\UploadAsset;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '课程管理';
$this->params['breadcrumbs'][] = $this->title;
UploadAsset::register($this);
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class='course-index'>

    <p class='img-margin-bottom'>
        <?= Html::a('新增课程', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量导入课程', 'javascript:;', ['class' => 'btn btn-success', 'id' => 'export']) ?>
    </p>

    <p class='img-margin-bottom' style='display:none' id='exportBlock'>
        <span class='btn btn-info fileinput-button'>
            <i class='glyphicon glyphicon-plus'></i>
            <span>上传并导入</span>
            <input type='file' name='excel' id='uploadExcel'>
        </span>
        <?= Html::a('下载导入模板', IMG_PRE . 'excel/template_course.xls', ['class' => 'btn btn-info']) ?>
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
                'contentOptions' => ['width' => '100px'],
            ],  
            'name',
            [
                'attribute' => 'tid',
                'contentOptions' => ['width' => '100px'],
                'value'     => 'teacher.name',
            ],
            [
                'attribute' => 'assistant',
                'contentOptions' => ['width' => '100px'],
                'value'     => 'ass.name',
            ],
            [
                'attribute' => 'cid',
                'contentOptions' => ['width' => '100px'],
                'value'     => 'class.name',
            ],
            [
                'attribute' => 'subject',
                'contentOptions' => ['width' => '100px'],
                'value'     => 'ownSubject.name',
            ],
            [
                'header'=> '操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{delete}',
                'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', ['onclick' => "deleteCourse({$model->id})",
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

        function deleteCourse(id)
        {
            layer.confirm('确认删除该课程吗?', function(index)
            {
                layer.close(index);

                $.post('./index.php?r=course/delete&id=' + id, {}, function(res) {
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
            data.formData = {type: 'excel', name: 'course'};  //如果需要额外添加参数可以在这里添加
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