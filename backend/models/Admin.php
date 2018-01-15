<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;
    public $repeat_pwd;
    public $old_password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required', 'on' => ['create', 'update']],
            ['password', 'required', 'on' => ['create', 'reset']],
            ['repeat_pwd', 'required', 'message' => '请确认密码', 'on' => ['create', 'reset']],
            ['old_password', 'required', 'on' => ['reset']],
            [['username', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            ['password', 'pwdRule'],
            ['old_password', 'validateOldPassword'],
            [['username'], 'unique', 'message' => '用户名已被占用'],
            [['email'], 'unique', 'message' => '邮箱已被占用'],
            ['repeat_pwd' , 'compare','compareAttribute' => 'password', 'message' => '两次密码输入不一致'],
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['username', 'email', 'password', 'repeat_pwd'],
            'update' => ['username', 'email'],
            'reset'  => ['password', 'repeat_pwd', 'old_password'],
        ];
    }

    public function pwdRule($attribute, $params)
    {
        if(strlen($this->$attribute) < 6)
        {
            $this->addError($attribute, '密码长度必须大于6个字符');
            return false;
        }

        return true;
    }

    public function validateOldPassword($attribute, $params)
    {
        $res = Yii::$app->security->validatePassword($this->$attribute, $this->oldAttributes['password_hash']);
        if($res)
            return true;
        else
        {
            $this->addError($attribute, '原始密码错误');
            return false;           
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'old_password' => '原始密码',
            'repeat_pwd' => '密码确认',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function addRole($post)
    {
        $roles  = empty($post['role']) ? [] : $post['role'];
        $auth   = Yii::$app->authManager;
        $userid = $this->id;
        $auth->revokeAll($userid);
        foreach ($roles as $key => $name)
        {
            $role = $auth->getRole($name);
            if($role !== NULL)
                $auth->assign($role, $userid);
        }

        return true;
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            //email username created_at updated_at  auth_key  password_hash
            if($insert)
            {
                $this->generateAuthKey();
                $this->created_at = $this->updated_at = time();
                $this->setPassword($this->password);
            }
            else
            {
                $action = Yii::$app->controller->action->id;
                if($action == 'update')
                {
                    //修改信息
                }
                else if ($action == 'reset')
                {
                    //重置密码
                    $this->setPassword($this->password);
                }
                else
                    return false;

                $this->updated_at = time();
            }

            return true;
        }
        else
            return false;
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
