<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teacher".
 *
 * @property string $id
 * @property integer $uid
 * @property integer $school
 * @property string $name
 * @property integer $sex
 * @property integer $mobile
 * @property string $avatar
 * @property string $main
 * @property string $birthdate
 * @property string $hiredate
 * @property string $bindcode
 * @property string $bindtime
 * @property integer $ischairman
 * @property string $experience
 * @property string $result
 * @property string $special
 */
class Teacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sex', 'mobile', 'school', 'bindtime', 'ischairman'], 'integer'],
            [['name', 'mobile'], 'required'],
            [['experience', 'result', 'special'], 'string'],
            [['name', 'main'], 'string', 'max' => 50],
            [['bindcode'], 'string', 'max' => 8],
            [['birthdate', 'hiredate'], 'required', 'message' => '请选择日期'],
            // [['birthdate', 'hiredate'], 'date', 'format' => 'yyyy-MM-dd', 'message' => '请选择日期'],
            ['mobile', 'match', 'pattern' => '/1[345678]\d{9}/', 'message' => '请输入正确手机号'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '教师ID',
            'uid' => 'Uid',
            'name' => '教师姓名',
            'sex' => '性别',
            'mobile' => '教师手机号',
            'avatar' => '教师头像',
            'main' => '主要教授科目或内容',
            'birthdate' => '出生日期',
            'hiredate' => '入职时间',
            'bindcode' => '绑定码',
            'ischairman' => '是否为校长',
            'experience' => '教学经验',
            'result' => '教学成果',
            'special' => '教学特点',
        ];
    }
}
