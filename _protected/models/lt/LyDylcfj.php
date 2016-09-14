<?php

namespace app\models\lt;

use Yii;

/**
 * This is the model class for table "lt_ly_dylcfj".
 *
 * @property string $roomid
 * @property string $fldid
 * @property string $fldcbriefname
 * @property integer $dyh
 * @property integer $lch
 * @property string $fjh
 * @property string $fjid
 * @property string $bzdz
 */
class LyDylcfj extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lt_ly_dylcfj';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roomid'], 'required'],
            [['fldid'], 'number'],
            [['dyh', 'lch'], 'integer'],
            [['roomid'], 'string', 'max' => 32],
            [['fldcbriefname', 'fjh', 'fjid'], 'string', 'max' => 15],
            [['bzdz'], 'string', 'max' => 127],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'roomid' => 'Roomid',
            'fldid' => 'Fldid',
            'fldcbriefname' => 'Fldcbriefname',
            'dyh' => 'Dyh',
            'lch' => 'Lch',
            'fjh' => 'Fjh',
            'fjid' => 'Fjid',
            'bzdz' => 'Bzdz',
        ];
    }
}
