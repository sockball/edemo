<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;

class StudentController extends ActiveController
{
	public $modelClass = 'common\models\Student';

	public function actionTest()
	{
		return ['limit 接触'];
	}
}