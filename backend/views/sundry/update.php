<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Sundry */

$this->title = "更新{$title}";
$this->params['breadcrumbs'][] = ['label' => "{$title}管理", 'url' => ['index', 'type' => $type]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sundry-update">

    <?= $this->render('_form', [
        'model' => $model,
        'type'	=> $type,
        'title' => $title,
    ]) ?>

</div>
