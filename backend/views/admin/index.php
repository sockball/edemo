<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\helpers\myHelpers;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员设置';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<div class='admin-index'>

    <p class='img-margin-bottom'>
        <?= Html::a('新增管理员', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'    => '{items}{pager}',
        'columns' => [
            'id',
            'username',
            // 'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',
            [
                'header' => '操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{reset}{privilege}{delete}',
                'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id], ['title' => '编辑信息', 'class' => 'btn btn-xs']);
                        },
                        'reset' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-repeat"></span>', ['reset', 'id' => $model->id], ['title' => '重置密码', 'class' => 'btn btn-xs']);
                        },
                        'privilege' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-user"></span>', ['privilege', 'id' => $model->id], [  'title' => '角色管理',
                                    'class' => 'btn btn-xs',
                                ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', ['onclick' => "deleteUser({$model->id})",
                                    'title' => '删除',
                                    'class' => 'btn btn-xs',
                                ]);
                        },
                ],
            ],
        ],
    ]); ?>
</div>
