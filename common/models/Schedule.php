<?php

namespace common\models;

use Yii;
use common\models\Sundry;
use common\models\Course;
/**
 * This is the model class for table "schedule".
 *
 * @property string $id
 * @property integer $relate
 * @property string $date
 * @property string $info
 * @property integer $period
 * @property integer $num
 * @property integer $ifadjust
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relate', 'date', 'period', 'num'], 'required'],
            [['relate', 'num', 'ifadjust'], 'integer'],
            [['info'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'relate'   => '所属课程',
            'date'     => '上课日期',
            'info'     => '课时内容',
            'period'   => '时段',
            'num'      => '课节次',
            'ifadjust' => '是否调课',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            $this->date = strtotime($this->date);

            return true;
        }
        else
            return false;
    }

    public function getOwnPeriod()
    {
        return $this->hasOne(Sundry::className(), ['id' => 'period'])->select(['id', 'name']);
    }

    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'relate'])->select(['id', 'name']);
    }
}
