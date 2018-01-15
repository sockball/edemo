<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '角色权限设置';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class='rbac-index'>

    <p class='img-margin-bottom'>
        <?= Html::a('新增项目', ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            'description:ntext',
            [
            	'attribute' => 'type',
                'filter' => ['1' => '角色', '2' => '权限'],
            	'value'	 => function($model) {
                    return $model->type == 1 ? '角色' : '权限';
                },
            ],
            [
            	'header' => '操作',
            	'class' => 'yii\grid\ActionColumn',
            	'template' => '{update}{relate}{delete}',
                'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->name], ['title' => '编辑信息', 'class' => 'btn btn-xs']);
                        },
                        'relate' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-plus"></span>', ['relate', 'id' => $model->name], [  'title' => '关联下级',
                                    'class' => 'btn btn-xs',
                                ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', ['onclick' => "deleteItem({$model->name})",
                                    'title' => '删除',
                                    'class' => 'btn btn-xs',
                                ]);
                        },
                ],
            ],
        ],
    ]); ?>
</div>
