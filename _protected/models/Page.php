<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property integer $author_id
 * @property integer $project_id
 * @property integer $catalog_id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property integer $sort_number
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class Page extends \yii\db\ActiveRecord
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
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'project_id', 'catalog_id', 'sort_number', 'created_at', 'updated_at', 'status'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 64],
            [['title'], 'filter', 'filter' => 'trim'],
            [['description'], 'string', 'max' => 255],
            [['title', 'content', 'description'], 'required'],
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
            'author_id' => Yii::t('app', 'Author ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'catalog_id' => Yii::t('app', 'Catalog ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
            'sort_number' => Yii::t('app', 'Sort Number'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!$this->catalog_id) $this->catalog_id = 0;
        if (!$this->sort_number) $this->sort_number = 99;
        return parent::beforeSave($insert);
    }
}
