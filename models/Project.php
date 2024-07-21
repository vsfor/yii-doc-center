<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $open_type
 */
class Project extends \yii\db\ActiveRecord
{
    const STATUS_NORMAL   = 1;
    const STATUS_DELETED  = 9;
    public $statusList = [
        self::STATUS_NORMAL   => 'Normal',
        self::STATUS_DELETED  => 'Deleted'
    ];

    const OPEN_TO_NONE = 0;
    const OPEN_TO_USER = 1;
    const OPEN_TO_ALL = 2;
    public $openList = [
        self::OPEN_TO_NONE => '不开放',
        self::OPEN_TO_USER => '开放给本站用户',
        self::OPEN_TO_ALL => '开放给所有人'
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at', 'status', 'open_type'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255],
            [['name', 'description'], 'required'],
        ];
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Project Name'),
            'description' => Yii::t('app', 'Description'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'open_type' => '开放类型',
        ];
    }
}
