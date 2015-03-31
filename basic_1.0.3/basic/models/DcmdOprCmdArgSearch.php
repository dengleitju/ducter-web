<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdOprCmdArg;

/**
 * DcmdOprCmdArgSearch represents the model behind the search form about `app\models\DcmdOprCmdArg`.
 */
class DcmdOprCmdArgSearch extends DcmdOprCmdArg
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'opr_cmd_id', 'optional', 'arg_type', 'opr_uid'], 'integer'],
            [['arg_name', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdOprCmdArg::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'opr_cmd_id' => $this->opr_cmd_id,
            'optional' => $this->optional,
            'arg_type' => $this->arg_type,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'arg_name', $this->arg_name]);

        return $dataProvider;
    }
}
