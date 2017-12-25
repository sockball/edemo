<?php

namespace common\models;

use Yii;
use common\models\Teacher;
use common\models\Classinfo;
use common\models\Sundry;
/**
 * This is the model class for table "course".
 *
 * @property string $id
 * @property string $name
 * @property integer $tid
 * @property integer $assistant
 * @property integer $cid
 * @property integer $subject
 * @property string $info
 * @property integer $price
 * @property string $starttime
 * @property string $endtime
 */
class Course extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tid', 'cid', 'subject', 'starttime', 'endtime'], 'required'],
            [['tid', 'assistant', 'cid', 'subject', 'price'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['info'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '课程ID',
            'name' => '课程名',
            'tid' => '授课教师',
            'assistant' => '助教',
            'cid' => '授课班级',
            'subject' => '科目',
            'info' => '课程大纲',
            'price' => '课程单价',
            'starttime' => '起止时间',
            'endtime' => '结束时间',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            $this->starttime = strtotime($this->starttime);
            $this->endtime   = strtotime($this->endtime);

            return true;
        }
        else
            return false;
    }

    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'tid'])->select(['id', 'name']);
    }

    public function getAss()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'assistant'])->select(['id', 'name']);
    }

    public function getClass()
    {
        return $this->hasOne(Classinfo::className(), ['id' => 'cid'])->select(['id', 'name']);;
    }

    public function getownSubject()
    {
        return $this->hasOne(Sundry::className(), ['id' => 'subject'])->select(['id', 'name']);;
    }
}

