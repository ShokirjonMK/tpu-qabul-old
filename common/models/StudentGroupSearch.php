<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StudentGroup;

/**
 * StudentGroupSearch represents the model behind the search form of `common\models\StudentGroup`.
 */
class StudentGroupSearch extends StudentGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'std_id', 'user_id', 'drift_id', 'drift_form_id', 'drift_course_id', 'edu_year_id', 'language_id', 'course_id', 'etype_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function search($params,$std)
    {
        $query = StudentGroup::find()->where(['std_id' => $std->id, 'is_deleted' => 0])->orderBy('edu_year_id asc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'std_id' => $this->std_id,
            'user_id' => $this->user_id,
            'drift_id' => $this->drift_id,
            'drift_form_id' => $this->drift_form_id,
            'drift_course_id' => $this->drift_course_id,
            'edu_year_id' => $this->edu_year_id,
            'language_id' => $this->language_id,
            'course_id' => $this->course_id,
            'etype_id' => $this->etype_id,
            'price' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => $this->is_deleted,
        ]);

        return $dataProvider;
    }
}
