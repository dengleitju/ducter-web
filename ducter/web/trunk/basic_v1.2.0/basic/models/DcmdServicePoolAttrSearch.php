<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdServicePoolAttr;

/**
 * DcmdServicePoolAttrSearch represents the model behind the search form about `app\models\DcmdServicePoolAttr`.
 */
class DcmdServicePoolAttrSearch extends DcmdServicePoolAttr
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'svr_id', 'svr_pool_id', 'opr_uid'], 'integer'],
            [['attr_name', 'attr_value', 'comment', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdServicePoolAttr::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'app_id' => $this->app_id,
            'svr_id' => $this->svr_id,
            'svr_pool_id' => $this->svr_pool_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'attr_name', $this->attr_name])
            ->andFilterWhere(['like', 'attr_value', $this->attr_value])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
