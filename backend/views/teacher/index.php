<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '教师管理';
$this->params['breadcrumbs'][] = $this->title;
?>
    <?= $hint ? myHelpers::giveHint($hint) : '' ?>

<div class="teacher-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class='img-margin-bottom'>
        <?= Html::a('新增教师', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量导入教师', ['#'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => ['class' => 'text-center'],
        'emptyText' => '没有教师数据',
        'emptyTextOptions' => ['style'=>'color:red;font-weight:bold'],
        'layout'    => '{items}{pager}',//{summary}
        // 'showOnEmpty'=> false, //默认开启表格
        'showFooter' => true,//默认关闭底部
        'pager'=>[
            'options'=>['class'=>'pagination pull-right img-margin-right'],
            'firstPageLabel'=>'首页',
            'prevPageLabel'=>'上一页',
            'nextPageLabel'=>'下一页',
            'lastPageLabel'=>'末页',
         ],
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => ['width' => '30px'],
                'footerOptions'=>['colspan'=>8],
                'footer'=>'<a href="javascript:;" class="_delete_all" data-url="111">删除全部</a>',
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
                'attribute' => 'mobile',
                'label'     => '手机号',
                'contentOptions' => ['width' => '100px'],
                'footerOptions' => ['class'=>'hide'],
            ],
            [
                'attribute' => 'age',
                'label'     => '年龄',
                'contentOptions' => ['width' => '80px'],
                'footerOptions' => ['class'=>'hide'],
            ],
            [
                'attribute' => 'bindcode',
                'contentOptions' => ['width' => '80px'],
                'footerOptions' => ['class'=>'hide'],
            ],
            [
                'attribute' =>'main',
                'footerOptions' => ['class'=>'hide'],
            ],
            [
                'header'=> '操作',
                'template'  => '{update}&nbsp;{delete}',
                'class' => 'yii\grid\ActionColumn',
                'footerOptions' => ['class'=>'hide'],
                'buttons' =>[
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
    </script>
<?php JsBlock::end();   ?>