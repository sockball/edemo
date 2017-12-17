<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Nav;
/* @var $this yii\web\View */
/* @var $searchModel common\models\GradeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '年级管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class='img-margin-bottom'>
        <?= Html::a('新增年级', ['create'], ['class' => 'btn btn-success ']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'manager',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
