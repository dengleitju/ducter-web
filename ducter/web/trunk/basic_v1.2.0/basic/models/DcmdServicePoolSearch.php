<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdServicePool;

/**
 * DcmdServicePoolSearch represents the model behind the search form about `app\models\DcmdServicePool`.
 */
class DcmdServicePoolSearch extends DcmdServicePool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_pool_id', 'svr_id', 'app_id', 'opr_uid'], 'integer'],
            [['svr_pool', 'repo', 'env_ver', 'comment', 'utime', 'ctime'], 'safe'],
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
        ///应用组用户只可查看所在组的应用
        $svr_pool_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $query = DcmdApp::find()->where($app_con)->asArray()->all();
          $svr_pool_con = "app_id in (0";
          foreach($query as $item) $svr_pool_con .= ",".$item['app_id'];
          $svr_pool_con .=")";
        }
        $query = DcmdServicePool::find()->where($svr_pool_con)->orderBy('svr_pool');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'svr_pool_id' => $this->svr_pool_id,
            'svr_id' => $this->svr_id,
            'app_id' => $this->app_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'svr_pool', $this->svr_pool])
            ->andFilterWhere(['like', 'repo', $this->repo])
            ->andFilterWhere(['like', 'env_ver', $this->env_ver])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
