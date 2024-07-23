<?php
namespace app\controllers\actions;

use app\components\ProjectLib;
use app\models\Project;
use Mpdf\Mpdf;
use yii\base\Action;
use yii\helpers\FileHelper;
use yii\helpers\Html;

/**
 * 文档项目导出为PDF
 *
 * @jyrbac 文档项目-导出PDF
 */
class ProjectExportAction extends Action
{
    public function init()
    {
        parent::init();
        $this->controller->enableCsrfValidation = true;
    }
    /** @var Project */
    protected $project;
    /** @var Mpdf */
    protected $pdf;

    public function run($project_id)
    {
        $this->controller->layout = false;
        $this->project = Project::findOne($project_id);
        if (empty($this->project)) {
            return $this->goHome();
        }

        $cacheKey = "Project:Pdf:$project_id";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }

        $lib = ProjectLib::getInstance();
        $itemList = $lib->getPdfList($project_id); //获取内容列表
//        dd($itemList);

        $this->initPdf(); //pdf 组件初始化
        $this->setPdfHtmlStyle(); //载入样式
        $this->setPdfHeader(); //设置页眉
        $this->setPdfFooter('DocProject &copy; <a target="_blank" href="https://ydc.jeen.wang/">YDC</a>'); //设置页脚

        $this->setPdfContent($itemList);
        $filename = 'YDC_'.$this->project->name.'_'.date('ymdHi').'.pdf';
        $cache = $this->pdf->Output($filename, \Mpdf\Output\Destination::INLINE);

        \Yii::$app->getCache()->set($cacheKey, $cache);
        return $cache;
    }

    //基于文档进行分页
    protected function setPdfContent($itemList)
    {
        $this->pdf->WriteHTML('<bookmark content="'.$this->project->name.'" level="0" />');

        $dirIndex = 0;
        $pageIndex = 0;
        foreach ($itemList as $item) {
            switch ($item['type']) {
                case 'page': {
                    $this->pdf->WriteHTML('<bookmark content="'.$item['data']['title'].'" level="'.$item['level'].'" />');
                    $pageHtml = $this->controller->render('/page/pdf', ['model' => $item['data']]);
//                    if (1632 == $item['data']['id']??'err') {
//                        $this->pageDebug($pageHtml); //调试样式
//                    }
                    $this->pdf->WriteHTML($pageHtml);
                } break;
                case 'catalog': {
                    if ($item['level'] == 1) {
                        $this->setPdfHeader(null, $item['data']['name']);
                        $this->pdf->AddPage();
                    } else {
                        $this->setPdfHeader($item['data']['name']);
                    }
                    $this->pdf->WriteHTML('<bookmark content="'.$item['data']['name'].'" level="'.$item['level'].'" />');
                } break;
            }
        }

        return $this;
    }

    protected function pageDebug($content)
    {
        unset($this->pdf);
        $css = '';
        foreach ($this->cssRelativeList as $relativePath) {
            $css .= Html::tag('link','',[
                'rel' => 'stylesheet',
                'href' => $relativePath,
            ]);
        }
        $html = <<<HTMLSTR
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PdfDebug</title>
    {$css}
</head>
<body>
    {$content}
</body>
</html>
HTMLSTR;
        echo $html;
        die();
    }

    //基于文档进行分页
    protected function setPdfContentByPage($itemList)
    {
        $tempBookmarks = [];
        foreach ($itemList as $item) {
            if ($item['type'] == 'page') {
                $pageHeader['R']['content'] = $item['data']['title'];
                $this->pdf->DefHeaderByName('diyHeader', $pageHeader);
                $this->pdf->mirrorMargins = 1; //Odd基数  Even偶数
                $this->pdf->SetHeaderByName('diyHeader', 'O');
                $this->pdf->SetHeaderByName('diyHeader', 'E');

                $this->pdf->AddPage();

                while ($tempBookmarks) {
                    $tempBookmark = array_shift($tempBookmarks);
                    $this->pdf->Bookmark($tempBookmark['txt'], $tempBookmark['level'], 1);
                }
                $this->pdf->Bookmark($item['data']['title'], $item['level']);

                $pageHtml = $this->controller->render('/page/pdf', ['model' => $item['data']]);
//                dd($pageHtml);
                $this->pdf->WriteHTML($pageHtml);

            } elseif ($item['type'] == 'catalog') {
                $pageHeader['C']['content'] = $item['data']['name'];
                array_push($tempBookmarks, [
                    'txt' => $item['data']['name'],
                    'level' => $item['level']
                ]);
            }
        }

        while ($tempBookmarks) {
            $tempBookmark = array_shift($tempBookmarks);
            $this->pdf->Bookmark($tempBookmark['txt'], $tempBookmark['level'], 1);
        }

        return $this;
    }

    protected function initPdf()
    {
        $this->initMPdfEnv();
        $pdfConfig = [
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
            'tempDir' => \Yii::$app->getRuntimePath().'/mpdf/',//for version 8.2.x
        ];
        $this->pdf = new Mpdf($pdfConfig);
        //基础设置
        $this->pdf->SetProtection(['copy','print'],'', 'ydc.jeen.wang');//设置权限
        $this->pdf->SetTitle($this->project->name); //标题
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
        $this->pdf->SetWatermarkText('Y  D  C',0.05);
        $this->pdf->showWatermarkText = true;
        $this->pdf->watermark_font = 'Sun-ExtA'; //支持中文
        //图片无法展示问题
//        $this->pdf->showImageErrors = true;
        return $this;
    }

    protected $cssRelativeList = [
        '/static/formpdf/kv-mpdf-bootstrap.min.css',
        '/static/formpdf/diy.css',
    ]; //相对路径 - 基于play/web/
    protected function setPdfHtmlStyle()
    {
        foreach ($this->cssRelativeList as $relativePath) {
            $cssFile = \Yii::getAlias('@webroot'.$relativePath);
            if (!file_exists($cssFile)) {
                continue;
            }

            $css = file_get_contents($cssFile);
            $this->pdf->WriteHTML($css, 1);
        }
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
    protected function setPdfHeader($centerTxt=null,$rightTxt=null)
    {
        if (empty($this->pageHeader['L']['content'])) {
            $this->pageHeader['L']['content'] = $this->project->name;
        }
        if (!empty($centerTxt)) {
            $this->pageHeader['C']['content'] = $centerTxt;
        }
        if (!empty($rightTxt)) {
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
    protected function setPdfFooter($copyright = '')
    {
        if (!empty($copyright)) {
            $this->pageFooter['C']['content'] = $copyright;
        }
        $this->pdf->DefFooterByName('diyFooter', $this->pageFooter);
        $this->pdf->mirrorMargins = 1;
        $this->pdf->SetHTMLFooterByName('diyFooter', 'O');
        $this->pdf->SetHTMLFooterByName('diyFooter', 'E');
        return $this;
    }

    protected function initMPdfEnv()
    {
        $prefix = \Yii::$app->getRuntimePath() . '/mpdf/';

        //for old version
        $this->definePath('_MPDF_TEMP_PATH', "{$prefix}tmp");
        $this->definePath('_MPDF_TTFONTDATAPATH', "{$prefix}ttfontdata");
    }

    protected function definePath($prop, $dir)
    {
        FileHelper::createDirectory($dir);
        defined($prop) or define($prop, $dir);
    }
}
