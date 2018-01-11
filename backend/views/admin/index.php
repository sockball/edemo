<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员设置';
$this->params['breadcrumbs'][] = $this->title;
?>
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{delete}',
            ],
        ],
    ]); ?>
</div>
