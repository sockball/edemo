<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "score".
 *
 * @property string $id
 * @property integer $sid
 * @property integer $relate
 * @property string $code
 * @property integer $score
 * @property string $answer
 * @property string $comment
 * @property integer $status
 * @property integer $ifpublish
 */
class Score extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'score';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'relate', 'code', 'score', 'status', 'ifpublish'], 'integer'],
            [['relate'], 'required'],
            [['answer'], 'string'],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'relate' => 'Relate',
            'code' => 'Code',
            'score' => 'Score',
            'answer' => 'Answer',
            'comment' => 'Comment',
            'status' => 'Status',
            'ifpublish' => 'Ifpublish',
        ];
    }
}
