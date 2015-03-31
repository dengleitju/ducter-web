<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdNodeGroupAttrDef;

/**
 * DcmdNodeGroupAttrDefSearch represents the model behind the search form about `app\models\DcmdNodeGroupAttrDef`.
 */
class DcmdNodeGroupAttrDefSearch extends DcmdNodeGroupAttrDef
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attr_id', 'optional', 'attr_type', 'opr_uid'], 'integer'],
            [['attr_name', 'def_value', 'comment', 'ctime'], 'safe'],
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
        $query = DcmdNodeGroupAttrDef::find()->orderBy('attr_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'attr_id' => $this->attr_id,
            'optional' => $this->optional,
            'attr_type' => $this->attr_type,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'attr_name', $this->attr_name])
            ->andFilterWhere(['like', 'def_value', $this->def_value])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
