<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_service_arch_diagram".
 *
 * @property integer $id
 * @property integer $app_id
 * @property integer $svr_id
 * @property string $arch_name
 * @property string $diagram
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdServiceArchDiagram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_arch_diagram';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'svr_id', 'arch_name', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['app_id', 'svr_id', 'opr_uid'], 'integer'],
            [['diagram'], 'string'],
            [['utime', 'ctime'], 'safe'],
            [['arch_name'], 'string', 'max' => 200],
            [['comment'], 'string', 'max' => 512],
            [['svr_id', 'arch_name'], 'unique', 'targetAttribute' => ['svr_id', 'arch_name'], 'message' => 'The combination of Svr ID and Arch Name has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'svr_id' => 'Svr ID',
            'arch_name' => 'Arch Name',
            'diagram' => 'Diagram',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
