<?php

namespace backend\controllers;

use Yii;
use backend\models\Admin;
use common\models\AdminSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends BaseController
{
    /**
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {
        // v(strtotime('tomorrow'));
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Admin();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->set('hint', '新增管理员成功');

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        // $model->save(); v($model->errors);

        $load = $model->load(Yii::$app->request->post());
        if (Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        }

        if ($load && $model->save()) {
            Yii::$app->session->set('hint', '修改管理员信息成功');

            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionReset($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'reset';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->set('hint', '重置密码成功');

            return $this->redirect(['index']);
        } else {
            return $this->render('reset', [
                'model' => $model,
            ]);
        }
    }

    public function actionPrivilege($id)
    {
        $model = $this->findModel($id);
        $auth  = Yii::$app->authManager;
        $post  = Yii::$app->request->post();

        if (!empty($post) && $model->addRole($post)) {
            Yii::$app->session->set('hint', '角色设置成功');

            return $this->redirect(['index']);
        } else {
            $roles    = array_keys($auth->getRolesByUser($id));
            $allRoles = array_flip(array_keys($auth->getRoles()));

            return $this->render('privilege', [
                'model'    => $model,
                'roles'    => $roles,
                'allRoles' => $allRoles,
            ]);
        }
    }

    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
