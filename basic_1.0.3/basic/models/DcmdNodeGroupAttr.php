<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_node_group_attr".
 *
 * @property integer $id
 * @property integer $ngroup_id
 * @property string $attr_name
 * @property string $attr_value
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdNodeGroupAttr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_node_group_attr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ngroup_id', 'attr_name', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['ngroup_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['attr_name'], 'string', 'max' => 32],
            [['attr_value'], 'string', 'max' => 256],
            [['comment'], 'string', 'max' => 512],
            [['ngroup_id', 'attr_name'], 'unique', 'targetAttribute' => ['ngroup_id', 'attr_name'], 'message' => 'The combination of Ngroup ID and Attr Name has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ngroup_id' => 'Ngroup ID',
            'attr_name' => 'Attr Name',
            'attr_value' => 'Attr Value',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
