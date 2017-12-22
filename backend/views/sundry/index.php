<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\widgets\JsBlock;
use backend\helpers\myHelpers;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SundrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "{$title}管理";
$this->params['breadcrumbs'][] = $this->title;
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class="sundry-index">

    <p class='img-margin-bottom'>
        <?= Html::a('新增' . $title, ['create', 'type' => $type], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'options' => ['id' => 'grid'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => ['class' => 'text-center'],
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
            [
                'attribute' => 'name',
                'label'     => $title,
            ],
            [
                'header'=> '操作',
                'template' => '{update}&nbsp;{delete}',
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                        'update' => function($url, $model, $key)
                        {
                            $content = '<span class="glyphicon glyphicon-pencil"></span>';
                            //$type传不进来阿
                            $url = ['sundry/update', 'id' => $model->id, 'type' => $_GET['type']];
                            $options = ['title' => '更新', 'data-pjax' =>'0'];

                            return Html::a($content, $url, $options);
                        },
                        'delete' => function ($url, $model, $key)
                        {
                            $content = '<span class="glyphicon glyphicon-trash"></span>';
                            $url     = 'javascript:;';
                            $options = [
                                    'onclick' => "deleteSubject({$model->id})",
                                    'title' => '删除',
                                    'class' => 'btn btn-xs',
                                ];

                            return Html::a($content, $url, $options);
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

        function deleteSubject(id)
        {
            var title = '<?= $title ?>';
            layer.confirm('确认删除该' + title + '吗?', function(index)
            {
                layer.close(index);

                let url = './index.php?r=sundry/delete&id=' + id + '&title=' + title; 
                $.post(url, {}, function(res) {
                    layer.msg(res, {icon: 1, shadeClose: true, shade: 0.3, scrollbar: false}, function(){
                        location.reload();
                    });
                });
            });
        }
    </script>
<?php JsBlock::end();   ?>