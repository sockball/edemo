<?php

namespace backend\controllers;

use Yii;
use common\models\Sundry;
use common\models\SundrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SundryController implements the CRUD actions for Sundry model.
 */
class SundryController extends Controller
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

    /**
     * Lists all Sundry models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new SundrySearch();
        $get          = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($get);

        $type         = $this->getType($get);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'title'        => $type['title'],
            'type'         => $type['type'],
        ]);
    }

    /**
     * Creates a new Sundry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sundry();

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $type = $this->getType($post['Sundry']);
            Yii::$app->session->set('hint', "新增{$type['title']}成功");

            return $this->redirect(['index', 'type' => $type['type']]);
        } else {
            $type = $this->getType();

            return $this->render('create', [
                'model' => $model,
                'type'  => $type['type'],
                'title' => $type['title'],
            ]);
        }
    }

    /**
     * Updates an existing Sundry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post  = Yii::$app->request->post();

        if ($model->load($post) && $model->save()) {
            $type = $this->getType($post['Sundry']);
            Yii::$app->session->set('hint', "更新{$type['title']}成功");

            return $this->redirect(['index', 'type' => $type['type']]);
        } else {
            $type = $this->getType();

            return $this->render('update', [
                'model' => $model,
                'type'  => $type['type'],
                'title' => $type['title'],
            ]);
        }
    }

    /**
     * Deletes an existing Sundry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id, $title)
    {
        $this->findModel($id)->delete();

        return "删除{$title}成功";
    }

    /**
     * Finds the Sundry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Sundry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sundry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function getType($get = false)
    {
        if($get === false)
            $get = Yii::$app->request->get();

        $type  = isset($get['type']) ? $get['type'] : 'subject';

        switch ($type)
        {
            case 'subject':
                $title = '科目';
                break;
            
            case 'week':
                $title = '星期';
                break;
            
            case 'period':
                $title = '时段';
                break;

            default:
                $title = false;
                break;
        }

        return ['type' => $type, 'title' => $title];
    }
}
