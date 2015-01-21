<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_opr_cmd_exec".
 *
 * @property integer $exec_id
 * @property integer $opr_cmd_id
 * @property string $opr_cmd
 * @property string $run_user
 * @property integer $timeout
 * @property string $ip
 * @property string $arg
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdOprCmdExec extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_opr_cmd_exec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opr_cmd_id', 'opr_cmd', 'run_user', 'timeout', 'ip', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['opr_cmd_id', 'timeout', 'opr_uid'], 'integer'],
            [['ip', 'arg'], 'string'],
            [['utime', 'ctime'], 'safe'],
            [['opr_cmd', 'run_user'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exec_id' => 'Exec ID',
            'opr_cmd_id' => 'Opr Cmd ID',
            'opr_cmd' => 'Opr Cmd',
            'run_user' => 'Run User',
            'timeout' => 'Timeout',
            'ip' => 'Ip',
            'arg' => 'Arg',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
