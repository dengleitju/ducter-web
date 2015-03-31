<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_service_pool_attr_def".
 *
 * @property integer $attr_id
 * @property string $attr_name
 * @property integer $optional
 * @property integer $attr_type
 * @property string $def_value
 * @property string $comment
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdServicePoolAttrDef extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_pool_attr_def';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attr_name', 'optional', 'attr_type', 'ctime', 'opr_uid'], 'required'],
            [['optional', 'attr_type', 'opr_uid'], 'integer'],
            [['ctime'], 'safe'],
            [['attr_name'], 'string', 'max' => 32],
            [['def_value'], 'string', 'max' => 256],
            [['comment'], 'string', 'max' => 512],
            [['attr_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attr_id' => 'Attr ID',
            'attr_name' => 'Attr Name',
            'optional' => 'Optional',
            'attr_type' => 'Attr Type',
            'def_value' => 'Def Value',
            'comment' => 'Comment',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function getUserName($uid) {
      $query = DcmdUser::findOne($uid);
      if($query) return $query['username'];
      return "";
    }
    public function convertYesNo($v) {
      if($v == 0) return "否";
      return "是";
    }
    public function convertType($v){
      $ar = array(1=>"int", 2=>"float", 3=>"bool", 4=>"char", 5=>"datetime");
      return $ar[$v];
   }
}
