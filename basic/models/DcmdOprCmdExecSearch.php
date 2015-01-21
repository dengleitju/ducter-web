<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdOprCmdExec;

/**
 * DcmdOprCmdExecSearch represents the model behind the search form about `app\models\DcmdOprCmdExec`.
 */
class DcmdOprCmdExecSearch extends DcmdOprCmdExec
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exec_id', 'opr_cmd_id', 'timeout', 'opr_uid'], 'integer'],
            [['opr_cmd', 'run_user', 'ip', 'arg', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdOprCmdExec::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'exec_id' => $this->exec_id,
            'opr_cmd_id' => $this->opr_cmd_id,
            'timeout' => $this->timeout,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'opr_cmd', $this->opr_cmd])
            ->andFilterWhere(['like', 'run_user', $this->run_user])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'arg', $this->arg]);

        return $dataProvider;
    }
}
