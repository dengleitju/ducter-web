<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdApp;

/**
 * DcmdAppSearch represents the model behind the search form about `app\models\DcmdApp`.
 */
class DcmdAppSearch extends DcmdApp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'sa_gid', 'svr_gid', 'depart_id', 'opr_uid'], 'integer'],
            [['app_name', 'app_alias', 'comment', 'utime', 'ctime'], 'safe'],
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
        ///应用足用户只可查看所在组的应用
        $app_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
        }
        $query = DcmdApp::find()->where($app_con)->orderBy('app_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'app_id' => $this->app_id,
            'sa_gid' => $this->sa_gid,
            'svr_gid' => $this->svr_gid,
            'depart_id' => $this->depart_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'app_alias', $this->app_alias])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
