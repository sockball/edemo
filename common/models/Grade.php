<?php

namespace common\models;

use Yii;
use common\models\Teacher;
/**
 * This is the model class for table "grade".
 *
 * @property string $id
 * @property integer $school
 * @property string $name
 * @property integer $manager
 */
class Grade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school', 'name', 'manager'], 'required'],
            [['school', 'manager'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '年级id',
            'school' => '学校',
            'name' => '年级名称',
            'manager' => '年级主任',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($insert)
                $this->school = Yii::$app->session->get('school');
            return true;
        }
        else
            return false;
    }

    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'manager']);
    }
}
