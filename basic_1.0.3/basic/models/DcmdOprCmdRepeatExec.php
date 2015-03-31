<?php

namespace app\models;

use Yii;
include_once( dirname(__FILE__)."/../common/dcmd_util_func.php");
/**
 * This is the model class for table "dcmd_opr_cmd_repeat_exec".
 *
 * @property integer $repeat_cmd_id
 * @property string $repeat_cmd_name
 * @property string $opr_cmd
 * @property string $run_user
 * @property integer $timeout
 * @property string $ip
 * @property integer $repeat
 * @property integer $cache_time
 * @property integer $ip_mutable
 * @property integer $arg_mutable
 * @property string $arg
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdOprCmdRepeatExec extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_opr_cmd_repeat_exec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['repeat_cmd_name', 'opr_cmd', 'run_user', 'timeout', 'ip', 'repeat', 'cache_time', 'ip_mutable', 'arg_mutable', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['timeout', 'repeat', 'cache_time', 'ip_mutable', 'arg_mutable', 'opr_uid'], 'integer'],
            [['ip', 'arg'], 'string'],
            [['utime', 'ctime'], 'safe'],
            [['repeat_cmd_name'], 'match', 'pattern'=>'/^[a-zA-Z0-9_]+$/', 'message'=>'只可包含[a-z,A-Z,0-9,_]字符'],
            [['repeat_cmd_name', 'opr_cmd', 'run_user'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'repeat_cmd_id' => 'Repeat Cmd ID',
            'repeat_cmd_name' => 'Repeat Cmd Name',
            'opr_cmd' => 'Opr Cmd',
            'run_user' => 'Run User',
            'timeout' => 'Timeout',
            'ip' => 'Ip',
            'repeat' => 'Repeat',
            'cache_time' => 'Cache Time',
            'ip_mutable' => 'Ip Mutable',
            'arg_mutable' => 'Arg Mutable',
            'arg' => 'Arg',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
   public function yesOrNo($n) {
     if($n == 0) return "否";
     return "是";
   }
   public function repeatInfo($repeat) {
     if($repeat == 1) return "不记录执行记录";
     return "记录执行记录";
   }
   public function parseArg($arg) {
     $ret = xml_to_array($arg);
     $temp = $ret['env'];
     $str = "";
     if(is_array($temp)){
      foreach($temp as $k=>$v) {
        $str .= $k."=".$v." ";
      }
     }
     return $str;
   }
}
