<?php

namespace common\models;

use Yii;
use backend\models\Upload;
use backend\helpers\myHelpers;
use common\models\Classinfo;
/**
 * This is the model class for table "student".
 *
 * @property string $id
 * @property string $name
 * @property integer $cid
 * @property string $code
 * @property integer $sex
 * @property string $birthdate
 * @property string $mobile
 * @property string $avatar
 * @property string $createtime
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cid', 'mobile'], 'required'],
            [['cid', 'code', 'sex', 'createtime'], 'integer'],
            [['name', 'avatar', 'birthdate'], 'string', 'max' => 100],
            ['mobile', 'match', 'pattern' => '/1[345678]\d{9}/', 'message' => '请输入正确手机号'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '学生ID',
            'name' => '学生姓名',
            'cid' => '班级',
            'code' => '学号',
            'sex' => '性别',
            'birthdate' => '出生日期',
            'mobile' => '家长手机号',
            'avatar' => '头像',
            'createtime' => 'Createtime',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($insert)
            {
                $this->avatar     = Upload::updateTemp($this->avatar, '', 'student');
                $this->createtime = time();
            }
            else
            {
                if($this->avatar != $this->oldAttributes['avatar'])
                    $this->avatar = Upload::updateTemp($this->avatar, $this->oldAttributes['avatar'], 'student');

                if($this->cid != $this->oldAttributes['cid'])
                    $this->code = $this->getCode($this->cid, $this->id);
            }

            $this->birthdate  = strtotime($this->birthdate);
            return true;
        }
        else
            return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert)
        {
            $code = $this->getCode($this->cid, $this->id);
            self::updateAll(['code' => $code], ['id' => $this->id]);
        }
    }

    protected function getCode($cid, $id)
    {
        $cid  = myHelpers::fillInNumber($cid, 4);
        $id   = myHelpers::fillInNumber($id, 4);
        $code = date('Y') . $cid . $id;

        return $code;
    }

    public function getClass()
    {
        return $this->hasOne(Classinfo::className(), ['id' => 'cid']);
    }

    public function getAge()
    {
        return date('Y') - date('Y', $this->birthdate);
    }
}
