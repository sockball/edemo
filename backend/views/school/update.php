<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\School */
use backend\helpers\myHelpers;

$this->title = '学校基本信息';
$this->params['breadcrumbs'][] = '更新学校信息';
?>
<div class="school-update">
	
	<?= $hint ? myHelpers::giveHint('更新学校信息成功') : '' ?>
    <!-- <h1> Html::encode('基本信息') </h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
