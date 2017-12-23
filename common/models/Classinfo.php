<?php

namespace common\models;

use Yii;
use common\models\Teacher;
use common\models\Grade;
/**
 * This is the model class for table "classinfo".
 *
 * @property string $id
 * @property string $name
 * @property integer $parent
 * @property integer $tid
 * @property integer $isvip
 */
class Classinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'classinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent', 'tid'], 'required'],
            [['parent', 'tid', 'isvip'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '班级ID',
            'name' => '班级名称',
            'parent' => '所属年级',
            'tid' => '班主任',
            'isvip' => '是否为VIP班',
        ];
    }

    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'tid']);
    }

    public function getGrade()
    {
        return $this->hasOne(Grade::className(), ['id' => 'parent']);
    }

}
