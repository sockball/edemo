<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Course;

/**
 * CourseSearch represents the model behind the search form about `common\models\Course`.
 */
class CourseSearch extends Course
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'info', 'tid', 'cid', 'assistant', 'subject'], 'safe'],
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
        $query  = Course::find()->where(['sundry.school' => $school]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pagesize' => 10],
            'sort'      => [
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
            'course.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        $query->join('INNER JOIN', 'teacher as t', 't.id = course.tid');
        $query->andFilterWhere(['like', 't.name', $this->tid]);

        $query->join('INNER JOIN', 'teacher as ass', 'ass.id = course.assistant');
        $query->andFilterWhere(['like', 'ass.name', $this->assistant]);

        $query->join('INNER JOIN', 'sundry', 'sundry.id = course.subject');
        $query->andFilterWhere(['like', 'sundry.name', $this->subject]);

        $query->join('INNER JOIN', 'classinfo', 'classinfo.id = course.cid');
        $query->andFilterWhere(['like', 'classinfo.name', $this->cid]);

        // v($query->createCommand()->getRawSql());

        return $dataProvider;
    }
}
