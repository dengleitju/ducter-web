<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdServicePoolNode;

/**
 * DcmdServicePoolNodeSearch represents the model behind the search form about `app\models\DcmdServicePoolNode`.
 */
class DcmdServicePoolNodeSearch extends DcmdServicePoolNode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'svr_pool_id', 'svr_id', 'nid', 'app_id', 'opr_uid'], 'integer'],
            [['ip', 'utime', 'ctime'], 'safe'],
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
    public function search($params, $index=true)
    {
        ///应用组用户只可查看所在组的应用
        $svr_pool_con = "";
        if($index==true && Yii::$app->user->getIdentity()->admin != 1)
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
        $query = DcmdServicePoolNode::find()->where($svr_pool_con)->orderBy('ip');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'svr_pool_id' => $this->svr_pool_id,
            'svr_id' => $this->svr_id,
            'nid' => $this->nid,
            'app_id' => $this->app_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
