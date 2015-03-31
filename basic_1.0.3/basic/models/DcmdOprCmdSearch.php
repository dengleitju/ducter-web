<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdOprCmd;

/**
 * DcmdOprCmdSearch represents the model behind the search form about `app\models\DcmdOprCmd`.
 */
class DcmdOprCmdSearch extends DcmdOprCmd
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opr_cmd_id', 'timeout', 'opr_uid'], 'integer'],
            [['opr_cmd', 'ui_name', 'run_user', 'script_md5', 'comment', 'utime', 'ctime'], 'safe'],
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
          $query = DcmdGroupCmd::find()->where($gstr)->asArray()->all();
          if($qstr == NULL) $qstr = " opr_cmd_id in (0";
          else $qstr .= " and opr_cmd_id in (0";
          foreach($query as $item) $qstr .=",".$item['opr_cmd_id'];
          $qstr .= ")";
        }

        if($qstr) $query = DcmdOprCmd::find()->andWhere($qstr)->orderBy('opr_cmd');
        else $query = DcmdOprCmd::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'opr_cmd_id' => $this->opr_cmd_id,
            'timeout' => $this->timeout,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'opr_cmd', $this->opr_cmd])
            ->andFilterWhere(['like', 'ui_name', $this->ui_name])
            ->andFilterWhere(['like', 'run_user', $this->run_user])
            ->andFilterWhere(['like', 'script_md5', $this->script_md5])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
