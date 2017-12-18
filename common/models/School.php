<?php

namespace common\models;

use Yii;
use backend\models\Upload;
/**
 * This is the model class for table "school".
 *
 * @property string $id
 * @property string $name
 * @property integer $phone
 * @property string $address
 * @property string $notice
 * @property string $logo
 * @property string $receipt
 * @property string $teacher
 * @property string $student
 * @property string $info
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['phone'], 'integer'],
            [['info'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['address', 'notice', 'logo', 'receipt', 'teacher', 'student'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '学校名称',
            'phone' => '固定电话',
            'address' => '学校地址',
            'notice' => '公告',
            'logo' => '学校Logo',
            'receipt' => '收据logo',
            'teacher' => '教师默认头像',
            'student' => '学生默认头像',
            'info' => '学校简介',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if(!$insert)
            {
                $this->logo    = Upload::updateTemp($this->logo,    $this->oldAttributes['logo'],    'school');
                $this->receipt = Upload::updateTemp($this->receipt, $this->oldAttributes['receipt'], 'school');
                $this->teacher = Upload::updateTemp($this->teacher, $this->oldAttributes['teacher'], 'school');
                $this->student = Upload::updateTemp($this->student, $this->oldAttributes['student'], 'school');
            }
            return true;
        }
        else
            return false;
    }

}
