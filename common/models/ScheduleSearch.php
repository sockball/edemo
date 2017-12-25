<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Schedule;

/**
 * ScheduleSearch represents the model behind the search form about `common\models\Schedule`.
 */
class ScheduleSearch extends Schedule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'period', 'num', 'ifadjust'], 'integer'],
            [['info', 'relate', 'date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $school = Yii::$app->session->get('school');
        $query  = Schedule::find()->where(['sundry.school' => $school]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pagesize' => 10],
            'sort'       => [
                        'defaultOrder' => ['id' => SORT_DESC],
                        'attributes'   => ['id'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'       => $this->id,
            'period'   => $this->period,
            'num'      => $this->num,
            'ifadjust' => $this->ifadjust,
        ]);

        $dateArray = explode(' - ', $this->date);
        ($start = strtotime($dateArray[0])) ? '' : ($start = 0);
        $end    = isset($dateArray[1]) ? strtotime($dateArray[1]) : time();

        $query->andFilterWhere(['between', 'schedule.date', $start, $end]);

        $query->innerJoin('course', 'course.id = schedule.relate');
        $query->andFilterWhere(['like', 'course.name', $this->relate]);

        $query->innerJoin('sundry', 'sundry.id = course.subject');

        return $dataProvider;
    }
}
