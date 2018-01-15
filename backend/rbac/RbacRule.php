<?php
namespace backend\rbac;

use Yii;
use yii\rbac\Rule;

/**
	若要使用rule则需重写execute方法 并对规则命名 $name
	
	基本使用：
        $auth = Yii::$app->authManager;
        添加： 
            $rule = new \backend\rbac\RbacRule;
            $auth->add($rule);
        关联权限    
            $rule = $auth->getRule('isAuthor');
            $permission = $auth->getPermisson('article/update');
            $permission->ruleName = $rule->name;
            $auth->update($permission->name, $permission);

*/
class RbacRule extends Rule
{
	public $name = 'isAuthor';
	/**
     * @param string|integer $user 用户 ID.
     * @param Item $item 该规则相关的角色或者权限
     * @param array $params 传给 ManagerInterface::checkAccess() 的参数
     * @return boolean 代表该规则相关的角色或者权限是否被允许
     */
    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? $params['post']->createdBy == $user : false;
    }
}