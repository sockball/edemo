<?php
namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\AdminLogin;
use common\models\School;
/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
/*                    [
                        'actions' => ['special-callback'],
                        'allow' => true,
                        //只允许10月31日当天使用
                        'matchCallback' => function ($rule, $action) {
                            return date('d-m') === '31-10';
                        }
                    ],*/
                ],
/*                'denyCallback' => function ($rule, $action) {
                    throw new \Exception('You are not allowed to access this page');
                }*/
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                // 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null, //好像值变了就会每次刷新验证码
                'maxLength' => 5,
                'minLength' => 5,
                'backColor' => 0x666666,
                'padding'   => 5,
                'foreColor' => 0xffffff,     //字体颜色
                'offset'    => 10,        //设置字符偏移量
                // 'transparent' => true,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->session->set('school', 1);
        $id = YII::$app->session->get('school');
        $model = School::findOne($id);
        return $this->render('index', [
                'model' => $model,
            ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        $model = new AdminLogin();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $this->layout = 'login';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
