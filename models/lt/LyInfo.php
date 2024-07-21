<?php

namespace app\models\lt;

use Yii;

/**
 * This is the model class for table "lt_ly_info".
 *
 * @property string $fldid
 * @property string $fldcbriefname
 * @property string $info
 */
class LyInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lt_ly_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fldid'], 'required'],
            [['fldid'], 'number'],
            [['info'], 'string'],
            [['fldcbriefname'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fldid' => 'Fldid',
            'fldcbriefname' => 'Fldcbriefname',
            'info' => 'Info',
        ];
    }
}
