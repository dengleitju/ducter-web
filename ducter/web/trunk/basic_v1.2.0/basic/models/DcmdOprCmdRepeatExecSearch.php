<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdOprCmdRepeatExec;

/**
 * DcmdOprCmdRepeatExecSearch represents the model behind the search form about `app\models\DcmdOprCmdRepeatExec`.
 */
class DcmdOprCmdRepeatExecSearch extends DcmdOprCmdRepeatExec
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['repeat_cmd_id', 'timeout', 'repeat', 'cache_time', 'ip_mutable', 'arg_mutable', 'opr_uid'], 'integer'],
            [['repeat_cmd_name', 'opr_cmd', 'run_user', 'ip', 'arg', 'utime', 'ctime'], 'safe'],
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
    public function search($params, $qstr=NULL)
    {
        ///非系统用户只能查看授权的操作
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $gstr = " gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $gstr .= ",".$item['gid'];
          $gstr .= ")";
          $query = DcmdGroupRepeatCmd::find()->where($gstr)->asArray()->all();
          if($qstr == NULL) $qstr = " repeat_cmd_id in (0";
          else $qstr .= " and repeat_cmd_id in (0";
          foreach($query as $item) $qstr .=",".$item['repeat_cmd_id'];
          $qstr .= ")";
        }

        if($qstr) $query = DcmdOprCmdRepeatExec::find()->andWhere($qstr)->orderBy('repeat_cmd_name');
        else $query = DcmdOprCmdRepeatExec::find()->orderBy('repeat_cmd_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'repeat_cmd_id' => $this->repeat_cmd_id,
            'timeout' => $this->timeout,
            'repeat' => $this->repeat,
            'cache_time' => $this->cache_time,
            'ip_mutable' => $this->ip_mutable,
            'arg_mutable' => $this->arg_mutable,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'repeat_cmd_name', $this->repeat_cmd_name])
            ->andFilterWhere(['like', 'opr_cmd', $this->opr_cmd])
            ->andFilterWhere(['like', 'run_user', $this->run_user])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'arg', $this->arg]);

        return $dataProvider;
    }
}
