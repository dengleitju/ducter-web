<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdAppArchDiagram;

/**
 * DcmdAppArchDiagramSearch represents the model behind the search form about `app\models\DcmdAppArchDiagram`.
 */
class DcmdAppArchDiagramSearch extends DcmdAppArchDiagram
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'opr_uid'], 'integer'],
            [['arch_name', 'diagram', 'comment', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdAppArchDiagram::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'app_id' => $this->app_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'arch_name', $this->arch_name])
            ->andFilterWhere(['like', 'diagram', $this->diagram])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
