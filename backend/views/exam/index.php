<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use backend\helpers\myHelpers;

$this->title = '考试管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= ($hint = myHelpers::getHint()) ? myHelpers::giveHint($hint) : '' ?>

<?= ''//$this->render('_search', ['model' => $searchModel]) ?>

<div class="exam-index">

    <p class='img-margin-bottom'>
        <?= Html::a('新增考试', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

        <?= ListView::widget([
                'id'=>'postList',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_listitem',
                'layout'=>'{items} {pager}',
                'pager'=>[
                        'options'        => ['class'=>'pagination pull-right img-margin-right'],
                        'maxButtonCount' => 10, //最多显示出几个可点击的数字页
                        'nextPageLabel'  => '下一页',
                        'prevPageLabel'  => '上一页',
                ],
        ])?>
</div>
