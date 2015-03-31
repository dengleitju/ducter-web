<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdTaskTemplate;

/**
 * DcmdTaskTemplateSearch represents the model behind the search form about `app\models\DcmdTaskTemplate`.
 */
class DcmdTaskTemplateSearch extends DcmdTaskTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_tmpt_id', 'task_cmd_id', 'svr_id', 'app_id', 'update_env', 'concurrent_rate', 'timeout', 'process', 'auto', 'opr_uid'], 'integer'],
            [['task_tmpt_name', 'task_cmd', 'svr_name', 'task_arg', 'comment', 'utime', 'ctime'], 'safe'],
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
    public function search($params, $pagesize=20)
    {
        ///应用组用户只可查看所在组的应用的模板
        $task_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $query = DcmdApp::find()->where($app_con)->asArray()->all();
          $task_con = "app_id in (0";
          foreach($query as $item) $task_con .=",".$item['app_id'];
          $task_con .=")";
        }
        $query = DcmdTaskTemplate::find()->where($task_con)->orderBy('task_tmpt_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => $pagesize],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'task_tmpt_id' => $this->task_tmpt_id,
            'task_cmd_id' => $this->task_cmd_id,
            'svr_id' => $this->svr_id,
            'app_id' => $this->app_id,
            'update_env' => $this->update_env,
            'concurrent_rate' => $this->concurrent_rate,
            'timeout' => $this->timeout,
            'process' => $this->process,
            'auto' => $this->auto,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'task_tmpt_name', $this->task_tmpt_name])
            ->andFilterWhere(['like', 'task_cmd', $this->task_cmd])
            ->andFilterWhere(['like', 'svr_name', $this->svr_name])
            ->andFilterWhere(['like', 'task_arg', $this->task_arg])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
