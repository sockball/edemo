<?php

use yii\helpers\Html;


$this->title = '新增项目';
$this->params['breadcrumbs'][] = ['label' => '角色权限管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
