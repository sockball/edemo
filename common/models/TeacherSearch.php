<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Teacher;

/**
 * TeacherSearch represents the model behind the search form about `common\models\Teacher`.
 */
class TeacherSearch extends Teacher
{
    public function attributes()
    {
        return array_merge(parent::attributes(), ['age']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'sex', 'mobile', 'birthdate', 'hiredate', 'age', 'bindtime', 'ischairman'], 'integer'],
            [['name', 'avatar', 'main', 'bindcode', 'experience', 'result', 'special'], 'safe'],
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
        $query  = Teacher::find()->where(['school' => $school]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pagesize' => 10],
            'sort'      => [
                        'defaultOrder' => ['id' => SORT_DESC,],
                        'attributes'   => ['id', 'age'],
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
            'id' => $this->id,
            'sex' => $this->sex,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'main', $this->main])
            ->andFilterWhere(['like', 'bindcode', $this->bindcode]);

        if(!empty($this->age))
        {
            $birthStart = strtotime(date('Y') - $this->age . '/1/1');
            $birthEnd   = strtotime('+1 year', $birthStart);

            $query->andFilterWhere(['between', 'birthdate', $birthStart, $birthEnd]);
        }

        $dataProvider->sort->attributes['age'] = 
        [
            'asc'  => ['birthdate' => SORT_DESC],
            'desc' => ['birthdate' => SORT_ASC],
        ];

        return $dataProvider;
    }
}
