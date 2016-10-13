<?php

namespace jext\jrbac\models;

use Yii;

/**
 * This is the model class for table "{{%jrbac_menu}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $label
 * @property string $icon
 * @property string $url
 * @property integer $sortorder
 * @property string $content
 * @property integer $status
 */
class JrbacMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%jrbac_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'sortorder', 'status'], 'integer'],
            [['label','icon'], 'string', 'max' => 32],
            [['url', 'content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '上级ID',
            'label' => '名称',
            'icon' => '图标',
            'url' => '链接',
            'sortorder' => '排序',
            'content' => '描述',
            'status' => '状态',
        ];
    }

}
