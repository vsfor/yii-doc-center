<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%project_member}}".
 *
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $level
 */
class ProjectMember extends \yii\db\ActiveRecord
{
    // the list of level values that can be stored in this table
    const LEVEL_OWNER   = 99;
    const LEVEL_ADMIN  = 50;
    const LEVEL_EDITOR = 20;
    const LEVEL_READER = 10;

    /**
     * List of names for each level.
     * @var array
     */
    public static $levelList = [
        self::LEVEL_READER  => 'Reader',
        self::LEVEL_EDITOR  => 'Editor',
        self::LEVEL_ADMIN  => 'Admin',
        self::LEVEL_OWNER   => 'Owner',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id', 'level'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => Yii::t('app', 'Project ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'level' => Yii::t('app', 'User Level'),
        ];
    }
}
