<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%catalog}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $project_id
 * @property integer $sort_number
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class Catalog extends \yii\db\ActiveRecord
{
    // the list of status values that can be stored in this table
    const STATUS_NORMAL   = 1;
    const STATUS_DELETED  = 9;

    /**
     * List of names for each status.
     * @var array
     */
    public $statusList = [
        self::STATUS_NORMAL   => 'Normal',
        self::STATUS_DELETED  => 'Deleted'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'sort_number', 'parent_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['name'], 'filter', 'filter' => 'trim'],
            [['name'], 'required'],
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
            'name' => Yii::t('app', 'Catalog Name'),
            'project_id' => Yii::t('app', 'Project ID'),
            'sort_number' => Yii::t('app', 'Sort Number'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!$this->sort_number) $this->sort_number = 99;
        if (!$this->parent_id) $this->parent_id = 0;
        return parent::beforeSave($insert);
    }

}
