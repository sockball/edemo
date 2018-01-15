<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;
use backend\models\Upload;
/**
 * 
 */
class UploadController extends BaseController
{
    //column为input框的name值
    public function actionInit()
    {
        if (Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();

            switch ($post['type']) {
                case 'pdf':
                    break;

                case 'excel':
                    $res = Upload::uploadExcel($post['name']);
                    break;

                case 'img':
                    $res  = Upload::uploadPic($post['column']);
                    break;                 

                default:
                    $res = ['error' => 1, 'msg' =>'请求数据异常'];
                    break;
            }
        }
        else
            $res = ['error' => 1, 'msg' =>'非法请求'];

        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}
