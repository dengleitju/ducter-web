<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_task_service_pool_attr_history".
 *
 * @property integer $id
 * @property integer $task_id
 * @property integer $app_id
 * @property integer $svr_id
 * @property integer $svr_pool_id
 * @property string $attr_name
 * @property string $attr_value
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdTaskServicePoolAttrHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_task_service_pool_attr_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'app_id', 'svr_id', 'svr_pool_id', 'attr_name', 'attr_value', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['task_id', 'app_id', 'svr_id', 'svr_pool_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['attr_name'], 'string', 'max' => 32],
            [['attr_value'], 'string', 'max' => 256],
            [['comment'], 'string', 'max' => 512],
            [['task_id', 'svr_pool_id', 'attr_name'], 'unique', 'targetAttribute' => ['task_id', 'svr_pool_id', 'attr_name'], 'message' => 'The combination of Task ID, Svr Pool ID and Attr Name has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'app_id' => 'App ID',
            'svr_id' => 'Svr ID',
            'svr_pool_id' => 'Svr Pool ID',
            'attr_name' => 'Attr Name',
            'attr_value' => 'Attr Value',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
