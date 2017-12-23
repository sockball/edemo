<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Student;

/**
 * StudentSearch represents the model behind the search form about `common\models\Student`.
 */
class StudentSearch extends Student
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
            [['id', 'code', 'sex', 'birthdate', 'age', 'mobile'], 'integer'],
            [['name', 'avatar', 'cid'], 'safe'],
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
        $query  = Student::find()->where(['grade.school' => $school]);

        // add conditions that should always apply here

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
            'id' => $this->id,
            'sex' => $this->sex,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'mobile', $this->mobile])
              ->andFilterWhere(['like', 'code', $this->code]);

        $query->join('INNER JOIN', 'classinfo', 'classinfo.id = student.cid');
        $query->join('INNER JOIN', 'grade', 'grade.id = classinfo.parent');
        $query->andFilterWhere(['like', 'classinfo.name', $this->cid]);

        if(!empty($this->age))
        {
            $birthStart = strtotime(date('Y') - $this->age . '/1/1');
            $birthEnd   = strtotime('+1 year', $birthStart);

            $query->andFilterWhere(['between', 'birthdate', $birthStart, $birthEnd]);
        }

        return $dataProvider;
    }
}
