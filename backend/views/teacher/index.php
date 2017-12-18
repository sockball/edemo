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
            [
                'attribute' => 'name',
                'label'     => '姓名',
                'contentOptions' => ['width' => '60px'],
            ],
            [
                'attribute' => 'sex',
                'contentOptions' => ['width' => '80px'],
                'filter' =>['0' => '男', '1' => '女'],
                'value'     => function($data) {
                    return $data->sex ? '女' : '男';
                }, 
            ],
            [
                'attribute' => 'mobile',
                'label'     => '手机号',
                'contentOptions' => ['width' => '100px'],
            ],
            [
                'attribute' => 'age',
                'label'     => '年龄',
                'contentOptions' => ['width' => '80px'],
                'value'     => function($data){
                    return date('Y') -  date('Y', $data->birthdate) . '岁';
                },
            ],
            [ 
                'attribute' => 'bindcode',
                'contentOptions' => ['width' => '80px'],
            ],
            // 'avatar',
            // 'main',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
