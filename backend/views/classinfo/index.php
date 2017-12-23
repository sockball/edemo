<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ClassinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '班级管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class="classinfo-index">

    <p class='img-margin-bottom'>
        <?= Html::a('新增班级', ['create'], ['class' => 'btn btn-success']) ?>
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
            ],            [
                'attribute' => 'parent',
                'value'     => 'grade.name',
            ],
            'name',
            [
                'attribute' => 'tid',
                'value'     => 'teacher.name',
            ],
            [
                'attribute' => 'isvip',
                'value'     => function($model){
                    return ($model->isvip == 0) ? '否' : '是';
                }
            ],
            [
                'header'=> '操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{delete}',
                'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', ['onclick' => "deleteClass({$model->id})",
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

        function deleteClass(id)
        {
            layer.confirm('确认删除该班级吗?', function(index)
            {
                layer.close(index);

                $.post('./index.php?r=classinfo/delete&id=' + id, {}, function(res) {
                    layer.msg(res, {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false}, function(){
                        location.reload();
                    });
                });
            });
        }
    </script>
<?php JsBlock::end();   ?>
