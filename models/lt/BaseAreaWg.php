<?php

namespace app\models\lt;

use Yii;

/**
 * This is the model class for table "lt_base_area_wg".
 *
 * @property integer $id
 * @property string $entity_tag
 * @property string $filter
 * @property string $fldname
 * @property string $fldcode
 * @property string $fldtag
 * @property string $fldid
 * @property string $fldareatag
 * @property string $fldareaid
 * @property string $fldlan
 * @property string $fldbrcomp
 * @property string $fldmapinfo
 */
class BaseAreaWg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lt_base_area_wg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_tag', 'fldcode', 'fldtag', 'fldid', 'fldareatag', 'fldareaid', 'fldlan', 'fldbrcomp'], 'string', 'max' => 32],
            [['filter', 'fldname', 'fldmapinfo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_tag' => 'Entity Tag',
            'filter' => 'Filter',
            'fldname' => 'Fldname',
            'fldcode' => 'Fldcode',
            'fldtag' => 'Fldtag',
            'fldid' => 'Fldid',
            'fldareatag' => 'Fldareatag',
            'fldareaid' => 'Fldareaid',
            'fldlan' => 'Fldlan',
            'fldbrcomp' => 'Fldbrcomp',
            'fldmapinfo' => 'Fldmapinfo',
        ];
    }
}
