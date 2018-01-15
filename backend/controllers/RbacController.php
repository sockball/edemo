<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use backend\models\Rbac;
use backend\models\RbacSearch;
use yii\web\NotFoundHttpException;

class RbacController extends BaseController
{

    public function actionIndex()
    {
        $searchModel = new RbacSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
    	$model = new Rbac();

        if ($model->load(Yii::$app->request->post()) && $model->saveItem()) {
            Yii::$app->session->set('hint', '新增项目成功');

            return $this->redirect(['index']);
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->saveItem(false)) {
            Yii::$app->session->set('hint', '更新项目成功');

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionRelate($id)
    {
        $model 	  = $this->findModel($id);
        $auth  	  = Yii::$app->authManager;
		$children = array_keys($auth->getChildren($model->name));
		$type = 'premission';
		if($model->type == 1)
		{
			//包括角色
			$type = 'role';
			$allRoles 		= array_flip(Rbac::find()->select('name')->where("name != '{$model->name}'")->andWhere(['type' => 1])->orderBy('type')->column());
			$allPermissions = array_flip(Rbac::find()->select('name')->where("name != '{$model->name}'")->andWhere(['type' => 2])->orderBy('type')->column());
			$allItems 		= [
				'roles' 	  => $allRoles,
				'permissions' => $allPermissions,
			];
		}
		else
			$allItems = array_flip(Rbac::find()->select('name')->where("name != '{$model->name}'")->andWhere(['type' => 2])->orderBy('type')->column());

        return $this->render('relate', [
            'model'	   => $model,
            'children' => $children,
            'allItems' => $allItems,
            'type'	   => $type,
        ]);
    }

    public function actionUpdatechild()
    {
    	$post  = Yii::$app->request->post();
    	$res   = Rbac::UpdateChild($post);

    	return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Deletes an existing Rbac model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rbac model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Rbac the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rbac::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}