<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Nav;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
/* @var $this yii\web\View */
/* @var $searchModel common\models\GradeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '年级管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class="grade-index">

    <p class='img-margin-bottom'>
        <?= Html::a('新增年级', ['create'], ['class' => 'btn btn-success ']) ?>
    </p>

    <?= GridView::widget([
        'options' => ['id' => 'grid'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => ['class' => 'text-center'],
        'emptyText' => '没有年级数据',
        'emptyTextOptions' => ['style'=>'color:red;font-weight:bold'],
        'layout'    => '{items}{pager}',//{summary}
        'pager' => [
            'options'          =>['class' => 'pagination pull-right img-margin-right'],
            'firstPageLabel'   =>'首页',
            'prevPageLabel'    =>'上一页',
            'nextPageLabel'    =>'下一页',
            'lastPageLabel'    =>'末页',
         ],
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'manager',
                'label'     => '年级主任',
                'value'     => 'teacher.name',
            ],
            [
                'header'=> '操作',
                'template' => '{update}&nbsp;{delete}',
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', ['onclick' => "deleteGrade({$model->id})",
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

        function deleteGrade(id)
        {
            layer.confirm('确认删除该年级吗?', function(index)
            {
                layer.close(index);

                $.post('./index.php?r=grade/delete&id=' + id, {}, function(res) {
                    layer.msg(res, {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false}, function(){
                        location.reload();
                    });
                });
            });
        }
    </script>
<?php JsBlock::end();   ?>