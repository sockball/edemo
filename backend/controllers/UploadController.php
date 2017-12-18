<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\Upload;
/**
 * 
 */
class UploadController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionInit()
    {
        if (Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            $res  = Upload::uploadPic($post['column']);
        }
        else
            $res = ['error' => 1, 'msg' =>'非法请求'];

        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}
