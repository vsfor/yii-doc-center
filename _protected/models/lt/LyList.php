<?php

namespace app\models\lt;

use Yii;

/**
 * This is the model class for table "lt_ly_list".
 *
 * @property integer $id
 * @property string $fldcbriefname
 * @property string $fldcname
 * @property string $fldex9
 * @property string $fldex10
 * @property string $areamanage
 * @property string $fldex3
 * @property string $fldex8
 * @property string $fldstreetno
 * @property string $flddoorno
 * @property string $xqname
 * @property string $ldh
 * @property string $fldcaddrss
 * @property string $fldex6
 * @property string $fldisfilling
 * @property string $fldnofillingnotes
 * @property string $flditype
 * @property string $fldex1
 * @property string $fldareaid
 * @property string $fldregionid
 * @property double $fldex5
 * @property string $fldcothername
 * @property string $fldex61
 * @property string $fldex62
 * @property string $fldex63
 * @property string $fldex64
 * @property string $vifok
 * @property string $flduser
 * @property string $buildgroupid
 * @property string $fldswwlwg
 * @property string $agbt
 * @property string $fldex26
 * @property string $fldex29
 * @property string $isftthzjsx
 * @property string $ifbasement
 * @property string $basementnum
 * @property string $fldex56
 * @property string $fldex60
 * @property string $fldex32
 * @property string $buildgroupname
 * @property string $fldex55
 * @property string $fldex33
 * @property string $fldex34
 * @property string $fldex35
 * @property string $fldex36
 * @property string $fldex37
 * @property string $fldex38
 * @property string $fldex46
 * @property string $fldex50
 * @property string $fldex12
 * @property string $fldex68
 * @property string $fldex70
 * @property string $fldex71
 * @property string $buildingvalue
 * @property string $zhuhuzuhunum
 * @property string $yztelusersnum
 * @property string $yzadslusersnum
 * @property string $frontneednum
 * @property string $fldid
 * @property string $fldtag
 * @property string $flddotime
 * @property string $fldreqip
 * @property string $fldflong
 * @property string $fldflat
 * @property string $fldex11
 * @property string $zjname
 * @property string $zjphone
 * @property string $isfw
 * @property string $fldex42
 * @property string $fldex43
 * @property string $fldex44
 * @property string $fldex45
 * @property string $fldex57
 */
class LyList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lt_ly_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fldex6', 'fldex5', 'fldex70', 'zhuhuzuhunum', 'yztelusersnum', 'yzadslusersnum', 'frontneednum', 'fldid', 'fldflong', 'fldflat'], 'number'],
            [['fldcbriefname', 'fldisfilling', 'fldareaid', 'fldregionid', 'vifok', 'ifbasement', 'basementnum', 'fldex56', 'fldex60', 'fldex55', 'fldex71', 'buildingvalue', 'fldtag', 'flddotime', 'fldex11', 'fldex42', 'fldex43', 'fldex44', 'fldex45', 'fldex57'], 'string', 'max' => 32],
            [['fldcname', 'fldcothername', 'fldex61', 'fldex62', 'fldex63', 'fldex64'], 'string', 'max' => 120],
            [['fldex9', 'fldex10', 'areamanage', 'fldex3', 'fldex8', 'fldstreetno', 'flddoorno', 'xqname', 'ldh', 'fldnofillingnotes', 'flditype', 'fldex1', 'flduser', 'buildgroupid', 'agbt', 'fldex26', 'fldex29', 'fldex32', 'fldex33', 'fldex34', 'fldex35', 'fldex36', 'fldex37', 'fldex46', 'fldex50', 'fldex12', 'fldex68', 'zjname'], 'string', 'max' => 64],
            [['fldcaddrss'], 'string', 'max' => 100],
            [['fldswwlwg', 'buildgroupname'], 'string', 'max' => 128],
            [['isftthzjsx', 'fldreqip'], 'string', 'max' => 16],
            [['fldex38', 'isfw'], 'string', 'max' => 12],
            [['zjphone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fldcbriefname' => 'Fldcbriefname',
            'fldcname' => 'Fldcname',
            'fldex9' => 'Fldex9',
            'fldex10' => 'Fldex10',
            'areamanage' => 'Areamanage',
            'fldex3' => 'Fldex3',
            'fldex8' => 'Fldex8',
            'fldstreetno' => 'Fldstreetno',
            'flddoorno' => 'Flddoorno',
            'xqname' => 'Xqname',
            'ldh' => 'Ldh',
            'fldcaddrss' => 'Fldcaddrss',
            'fldex6' => 'Fldex6',
            'fldisfilling' => 'Fldisfilling',
            'fldnofillingnotes' => 'Fldnofillingnotes',
            'flditype' => 'Flditype',
            'fldex1' => 'Fldex1',
            'fldareaid' => 'Fldareaid',
            'fldregionid' => 'Fldregionid',
            'fldex5' => 'Fldex5',
            'fldcothername' => 'Fldcothername',
            'fldex61' => 'Fldex61',
            'fldex62' => 'Fldex62',
            'fldex63' => 'Fldex63',
            'fldex64' => 'Fldex64',
            'vifok' => 'Vifok',
            'flduser' => 'Flduser',
            'buildgroupid' => 'Buildgroupid',
            'fldswwlwg' => 'Fldswwlwg',
            'agbt' => 'Agbt',
            'fldex26' => 'Fldex26',
            'fldex29' => 'Fldex29',
            'isftthzjsx' => 'Isftthzjsx',
            'ifbasement' => 'Ifbasement',
            'basementnum' => 'Basementnum',
            'fldex56' => 'Fldex56',
            'fldex60' => 'Fldex60',
            'fldex32' => 'Fldex32',
            'buildgroupname' => 'Buildgroupname',
            'fldex55' => 'Fldex55',
            'fldex33' => 'Fldex33',
            'fldex34' => 'Fldex34',
            'fldex35' => 'Fldex35',
            'fldex36' => 'Fldex36',
            'fldex37' => 'Fldex37',
            'fldex38' => 'Fldex38',
            'fldex46' => 'Fldex46',
            'fldex50' => 'Fldex50',
            'fldex12' => 'Fldex12',
            'fldex68' => 'Fldex68',
            'fldex70' => 'Fldex70',
            'fldex71' => 'Fldex71',
            'buildingvalue' => 'Buildingvalue',
            'zhuhuzuhunum' => 'Zhuhuzuhunum',
            'yztelusersnum' => 'Yztelusersnum',
            'yzadslusersnum' => 'Yzadslusersnum',
            'frontneednum' => 'Frontneednum',
            'fldid' => 'Fldid',
            'fldtag' => 'Fldtag',
            'flddotime' => 'Flddotime',
            'fldreqip' => 'Fldreqip',
            'fldflong' => 'Fldflong',
            'fldflat' => 'Fldflat',
            'fldex11' => 'Fldex11',
            'zjname' => 'Zjname',
            'zjphone' => 'Zjphone',
            'isfw' => 'Isfw',
            'fldex42' => 'Fldex42',
            'fldex43' => 'Fldex43',
            'fldex44' => 'Fldex44',
            'fldex45' => 'Fldex45',
            'fldex57' => 'Fldex57',
        ];
    }
}
