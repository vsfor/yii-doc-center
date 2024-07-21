<?php

namespace app\models\lt;

use Yii;

/**
 * This is the model class for table "lt_fld_list".
 *
 * @property integer $id
 * @property string $entity_tag
 * @property string $filter
 * @property string $fldcbriefname
 * @property string $fldcname
 * @property string $fldex9
 * @property string $fldex10
 * @property string $areamanage
 * @property string $xqname
 * @property string $fldmapinfo
 * @property string $swapcodeinfo
 * @property string $fldisfilling
 * @property string $fldbuildstatus
 * @property string $flddotime
 * @property string $fldprjinfo
 * @property string $fldlan
 * @property string $fldid
 * @property string $fldtag
 * @property string $correctinfo
 * @property string $louyumingxi
 * @property string $fldex60
 * @property string $fldex38
 */
class FldList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lt_fld_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_tag', 'fldcname', 'fldex9', 'fldex10', 'areamanage', 'xqname'], 'string', 'max' => 64],
            [['filter', 'fldcbriefname', 'fldmapinfo', 'swapcodeinfo', 'fldprjinfo', 'correctinfo', 'louyumingxi'], 'string', 'max' => 255],
            [['fldisfilling', 'fldbuildstatus', 'flddotime', 'fldlan', 'fldid', 'fldtag', 'fldex60', 'fldex38'], 'string', 'max' => 32],
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
            'fldcbriefname' => 'Fldcbriefname',
            'fldcname' => 'Fldcname',
            'fldex9' => 'Fldex9',
            'fldex10' => 'Fldex10',
            'areamanage' => 'Areamanage',
            'xqname' => 'Xqname',
            'fldmapinfo' => 'Fldmapinfo',
            'swapcodeinfo' => 'Swapcodeinfo',
            'fldisfilling' => 'Fldisfilling',
            'fldbuildstatus' => 'Fldbuildstatus',
            'flddotime' => 'Flddotime',
            'fldprjinfo' => 'Fldprjinfo',
            'fldlan' => 'Fldlan',
            'fldid' => 'Fldid',
            'fldtag' => 'Fldtag',
            'correctinfo' => 'Correctinfo',
            'louyumingxi' => 'Louyumingxi',
            'fldex60' => 'Fldex60',
            'fldex38' => 'Fldex38',
        ];
    }
}
