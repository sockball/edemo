<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Grade;

/**
 * GradeSearch represents the model behind the search form about `common\models\Grade`.
 */
class GradeSearch extends Grade
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'manager'], 'safe'],
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
        $query  = Grade::find()->where(['grade.school' => $school]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pagesize' => 10],
            'sort'       => [
                        'defaultOrder' => ['id' => SORT_DESC],
                        'attributes'   => ['id'],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'grade.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        $query->join('INNER JOIN', 'teacher', 'teacher.id = grade.manager');
        $query->andFilterWhere(['like', 'teacher.name', $this->manager]);

        return $dataProvider;
    }
}
