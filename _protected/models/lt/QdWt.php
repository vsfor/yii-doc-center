<?php

namespace app\models\lt;

use Yii;

/**
 * This is the model class for table "lt_qd_wt".
 *
 * @property integer $id
 * @property string $entity_tag
 * @property string $filter
 * @property string $fldid
 * @property string $fldtag
 * @property string $qdbm
 * @property string $fldmapinfo
 * @property string $mdmc
 * @property string $qdmc
 * @property string $fgs
 * @property string $jb
 * @property string $ifwtd
 * @property string $fgfw
 * @property string $qdzt
 * @property string $qdlx
 * @property string $qdbdhfl
 * @property string $sfls
 * @property string $jsfw
 * @property string $imageinfo
 * @property string $jsdx
 * @property string $sjbbqdbm
 * @property string $qdgssfbm
 * @property string $qdgsmsqy
 * @property string $qdlsjb
 * @property string $sfjrslxt
 * @property string $jrxtzdsl
 * @property string $sfmnyyt
 * @property string $qddylx
 * @property string $khyh
 * @property string $yhzh
 * @property string $zhmc
 * @property string $qdlxrxm
 * @property string $qdlxryx
 * @property string $qdlxrdz
 * @property string $qdlxrbghm
 * @property string $qdlxrdh
 * @property string $qdlxryb
 * @property string $qdglbmbm
 * @property string $qdglrygh
 * @property string $qdglrydh
 * @property string $jmsj
 * @property string $zllusj
 */
class QdWt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lt_qd_wt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_tag', 'fldid', 'fldtag', 'qdbm', 'fgs', 'jb', 'ifwtd', 'fgfw', 'qdzt', 'qdlx', 'qdbdhfl', 'sfls', 'jsfw', 'jsdx', 'sjbbqdbm', 'qdgssfbm', 'qdgsmsqy', 'qdlsjb', 'sfjrslxt', 'jrxtzdsl', 'sfmnyyt', 'qddylx', 'yhzh', 'qdlxrxm', 'qdlxrbghm', 'qdlxrdh', 'qdlxryb', 'qdglbmbm', 'qdglrygh', 'qdglrydh', 'jmsj', 'zllusj'], 'string', 'max' => 32],
            [['filter', 'fldmapinfo', 'qdmc', 'imageinfo', 'khyh', 'qdlxryx', 'qdlxrdz'], 'string', 'max' => 255],
            [['mdmc', 'zhmc'], 'string', 'max' => 64],
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
            'fldid' => 'Fldid',
            'fldtag' => 'Fldtag',
            'qdbm' => 'Qdbm',
            'fldmapinfo' => 'Fldmapinfo',
            'mdmc' => 'Mdmc',
            'qdmc' => 'Qdmc',
            'fgs' => 'Fgs',
            'jb' => 'Jb',
            'ifwtd' => 'Ifwtd',
            'fgfw' => 'Fgfw',
            'qdzt' => 'Qdzt',
            'qdlx' => 'Qdlx',
            'qdbdhfl' => 'Qdbdhfl',
            'sfls' => 'Sfls',
            'jsfw' => 'Jsfw',
            'imageinfo' => 'Imageinfo',
            'jsdx' => 'Jsdx',
            'sjbbqdbm' => 'Sjbbqdbm',
            'qdgssfbm' => 'Qdgssfbm',
            'qdgsmsqy' => 'Qdgsmsqy',
            'qdlsjb' => 'Qdlsjb',
            'sfjrslxt' => 'Sfjrslxt',
            'jrxtzdsl' => 'Jrxtzdsl',
            'sfmnyyt' => 'Sfmnyyt',
            'qddylx' => 'Qddylx',
            'khyh' => 'Khyh',
            'yhzh' => 'Yhzh',
            'zhmc' => 'Zhmc',
            'qdlxrxm' => 'Qdlxrxm',
            'qdlxryx' => 'Qdlxryx',
            'qdlxrdz' => 'Qdlxrdz',
            'qdlxrbghm' => 'Qdlxrbghm',
            'qdlxrdh' => 'Qdlxrdh',
            'qdlxryb' => 'Qdlxryb',
            'qdglbmbm' => 'Qdglbmbm',
            'qdglrygh' => 'Qdglrygh',
            'qdglrydh' => 'Qdglrydh',
            'jmsj' => 'Jmsj',
            'zllusj' => 'Zllusj',
        ];
    }
}
