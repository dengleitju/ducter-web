<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_group_repeat_cmd".
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $repeat_cmd_id
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdGroupRepeatCmd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_group_repeat_cmd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'repeat_cmd_id', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['gid', 'repeat_cmd_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['gid', 'repeat_cmd_id'], 'unique', 'targetAttribute' => ['gid', 'repeat_cmd_id'], 'message' => 'The combination of Gid and Repeat Cmd ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Gid',
            'repeat_cmd_id' => 'Repeat Cmd ID',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function getGroupName($gid)
    {
      $query = DcmdGroup::findOne($gid);
      if($query) return $query['gname'];
      return "";
    }
    public function getRepeatcmd($repeat_cmd_id)
    {
       $query = DcmdOprCmdRepeatExec::findOne($repeat_cmd_id);
       if($query) return $query['repeat_cmd_name'];
       return "";
    }
    public function getOprcmd($repeat_cmd_id)
    {
       $query = DcmdOprCmdRepeatExec::findOne($repeat_cmd_id);
       if($query) return $query['opr_cmd'];
       return "";
    }
}
