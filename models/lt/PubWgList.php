<?php

namespace app\models\lt;

use Yii;

/**
 * This is the model class for table "lt_pub_wg_list".
 *
 * @property integer $id
 * @property string $entity_tag
 * @property string $filter
 * @property string $fldbriefname
 * @property string $fldname
 * @property string $fldcode
 * @property string $wgssqy
 * @property string $jzdsqk2
 * @property string $wygx
 * @property string $jzdsqk1
 * @property string $kdzyjxqk
 * @property string $fldmapinfo
 * @property string $fldtag
 * @property string $fldid
 * @property string $fldex9
 * @property string $fldex10
 * @property string $flddotime
 * @property string $fldtotalbuilding
 */
class PubWgList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lt_pub_wg_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_tag', 'fldcode', 'wgssqy', 'jzdsqk2', 'wygx', 'jzdsqk1', 'kdzyjxqk', 'fldtag', 'fldid', 'fldex9', 'fldex10', 'flddotime', 'fldtotalbuilding'], 'string', 'max' => 32],
            [['filter', 'fldbriefname', 'fldname', 'fldmapinfo'], 'string', 'max' => 255],
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
            'fldbriefname' => 'Fldbriefname',
            'fldname' => 'Fldname',
            'fldcode' => 'Fldcode',
            'wgssqy' => 'Wgssqy',
            'jzdsqk2' => 'Jzdsqk2',
            'wygx' => 'Wygx',
            'jzdsqk1' => 'Jzdsqk1',
            'kdzyjxqk' => 'Kdzyjxqk',
            'fldmapinfo' => 'Fldmapinfo',
            'fldtag' => 'Fldtag',
            'fldid' => 'Fldid',
            'fldex9' => 'Fldex9',
            'fldex10' => 'Fldex10',
            'flddotime' => 'Flddotime',
            'fldtotalbuilding' => 'Fldtotalbuilding',
        ];
    }
}
