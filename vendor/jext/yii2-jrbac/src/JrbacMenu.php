<?php
namespace jext\jrbac\src;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%jrbac_menu}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $label
 * @property string $icon
 * @property string $url
 * @property integer $sort_order
 * @property string $content
 * @property integer $status
 */
class JrbacMenu extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        $am = \Yii::$app->getAuthManager();
        if ($am instanceof JDbManager) {
            return $am->menuTable;
        }
        return '{{%jrbac_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'sort_order', 'status'], 'integer'],
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
            'sort_order' => '排序',
            'content' => '描述',
            'status' => '状态',
        ];
    }

}
