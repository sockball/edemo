<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '教师管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class='img-margin-bottom'>
        <?= Html::a('新增教师', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量导入教师', ['#'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => ['width' => '30px'],
            ],
            'uid',
            'name',
            'sex',
            'mobile',
            // 'avatar',
            // 'main',
            // 'birthdate',
            // 'bindcode',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
