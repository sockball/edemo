<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;
use backend\widgets\JsBlock;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '课程管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class='course-index'>

    <p class='img-margin-bottom'>
        <?= Html::a('新增课程', ['create'], ['class' => 'btn btn-success']) ?>
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
    </script>
<?php JsBlock::end();   ?>