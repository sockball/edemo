<?php

namespace backend\controllers;

use Yii;
use common\models\Teacher;
use common\models\TeacherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\School;
/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
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
     * Lists all Teacher models.
     * @return mixed
     */
    public function actionIndex($op = false)
    {
        $searchModel  = new TeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $hint         = $this->getHint($op);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'hint'      => $hint,
        ]);
    }

    /**
     * Displays a single Teacher model.
     * @param string $id
     * @return mixed
     */
/*    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Teacher();
        //初始化个别值
        $school = Yii::$app->session->get('school');
        $model->avatar = School::findOne($school)->teacher;
        $model->sex = 1;
        $model->ischairman = 0;
        $model->birthdate = $model->hiredate = time();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'op' => 'create']);    
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Teacher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'op' => 'update']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return '删除教师成功！';
    }

    /**
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Teacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    'imageUrlPrefix'  => IMG_PRE,//图片访问路径前缀
                    'imagePathFormat' => 'ueditor/image/teacher/{time}{rand:6}',
                    'imageRoot'       => Yii::getAlias('@common') . '\/uploads/',
                ],
            ]
        ];
    }

    public function getHint($op)
    {
        switch ($op)
        {
            case 'create':
                $hint = '新增教师成功';
                break;
    
            case 'update':
                $hint = '更新教师信息成功';
                break;

            default:
                $hint = false;
                break;
        }

        return $hint;
    }
}
