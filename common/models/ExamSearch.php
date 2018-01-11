<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Exam;

/**
 * ExamSearch represents the model behind the search form about `common\models\Exam`.
 */
class ExamSearch extends Exam
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'relate', 'pages', 'type', 'passscore', 'totalscore', 'ifpublish', 'createtime'], 'integer'],
            [['name', 'answer'], 'safe'],
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
        $query = Exam::find();

        // add conditions that should always apply here

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
            'id' => $this->id,
            'relate' => $this->relate,
            'pages' => $this->pages,
            'type' => $this->type,
            'passscore' => $this->passscore,
            'totalscore' => $this->totalscore,
            'ifpublish' => $this->ifpublish,
            'createtime' => $this->createtime,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'answer', $this->answer]);

        return $dataProvider;
    }
}
