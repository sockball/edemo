<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Login form
 */
class Rbac extends ActiveRecord
{

    public static function tableName()
    {
        return 'auth_item';
    }

    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            ['name', 'unique'],
            ['name', 'validateName'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
        ];
    }

    public function validateName($attribute, $params)
    {
        if($this->type == 2)
        {
            if(!strpos($this->name, '/'))
            {
                $this->addError($attribute, '权限的项目名不合法');
                return false;
            }
        }

        return true;
    }

    public function attributeLabels()
    {
        return [
            'name' => '项目名称',
            'type' => '类型',
            'description' => '项目描述',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function saveItem($insert = true)
    {
        if($this->validate())
        {
            $auth = Yii::$app->authManager;

            if($insert)
            {
                $item   = self::getItem($auth, $this->name, $this->type);
                $item->description = $this->description;
                $auth->add($item);
            }
            else
            {
                //只改项目名字
                $item = self::getItem($auth, $this->name, $this->type);
                $item->description = $this->description;
                $auth->update($this->oldAttributes['name'], $item);

/*                if($this->type != $old['type'])
                {
                    //更改类型且已确认无child
                    $method  = 'get' . ($old['type'] == 1 ? 'Role' : 'Permission');
                    $oldItem = $auth->$method($old['name']);
                    $auth->remove($oldItem);
                    $item    = self::getItem($auth, $this->name, $this->type);
                    $item->description = $this->description;
                    $auth->add($item);
                }
                else
                {*/
            }

            return true;
        }
        else 
            return false;
    }

    public static function getItem($auth, $name, $type, $pre = 'create')
    {
        $method = $pre . ($type == 1 ? 'Role' : 'Permission');
        $item   = $auth->$method($name);

        return ($item === NULL) ? false : $item;
    }

    public static function UpdateChild($post)
    {
        $auth   = Yii::$app->authManager;
        $parent = self::getItem($auth, $post['id'], $post['type'], 'get'); 

        if ($parent === false)
            $res = ['error' => 1, 'msg' => '不存在的项目, 请刷新页面重试!'];
        else
        {
            $type  = strpos($post['name'], '/') ? 2 : 1;
            $child = self::getItem($auth, $post['name'], $type, 'get');

            if ($child === false)
                $res = ['error' => 1, 'msg' => '不存在的下级, 请刷新页面重试!'];
            else if ($auth->canAddChild($parent, $child))
            {
                $method = ($post['checked'] == 1 ? 'add' : 'remove') . 'Child'; 
                $auth->$method($parent, $child);
                $res = ['error' => 0];
            }
            else
                $res = ['error' => 1, '该下级已包含此项目!'];
        }

        return $res;
    }
}