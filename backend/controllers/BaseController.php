<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\helpers\Url;
use yii\base\InvalidParamException; 

class BaseController extends Controller
{
	private $commonRoute = ['site/login', 'site/captcha', 'site/logout'];

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
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
        ];
    }

	public function beforeAction($action)
	{
		if(parent::beforeAction($action))
		{
/*			$controller = Yii::$app->controller->id;
			$route	 	= $controller . '/' . $action->id;
*/
			$route = $this->getRoute();//在没有模块的情况下可以如此得到形如 site/login
			if (in_array($route, $this->commonRoute))
				return true;

			$userid = Yii::$app->user->identity->id;

			if ($userid == 1)
				return true;

            // Yii::$app->authManager->checkAccess($userid, $route)
			if(!Yii::$app->user->can($route))
                throw new ForbiddenHttpException('您没有此操作的权限！');
                // return $this->error('您没有此操作的权限！');

			return true;
		}

		return false;
	}

    public function success($msg, $url, $wait = 5)
    {
        if(!is_array($url))
            throw new InvalidParamException('url参数错误');
            
        $url = Url::toRoute($url);

        return $this->renderPartial('//base/_error', [
                'wait'  => $wait,
                'url'   => $url,
                'msg'   => $msg,
                'title' => '页面跳转',
            ]);
    }

    public function error($msg, $url = '', $wait = 5)
    {
        $url = is_array($url) ? Url::toRoute($url) : '';

        return $this->renderPartial('//base/_error', [
                'wait'  => $wait,
                'url'   => $url,
                'msg'   => $msg,
                'title' => '出错啦咧!', 
            ]);
    }
}