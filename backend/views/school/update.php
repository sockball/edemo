<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\School */

$this->title = '学校基本信息';
$this->params['breadcrumbs'][] = '更新学校信息';
?>
<div class="school-update">
	<?php if($success): ?>
	<div class="alert alert-warning alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  
	  <h4>更新基本信息成功</h4>
	</div>			
	<?php endif;?>

    <!-- <h1> Html::encode('基本信息') </h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
