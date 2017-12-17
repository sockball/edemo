<?php

namespace common\models;

use Yii;

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
    private $_oldPaths = null;
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

    public function afterFind()
    {
        parent::afterFind();
        $this->_oldPaths = [
            'logo'    => $this->getPath($this->logo), 
            'receipt' => $this->getPath($this->receipt),
            'teacher' => $this->getPath($this->teacher),
            'student' => $this->getPath($this->student),
        ];
    }

    private function getPath($path)
    {
        $array = explode('school', $path);
        if(count($array) > 1)
            return $array[1];
        else
            return 'ksjdsajdass.jpg';
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if(!$insert)
            {
                $this->logo    = $this->updateTemp($this->logo, 'logo');
                $this->receipt = $this->updateTemp($this->receipt, 'receipt');
                $this->teacher = $this->updateTemp($this->teacher, 'teacher');
                $this->student = $this->updateTemp($this->student, 'student');
            }
            return true;
        }
        else
            return false;
    }

    //将临时logo保存为正式并删除临时文件
    private function updateTemp($tmp, $column)
    {
        $array = explode('temp/', $tmp);
        if(count($array) < 2)
            return $tmp;
        else
        {
            $filename = $array[1];
            $root = '../../common/uploads/';
            $tmp  = $root . 'temp/' . $filename;
            $new  = $root . 'school/' . $filename;
            copy($tmp, $new);
            unlink($tmp);

            $oldFile = $root . 'school/' . $this->_oldPaths[$column];
            if(file_exists($oldFile))
                unlink($oldFile);

            return IMG_PATH . 'school/' . $filename;
        }
    }
}
