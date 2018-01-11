<?php

namespace common\models;

use Yii;
use yii\db\Query;
use backend\helpers\myHelpers;
/**
 * This is the model class for table "exam".
 *
 * @property string $id
 * @property string $name
 * @property integer $relate
 * @property integer $pages
 * @property integer $type
 * @property integer $passscore
 * @property integer $totalscore
 * @property integer $ifpublish
 * @property string $createtime
 * @property string $answer
 */
class Exam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exam';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'relate', 'passscore', 'totalscore', 'pages'], 'required'],
            [['relate', 'type', 'ifpublish', 'createtime', 'passscore', 'totalscore', 'pages'], 'integer'],
            [['answer'], 'string'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '考试名称',
            'relate' => '课时id',
            'pages' => '总页数',
            'type' => '考试类型',
            'passscore' => '及格分数',
            'totalscore' => '总分',
            'ifpublish' => '是否发布',
            'createtime' => '创建时间',
            'answer' => '答案',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($insert)
                $this->createtime = time();

            return true;
        }
        else
            return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert)
            $this->addStudentsScore($this->id, $this->relate);
    }

    protected function addStudentsScore($exam, $schedule)
    {
        $class    = (new Query)->from('schedule')
                               ->innerJoin('course',    'course.id = schedule.relate')
                               ->innerJoin('classinfo', 'classinfo.id = course.cid')
                               ->where(['schedule.id' => $schedule])
                               ->select(['classinfo.id'])
                               ->scalar();

        $students = (new Query)->from('student')
                               ->innerJoin('classinfo', 'classinfo.id = student.cid')
                               ->where(['classinfo.id' => $class])
                               ->select('student.id')
                               ->column();

        $columns = ['sid', 'relate', 'code'];
        $data    = [];               
        $codePre = date('Y') . myHelpers::fillInNumber($exam, 6);

        foreach ($students as $k => $student)
        {
            $code = $codePre . myHelpers::fillInNumber($student, 6);
            $data[$k] = [
                'sid'    => $student,
                'relate' => $exam,
                'code'   => $code,
            ];
        }

        Yii::$app->db->createCommand()->batchInsert('score', $columns, $data)->execute();

        return true;
    }
}
