<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sundry".
 *
 * @property string $id
 * @property integer $school
 * @property string $name
 * @property string $type
 */
class Sundry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sundry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school'], 'integer'],
            [['name', 'type'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school' => 'School',
            'name' => 'Name',
            'type' => 'Type',
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

}
