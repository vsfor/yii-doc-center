<?php
namespace app\components;

use Mpdf\Mpdf;
use yii\helpers\FileHelper;

class PdfLib
{
    protected $pdfConfig = [
        'mode' => 'UTF-8',
        'format' => 'A4',
        'default_font_size' => 0,
        'default_font' => '',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'margin_header' => 9,
        'margin_footer' => 9,
        'orientation' => 'P',
    ];

    protected $pdfPermissions = [
        'copy',
        'print',
        'modify',
        'annot-forms',
        'fill-forms',
        'extract',
        'assemble',
        'print-highres',
    ];

    /** @var Mpdf */
    protected $pdf;

    protected function __construct()
    {
        $prefix = \Yii::$app->getRuntimePath() . '/mpdf/';
        FileHelper::createDirectory("{$prefix}tmp");
        defined('_MPDF_TEMP_PATH') or define('_MPDF_TEMP_PATH', "{$prefix}tmp");
        FileHelper::createDirectory("{$prefix}ttfontdata");
        defined('_MPDF_TEMP_PATH') or define('_MPDF_TTFONTDATAPATH', "{$prefix}ttfontdata");
    }

    public static function getInstance()
    {
        return new self();
    }
    /**
     * @see \app\controllers\actions\ProjectExportAction
     *
     * @return self
     * @throws \Mpdf\MpdfException
     * @throws \yii\base\Exception
     */
    public function makeMpdf($title= '',$pwd = '')
    {
        $this->pdf = new Mpdf($this->pdfConfig);
        //权限设置
        $this->pdf->SetProtection($this->pdfPermissions, $pwd, 'ydc.jeen.wang');
        //文件属性设置
        $this->pdf->SetTitle($title ? : 'YDC文档');
        $this->pdf->SetAuthor('ydc'); //作者
        $this->pdf->SetCreator('ydc.jeen.wang'); //应用
        $this->pdf->SetSubject('pdf doc'); //主题
        $this->pdf->SetKeywords('ydc,pdf,doc'); //关键字
        //CJK渲染
        $this->pdf->autoScriptToLang = true;
        $this->pdf->autoVietnamese = true;
        $this->pdf->autoArabic = true;
        $this->pdf->autoLangToFont = true;//部分字体在中英混合时会出现乱码，"Sun-ExtA"
        $this->pdf->useSubstitutions = true;//解决中英混合时出现乱码
        //水印设置
        $this->setWaterMark('Y  D  C');

        $cssRelativeList = [
            '@webroot/static/formpdf/kv-mpdf-bootstrap.min.css',
            '@webroot/static/formpdf/diy.css',
        ];
        foreach ($cssRelativeList as $relativePath) {
            $cssFile = \Yii::getAlias($relativePath);
            if (!file_exists($cssFile)) {
                continue;
            }

            $css = file_get_contents($cssFile);
            $this->pdf->WriteHTML($css, 1);
        }

        $this->setPdfFooter('DocProject &copy; <a target="_blank" href="https://ydc.jeen.wang/">YDC</a>'); //设置页脚
        return $this;
    }

    protected $pageHeader = [
        'L' => [
            'content' => '',
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' =>  'serif',
            'color' => '#111111',
        ],
        'C' => [
            'content' => '',
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' =>  'serif',
            'color' => '#999999',
        ],
        'R' => [
            'content' => '',
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' =>  'serif',
            'color' => '#555555',
        ],
        'line' => 1,
    ];
    public function setPdfHeader($leftTxt=null,$centerTxt=null,$rightTxt=null)
    {
        if (!is_null($leftTxt)) {
            $this->pageHeader['L']['content'] = $leftTxt;
        }
        if (!is_null($centerTxt)) {
            $this->pageHeader['C']['content'] = $centerTxt;
        }
        if (!is_null($rightTxt)) {
            $this->pageHeader['R']['content'] = $rightTxt;
        }
        $this->pdf->DefHeaderByName('diyHeader', $this->pageHeader);
        $this->pdf->mirrorMargins = 1; //Odd基数  Even偶数
        $this->pdf->SetHeaderByName('diyHeader', 'O');
        $this->pdf->SetHeaderByName('diyHeader', 'E');
        return $this;
    }

    protected $pageFooter = [
        'L' => [
            'content' => '{DATE Y-m-d}',//'{DATE Y-m-d}/{nb}/'.$this->project->id,
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' =>  'serif',
            'color' => '#555555',
        ],
        'C' => [
            'content' => '',// {nb} ｜ {nbpg}
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' =>  'serif',
            'color' => '#999999',
        ],
        'R' => [
            'content' => '{PAGENO}/{nbpg}', // {nb} | {nbpg}
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' => 'serif',
            'color' => '#111111',
        ],
        'line' => 1,
    ];
    public function setPdfFooter($copyright = '')
    {
        $this->pageFooter['C']['content'] = $copyright;
        $this->pdf->DefFooterByName('diyFooter', $this->pageFooter);
        $this->pdf->mirrorMargins = 1;
        $this->pdf->SetHTMLFooterByName('diyFooter', 'O');
        $this->pdf->SetHTMLFooterByName('diyFooter', 'E');
        return $this;
    }

    public function setWaterMark($watermark='')
    {
        if (empty($watermark)) {
            $this->pdf->showWatermarkText = false;
            return $this;
        }
        //水印设置
        $this->pdf->SetWatermarkText($watermark,0.02);
        $this->pdf->showWatermarkText = true;
        $this->pdf->watermark_font = 'Sun-ExtA'; //支持中文
        return $this;
    }

    public function writeHtml($html)
    {
        $this->pdf->WriteHTML($html);
        return $this;
    }

    public function addPage()
    {
        $this->pdf->AddPage();
        return $this;
    }

    public function output($filename=null)
    {
        $file = 'YDC_'.($filename??uniqid()).'_'.date('ymdHi').'.pdf';
        return $this->pdf->Output($file, \Mpdf\Output\Destination::INLINE);
    }

    /**
     * @return Mpdf
     */
    public function getPdfObject()
    {
        return $this->pdf;
    }

}