<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\console\controllers;
 
use app\models\lt\LyDylcfj;
use app\models\lt\LyInfo;
use app\models\lt\LyList;
use Yii;
use app\components\Jeen;
use app\models\User;
use yii\console\Controller;
use hightman\http\Client;
use hightman\http\Request;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This command echoes the first argument that you have entered.
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
        $rows = User::find()->where([
            'id'=>[1,2]
        ])->asArray()->all();
        Jeen::echoln($rows);
    }
    
    public function actionTc()
    {
        $t = \Yii::$app->security->generateRandomString();
        $password = 'admin';
        $t = Yii::$app->security->generatePasswordHash($password);
        Jeen::echoln($t);
    }

    public function actionSphinx()
    {
        /** @var \yii\sphinx\Connection $s */
        $s = \Yii::$app->sphinx;
        $q = new \yii\sphinx\Query();
        $rows = $q->from('test1')
            ->select(['id','group_id'])
            ->match('电影吃饭')
            ->where(['group_id' => [1,2]])
            ->andWhere(['>','id',3])
            ->all();
        Jeen::echoln($rows);
    }

    public function actionXs()
    {
//        $model = new \app\components\xunsearch\Test();
//        $model->id = 5;
//        $model->title = '中文标题';
//        $model->content = '今天只吃饭,不看电影';
//        $model->status = 1;
//        if ($model->save()) {
//            Jeen::echoln($model->toArray());
//        } else {
//            Jeen::echoln($model->getErrors());
//        }
        
//        $a = \app\components\xunsearch\Test::findOne(2);
//        $a->content .= ' update';
//        $a->addIndex('content', '电影');
//        $a->save();
//        Jeen::echoln($a->toArray());
        
        $q = \app\components\xunsearch\Test::find();
//        $q->getDb()->getIndex()->flushIndex();
        $t = $q->where('电影')->asArray()->all();
        Jeen::echoln($t);
        
        $db = \app\components\xunsearch\Test::getDb();
        $scws = $db->getScws();
        $t = $scws->getResult('吃饭看电影');
        Jeen::echoln($t);
        
        $index = $db->getIndex();
        $t = $index->getProject();
        Jeen::echoln($t);
        
        $search = $db->getSearch();
        $t = $search->getDbTotal();
        Jeen::echoln($t);
        
        
    }
 
    
    public function actionLt()
    { 
        $t = '';
        //楼宇信息
//        $ly = $this->getLY();
//        Jeen::echoln('============'.$ly->statusText);
//        $t = Json::encode($this->xmlToArr($ly->body));
//        Jeen::echoln($t);
//
        $ly = $this->getLY('12062005106052945');
        Jeen::echoln('============'.$ly->statusText);
        $t = Json::encode($this->xmlToArr($ly->body));
        Jeen::echoln($t);
        
        //单元信息
//        $dy = $this->queryInfoDY();
//        $t = json_decode($dy->getContent(), true);
//        Jeen::echoln($t);
        
        //楼层信息
//        $lc = $this->queryInfoLC();
//        $t = json_decode($lc->getContent(), true);
//        Jeen::echoln($t);

        //房间信息
//        $fj = $this->queryInfoFJ();
//        $t = json_decode($fj->getContent(), true);
//        Jeen::echoln($t);
        
        //报装地址信息
//        $dz = $this->queryInfoBZDZ();
//        $t = $dz->getContent();
//        Jeen::echoln($t);


        
        Jeen::echoln('done');
    }

    /**
     * @param bool $debug
     * @return Client
     */
    protected $pubClient = null;
    protected $pubRequest = null;
    protected function getClient($debug = false)
    {
        if($this->pubClient) {
            return $this->pubClient;
        }
        $client = new Client();
        $client->clearCookie();
        $client->clearHeader();
        if ($debug) $client->debug('open');

        $indexUrl = 'http://202.96.18.90:9001/NRMS/csm_select_print.jsp?sid=AeQSilHv8DKao5%2Bi%2Bgd1rA==&current=W8Ywvw7noVklGgspZSuneQ==&bc=&returnflag=1&stid=GSQ%2FkncxH1o=&cname=JVnwWXU/zaU=&cphone=AeQSilHv8DKao5%2Bi%2Bgd1rA==';
        $client->setHeader('Referer', $indexUrl);

        $client->setHeader('Accept', '*/*');
        $client->setHeader('Accept-Encoding', 'gzip, deflate');
        $client->setHeader('Accept-Language', 'zh-CN,zh;q=0.8,en;q=0.6');
        $client->setHeader('X-Requested-With', 'Ext.basex');
        $client->setHeader('Host', '202.96.18.90:9001');
        $client->setHeader('Origin', 'http://202.96.18.90:9001');
        $client->setHeader('Connection', 'keep-alive');

        $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36';
        $client->setHeader('User-Agent', $userAgent);

        $cookie = urldecode('JSESSIONID=0000XPLGbF_EV0yyWMuCGTtHYSI:-1; BIGipServerly_was_9001=1581927812.31267.0000; fenbianlv=100%25; fenbianlvwidth=100; fenbianlvheight=100; ys-queryComboCmpId=s%3AcomboCmp1473127789914; ys-queryFormPnlId=s%3AidFormPanel1473127789922; ys-tabPnlId=s%3AtabPnlId1473127789960; ys-tabId01=s%3AtabId011473127789963; ys-tabId03=s%3AtabId031473127789963');
        $client->setHeader('Cookie', $cookie);

        $this->pubClient = $client;
        return $client;
    }

    protected function getRequest()
    {
        if ($this->pubRequest) {
            return $this->pubRequest;
        }
        $request = new \yii\httpclient\Request();
        $request->client = new \yii\httpclient\Client();
        $header = new \yii\web\HeaderCollection();
        $indexUrl = 'http://202.96.18.90:9001/NRMS/csm_select_print.jsp?sid=AeQSilHv8DKao5%2Bi%2Bgd1rA==&current=W8Ywvw7noVklGgspZSuneQ==&bc=&returnflag=1&stid=GSQ%2FkncxH1o=&cname=JVnwWXU/zaU=&cphone=AeQSilHv8DKao5%2Bi%2Bgd1rA==';
        $indexUrl = 'http://202.96.18.90:9001/NRMS/index.jsp';
        $header->set('Referer', $indexUrl);

        $header->set('Accept', '*/*');
        $header->set('Accept-Encoding', 'gzip, deflate');
        $header->set('Accept-Language', 'zh-CN,zh;q=0.8,en;q=0.6');
        $header->set('X-Requested-With', 'Ext.basex');
        $header->set('Host', '202.96.18.90:9001');
        $header->set('Origin', 'http://202.96.18.90:9001');
        $header->set('Connection', 'keep-alive');

        $userAgents = [
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
            'Mozilla/5.0 (Windows NT 5.2) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.122 Safari/534.30',
            'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET4.0E; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET4.0E; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727) ',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E)',
            'Opera/9.80 (Windows NT 5.1; U; zh-cn) Presto/2.9.168 Version/11.50',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET4.0E; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C)',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; ) AppleWebKit/534.12 (KHTML, like Gecko) Maxthon/3.0 Safari/534.12',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; TheWorld)',
        ];
        $userAgent = $userAgents[mt_rand(0,12)];
        $header->set('User-Agent', $userAgent);

        $cookies = [
            'ys-eastCmp=o%3Acollapsed%3Db%253A0; JSESSIONID=0000f4ltQvWQbrRFL4YAEF_d72v:-1; BIGipServerly_was_9001=1565150596.31267.0000; ys-thmodel=o%3Acollapsed%3Db%253A1; ys-queryComboCmpId=s%3AcomboCmp1473652727377; ys-queryFormPnlId=s%3AidFormPanel1473652727381; ys-tabPnlId=s%3AtabPnlId1473652727425; ys-tabId01=s%3AtabId011473652727430; ys-tabId03=s%3AtabId031473652727430',
            'ys-queryComboCmpId=s%3AcomboCmp1473652811163; ys-queryFormPnlId=s%3AidFormPanel1473652811163; ys-tabPnlId
=s%3AtabPnlId1473652811196; ys-tabId01=s%3AtabId011473652811199; ys-tabId03=s%3AtabId031473652811200
; JSESSIONID=0000kod8MX3iemaflr8LVxQx3hM:-1; BIGipServerly_was_9001=1565150596.31267.0000',
            'ys-queryComboCmpId=s%3AcomboCmp1473389367361; ys-queryFormPnlId=s%3AidFormPanel1473389367366; ys-tabPnlId=s%3AtabPnlId1473389367417; ys-tabId01=s%3AtabId011473389367421; ys-tabId03=s%3AtabId031473389367422; BIGipServerly_was_9001=1565150596.31267.0000; JSESSIONID=0000-jdjueORpLlTq0kUqHLsnoA:-1',
            'JSESSIONID=0000otq289BfKjuMzqYYgGK7bFe:-1; BIGipServerly_was_9001=1565150596.31267.0000; ys-queryComboCmpId=s%3AcomboCmp1473653127278; ys-queryFormPnlId=s%3AidFormPanel1473653127282; ys-tabPnlId=s%3AtabPnlId1473653127409; ys-tabId01=s%3AtabId011473653127428; ys-tabId03=s%3AtabId031473653127428',
        ];
        $cookie = urldecode($cookies[mt_rand(0,3)]);
        $header->set('Cookie', $cookie);

        $request->setHeaders($header);
//        $this->pubRequest = $request;
        return $request;
    }

    protected function search($key = '十里堡东里128楼')
    {
        $request = new Request();
        $searchUrl = 'http://202.96.18.90:9001/NRMS/solrEngine.do?dispatch=comboBoxList&queryFlag=1110';
        $request->setUrl($searchUrl);
        $request->setMethod('post');
        $request->addPostField('key', $key);
        $request->addPostField('staff_id', 'null');
        $request->addPostField('params', 'print');
        $response = $this->getClient()->exec($request);
        $key = '';
        if ($response->status == 200) {
            $t = strip_tags($response->body);
            $match = [];
            preg_match_all("/key':'([0-9]+)','briefname/",$t,$match);
            if (isset($match[1][0])) {
                $key = $match[1][0];
            }
        }
        Jeen::echoln('search request:'.$response->statusText.'|key:['.$key.']');
        return $key;
    }

    protected function doSearch($key = '351964')
    {
        $sdoUrl = 'http://202.96.18.90:9001/NRMS/search.do?dispatch=insertQZJHXTJInfo';
        unset($request);
        $request = new Request();
        $request->setUrl($sdoUrl);
        $request->setMethod('post');
        $params = [
            'fldcheckinfo' => "$key",
            'fldfrom' => '0'
        ];
        foreach ($params as $k => $v) {
            $request->addPostField($k, $v);
        }
        $contentLength = strlen(http_build_query($params));
        $request->setHeader('Content-Length', "$contentLength");
        $request->setHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        $request->setHeader('Connection', 'keep-alive');
        $response = $this->getClient()->exec($request);
        
        Jeen::echoln('do Search:' . $response->statusText);

        return $response->status == 200 ? true : false;
    }

    protected function getLYbak($key = '351964')
    {
        $LYurl = 'http://202.96.18.90:9001/NRMS/solrEngine.do?dispatch=entityList&queryFlag=1000&entityTag=001017036&staff_id=null';
        $request = new Request();
        $request->setUrl($LYurl);
        $request->setMethod('post');
        $params = [
            'start' => '0',
            'limit' => '25',
            'sort' => 'fldtag',
            'dir' => 'DESC',
            'key' => "$key",
            'staff_id' => 'null',
            'params' => 'print',
        ];
        foreach ($params as $k => $v) {
            $request->addPostField($k, $v);
        }
        $contentLength = strlen(http_build_query($params));
        $request->setHeader('Content-Length', "$contentLength");
        $request->setHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        $request->setHeader('Connection', 'keep-alive');
        $response = $this->getClient('open')->exec($request);
        Jeen::echoln('get LY :'.$response->statusText);

        return $response->status == 200 ? $response : false;
    }

    /**
     * @param string $fldid
     * @return bool|\hightman\http\Response|\hightman\http\Response[]
     */
    protected function getLY($fldid = '12062005106336346')
    {
        $LYurl = "http://202.96.18.90:9001/NRMS/search.do?dispatch=getEntityInfo&entityTag=001017013&fldid=$fldid&_dc=1473212978850";
        $request = new Request($LYurl,'get');
        $response = $this->getClient()->exec($request);
        Jeen::echoln('get LY :'.$response->statusText);

        return $response->status == 200 ? $response : false;
    }

    /**
     * 获取单元信息
     * @param string $briefname
     * @return bool|\yii\httpclient\Response
     * @throws \yii\httpclient\Exception
     */
    protected function queryInfoDY($briefname = '040303351964')
    {
        $url = 'http://202.96.18.90:9001/NRMS/ci.do?dispatch=queryInfoDY';
        $request = $this->getRequest();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'briefname' => "$briefname",
            'type' => '0'
        ];
        $request->setContent(http_build_query($params));
        $contentLength = strlen(http_build_query($params));
        $request->addHeaders([
            'Content-Length' => "$contentLength",
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ]);
        $response = $request->send();
        return $response->getStatusCode() == 200 ? $response : false;
    }

    /**
     * 获取楼层信息
     * @param string $briefname
     * @param string $dyh
     * @return bool|\yii\httpclient\Response
     * @throws \yii\httpclient\Exception
     */
    protected function queryInfoLC($briefname = '040303351964', $dyh = '1')
    {
        $url = 'http://202.96.18.90:9001/NRMS/ci.do?dispatch=queryInfoLC';
        $request = $this->getRequest();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'briefname' => "$briefname",
            'type' => '0',
            'dyh' => "$dyh"
        ];
        $request->setContent(http_build_query($params));
        $contentLength = strlen(http_build_query($params));
        $request->addHeaders([
            'Content-Length' => "$contentLength",
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ]);
        $response = $request->send();
        return $response->getStatusCode() == 200 ? $response : false;
    }

    /**
     * 获取房间信息
     * @param string $briefname
     * @param string $dyh
     * @param string $lch
     * @return bool|\yii\httpclient\Response
     * @throws \yii\httpclient\Exception
     */
    protected function queryInfoFJ($briefname = '040303351964', $dyh = '1', $lch = '22')
    {
        $url = 'http://202.96.18.90:9001/NRMS/ci.do?dispatch=queryInfoFJ';
        $request = $this->getRequest();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'briefname' => "$briefname",
            'type' => '0',
            'dyh' => "$dyh",
            'lch' => "$lch"
        ];
        $request->setContent(http_build_query($params));
        $contentLength = strlen(http_build_query($params));
        $request->addHeaders([
            'Content-Length' => "$contentLength",
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ]);
        $response = $request->send();
        return $response->getStatusCode() == 200 ? $response : false;
    }
    
    /**
     * 获取房间报装地址
     * @param string $roomid
     * @return bool|\yii\httpclient\Response
     * @throws \yii\httpclient\Exception
     */
    protected function queryInfoBZDZ($roomid = '040303351964_6016707_0')
    {
        $url = 'http://202.96.18.90:9001/NRMS/ci.do?dispatch=queryInfoBZDZ';
        $request = $this->getRequest();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'roomid' => "$roomid", 
        ];
        $request->setContent(http_build_query($params));
        $contentLength = strlen(http_build_query($params));
        $request->addHeaders([
            'Content-Length' => "$contentLength",
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ]);
        $response = $request->send();
        return $response->getStatusCode() == 200 ? $response : false;
    }

    /**
     * @param int $start
     * @param int $limit  25  50 75 100 125 150 175 200 500  1000  2k  3k  4k
     * @return bool|\hightman\http\Response|\hightman\http\Response[]
     */

    /**
     * 基础应用  楼宇资源  楼宇
     * @param int $start
     * @param int $limit
     * @return bool|\hightman\http\Response|\hightman\http\Response[]
     */
    protected function getEntityList1($start=0, $limit=25)
    {
        $url = 'http://202.96.18.90:9001/NRMS/entity.do?dispatch=getEntityList&entityTag=001001002001&filter=true~true~false~120660073116760136209~120680093116760028631~true~true&parameter=null';

        $request = new Request();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'start' => "$start",
            'limit' => "$limit"
        ];
        foreach ($params as $k => $v) {
            $request->addPostField($k, $v);
        }
        $contentLength = strlen(http_build_query($params));
        $request->setHeader('Content-Length', "$contentLength");
        $request->setHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        $request->setHeader('Connection', 'keep-alive');
        $response = $this->getClient()->exec($request);
        Jeen::echoln('get entity list(louyu list) :'.$response->statusText);

        return $response->status == 200 ? $response : false;

    /*
        //楼宇列表  4756 已抓取完成
        $lys = $this->getEntityList1(5000,1000);
        if ($lys->status == 200) {
            $t = $this->xmlToArr($lys->body);
//            Jeen::echoln($t);exit();
            if (isset($t['b001001002001d']) && is_array($t['b001001002001d'])) {
                foreach ($t['b001001002001d'] as $lyItem) {
//                    Jeen::echoln($lyItem); exit();
                    $row = new \app\models\lt\FldList();
                    $row->entity_tag = '001001002001';
                    $row->filter = 'true~true~false~120660073116760136209~120680093116760028631~true~true';
                    $row->setAttributes($lyItem);
                    if (!$row->save()) {
                        Jeen::echoln($row->getErrors());
                        exit();
                    } else {
                        echo $row->id. '| ';
                    }
                }
            }
        }

    */
    }

    /**
     * 基础应用  区域管理   网格
     * @param int $start
     * @param int $limit
     * @return bool|\hightman\http\Response|\hightman\http\Response[]
     */
    protected function getEntityList2($start=0, $limit=25)
    {
        $url = 'http://202.96.18.90:9001/NRMS/entity.do?dispatch=getEntityList&entityTag=001001001004&filter=true~true~true~120660073116760136209~120680093116760019138~false~false&parameter=null';

        $request = new Request();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'start' => "$start",
            'limit' => "$limit"
        ];
        foreach ($params as $k => $v) {
            $request->addPostField($k, $v);
        }
        $contentLength = strlen(http_build_query($params));
        $request->setHeader('Content-Length', "$contentLength");
        $request->setHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        $request->setHeader('Connection', 'keep-alive');
        $response = $this->getClient()->exec($request);
        Jeen::echoln('get entity list(louyu list) :'.$response->statusText);

        return $response->status == 200 ? $response : false;

    /*
        $sis = $this->getEntityList2(1000,1000);
        if ($sis->status == 200) {
            $t = $this->xmlToArr($sis->body);
//            Jeen::echoln($t);exit();
            if (isset($t['b001001001004d']) && is_array($t['b001001001004d'])) {
                foreach ($t['b001001001004d'] as $item) {
//                    Jeen::echoln($lyItem); exit();
                    $row = new \app\models\lt\BaseAreaWg();
                    $row->entity_tag = '001001001004';
                    $row->filter = 'true~true~true~120660073116760136209~120680093116760019138~false~false';
                    $row->setAttributes($item);
                    if (!$row->save()) {
                        Jeen::echoln($row->getErrors());
                        exit();
                    } else {
                        echo $row->id. '| ';
                    }
                }
            }
        }
    */

    }

    /**
     * 基础应用  公众网格资源  公众网格资源管理
     * @param int $start
     * @param int $limit
     * @return bool|\hightman\http\Response|\hightman\http\Response[]
     */
    protected function getEntityList3($start=0, $limit=25)
    {
        $url = 'http://202.96.18.90:9001/NRMS/entity.do?dispatch=getEntityList&entityTag=001017029&filter=true~true~true~120660073116760136209~120680093116760089961~true~true&parameter=null';

        $request = new Request();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'start' => "$start",
            'limit' => "$limit"
        ];
        foreach ($params as $k => $v) {
            $request->addPostField($k, $v);
        }
        $contentLength = strlen(http_build_query($params));
        $request->setHeader('Content-Length', "$contentLength");
        $request->setHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        $request->setHeader('Connection', 'keep-alive');
        $response = $this->getClient()->exec($request);
        Jeen::echoln('get entity list(louyu list) :'.$response->statusText);

        return $response->status == 200 ? $response : false;

    /*
        $sis = $this->getEntityList3(11000,1000);
        if ($sis->status == 200) {
            $t = $this->xmlToArr($sis->body);
//            Jeen::echoln($t);exit();
            if (isset($t['b001017029d']) && is_array($t['b001017029d'])) {
                foreach ($t['b001017029d'] as $item) {
//                    Jeen::echoln($lyItem); exit();
                    $row = new \app\models\lt\PubWgList();
                    $row->entity_tag = '001017029';
                    $row->filter = 'true~true~true~120660073116760136209~120680093116760089961~true~true';
                    $row->setAttributes($item);
                    if (!$row->save()) {
                        Jeen::echoln($row->getErrors());
                        exit();
                    } else {
                        echo $row->id. '| ';
                    }
                }
            }
        }
    */

    }

    /**
     * 基础应用  厅渠管理  沃厅店
     * @param int $start
     * @param int $limit
     * @return bool|\hightman\http\Response|\hightman\http\Response[]
     */
    protected function getEntityList4($start=0, $limit=25)
    {
        $url = 'http://202.96.18.90:9001/NRMS/entity.do?dispatch=getEntityList&entityTag=001017051&filter=false~true~false~120660073116760136209~120680093116761974300~false~true&parameter=IFWTD=(1)';

        $request = new Request();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'start' => "$start",
            'limit' => "$limit"
        ];
        foreach ($params as $k => $v) {
            $request->addPostField($k, $v);
        }
        $contentLength = strlen(http_build_query($params));
        $request->setHeader('Content-Length', "$contentLength");
        $request->setHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        $request->setHeader('Connection', 'keep-alive');
        $response = $this->getClient()->exec($request);
        Jeen::echoln('get entity list(louyu list) :'.$response->statusText);

        return $response->status == 200 ? $response : false;

    /*
        $sis = $this->getEntityList4(26000,1000);
        if ($sis->status == 200) {
            $t = $this->xmlToArr($sis->body);
//            Jeen::echoln($t);exit();
            if (isset($t['b001017051d']) && is_array($t['b001017051d'])) {
                foreach ($t['b001017051d'] as $item) {
//                    Jeen::echoln($lyItem); exit();
                    $row = new \app\models\lt\QdWt();
                    $row->entity_tag = '001017051';
                    $row->filter = 'false~true~false~120660073116760136209~120680093116761974300~false~true';
                    $row->setAttributes($item);
                    if (!$row->save()) {
                        Jeen::echoln($row->getErrors());
                        exit();
                    } else {
                        echo $row->id. '| ';
                    }
                }
            }
        }
    */

    }

    /**
     * 基础应用  厅渠管理  沃家店管理
     * @param int $start
     * @param int $limit
     * @return bool|\hightman\http\Response|\hightman\http\Response[]
     */
    protected function getEntityList5($start=0, $limit=25)
    {
        $url = 'http://202.96.18.90:9001/NRMS/entity.do?dispatch=getEntityList&entityTag=001017052&filter=false~true~false~120660073116760136209~120680093116761974341~false~true&parameter=IFWTD=(0)';

        $request = new Request();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'start' => "$start",
            'limit' => "$limit"
        ];
        foreach ($params as $k => $v) {
            $request->addPostField($k, $v);
        }
        $contentLength = strlen(http_build_query($params));
        $request->setHeader('Content-Length', "$contentLength");
        $request->setHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        $request->setHeader('Connection', 'keep-alive');
        $response = $this->getClient()->exec($request);
        Jeen::echoln('get entity list(louyu list) :'.$response->statusText);

        return $response->status == 200 ? $response : false;

    /*
        $sis = $this->getEntityList5(1000,1000);
        if ($sis->status == 200) {
            $t = $this->xmlToArr($sis->body);
//            Jeen::echoln($t);exit();
            if (isset($t['b001017052d']) && is_array($t['b001017052d'])) {
                foreach ($t['b001017052d'] as $item) {
//                    Jeen::echoln($lyItem); exit();
                    $row = new \app\models\lt\QdWj();
                    $row->entity_tag = '001017052';
                    $row->filter = 'false~true~false~120660073116760136209~120680093116761974341~false~true';
                    $row->setAttributes($item);
                    if (!$row->save()) {
                        Jeen::echoln($row->getErrors());
                        exit();
                    } else {
                        echo $row->id. '| ';
                    }
                }
            }
        }
    */

    }


    //抓取联通楼宇列表信息
    public function actionLtly()
    { 
        $limit = 25;

        $startKey = 'LtLY:Start:Number';
        if (\Yii::$app->getCache()->get($startKey)) {
            $start = intval(\Yii::$app->getCache()->get($startKey));
        } else {
            $start = 0;
        }
        Jeen::echoln('start: '.$start.' | limit: '.$limit);
        if (!$this->confirm('sure?')) {
            Jeen::echoln('bye~');
            exit();
        }
        $ret = $this->ltlyList($start, $limit);
        $flag = $ret ? true : false;
        while ($flag) {
            $res = $this->xmlToArr($ret->getContent());
            if (isset($res['b001001002001d']) && is_array($res['b001001002001d'])) {
                foreach ($res['b001001002001d'] as $item) {
                    echo '.';
//                    $lyItem = $this->handleLy($item);
//                    if (!$lyItem) continue;
//                    $row = new \app\models\lt\LyList();
//                    $row->setAttributes($lyItem, false);
//                    $row->loadDefaultValues();
//                    try {
//                        if (!$row->save()) {
//                            Jeen::echoln($row->getErrors());
//                            Jeen::echoln($lyItem);
//                            exit();
//                        } else {
//                            echo $row->id. '| ';
//                        }
//                    } catch (\Exception $e) {
//                        echo '.';
//                        continue;
//                    }
                }
            }
            $start += $limit;
            \Yii::$app->getCache()->set($startKey, $start);
            if (count($res['b001001002001d']) == $limit) {
                $ret = $this->ltlyList($start, $limit);
            } else {
                $flag = false;
            }
        }
        Jeen::echoln('done');
    }
    protected function ltlyList($start,$limit=1000)
    {
        $url = 'http://202.96.18.90:9001/NRMS/entity.do?dispatch=getEntityList&entityTag=001001002001&filter=null&parameter=null';
        $request = $this->getRequest();
        $request->setUrl($url);
        $request->setMethod('post');
        $params = [
            'start' => "$start",
            'limit' => "$limit"
        ];
        $request->setContent(http_build_query($params));
        $contentLength = strlen(http_build_query($params));
        $request->addHeaders([
            'Content-Length' => "$contentLength",
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ]);
        $response = $request->send();
        Jeen::echoln("get lian tong lou yu list from $start limit $limit : ".$response->getIsOk());

        return $response->getStatusCode() == 200 ? $response : false;
    }
    protected function handleLy($item)
    {
        if (isset($item['fldcbriefname'])) {
            $t = explode('%#', $item['fldcbriefname']);
            if (isset($t[1])) {
                $item['fldcbriefname'] = $t[1];
            } else {
                return false;
            }
        }
        if (isset($item['fldmapinfo'])) {
            unset($item['fldmapinfo']);
        }
        if (isset($item['swapcodeinfo'])) {
            unset($item['swapcodeinfo']);
        }
        if (isset($item['fldprjinfo'])) {
            unset($item['fldprjinfo']);
        } 
        if (isset($item['correctinfo'])) {
            unset($item['correctinfo']);
        }
        if (isset($item['louyumingxi'])) {
            unset($item['louyumingxi']);
        }
        if (isset($item['fldex70'])) {
            $item['fldex70'] = intval($item['fldex70']);
        }
        if (isset($item['fldex6'])) {
            $item['fldex6'] = intval($item['fldex6']);
        }
        return $item;
    }

    //分析联通楼宇详细信息
    public function actionLtlyinfo()
    {
        $offset = 0;
        $limit = 2000;
        $flag = true;
        while ($flag) {
            Jeen::echoln("$offset to ".($offset+$limit)." start ...");
            $rows = LyList::find()
                ->select([
                    'fldid','fldcbriefname','fldcname','xqname','fldcaddrss','fldstreetno','flddoorno',
                    'fldregionid','fldareaid','flditype',
                    'fldex1', 'fldex6', 'fldex8','fldex9','fldex10','fldex3','areamanage',
                    'flddotime', 'fldflong', 'fldflat', 'fldreqip',
                    'zjname', 'zjphone',
                ])
                ->asArray()
                ->limit($limit)
                ->offset($offset)
                ->all();
            if (count($rows) < 1) {
                $flag = false;
                continue;
            }
            $offset += $limit;
            foreach ($rows as $row) {
                $info = new LyInfo();
                $info->fldid = intval($row['fldid']);
                $info->fldcbriefname = strval($row['fldcbriefname']);
                $info->info = Json::encode($row);
                try {
                    if ($info->save()) {
                        echo 'y';
                    } else {
                        echo 'n';
                    }
                } catch (\Exception $e) {
                    echo '-';
                }
            }
        }
        Jeen::echoln('done');
    }

    //抓取联通楼宇单元信息
    public function actionLtdy()
    {
        $minId = 1459;
        $mindy = 3;
        $minlc = 6;
        $minfj = 312;
        $offset = 0;
        $limit = 10;
        $flag = true;
        while ($flag) {
            Jeen::echoln("$offset to ".($offset+$limit)." start ...");
            $rows = LyList::find()
                ->select(['id','fldid','fldcbriefname'])
                ->where('`id`>='.$minId)
                ->asArray()
                ->limit($limit)
                ->offset($offset)
                ->all();
            if (count($rows) < 1) {
                $flag = false;
                continue;
            }
            $offset += $limit;
            foreach ($rows as $k=>$row) {
                $dys = $this->queryInfoDY($row['fldcbriefname']);
                sleep(3);
//                Jeen::echoln($dys->getContent());exit();
                if (!$dys) continue;
                $dys = Json::decode($dys->getContent());
                if (!isset($dys['result'])) continue;
                $dys = ArrayHelper::getColumn($dys['result'], 'dyh');
                if ($dys) {
                    foreach ($dys as $dyh) {
                        if ($row['id'] == $minId && $dyh < $mindy) continue;
                        $lcs = $this->queryInfoLC($row['fldcbriefname'], $dyh);
                        sleep(3);
                        if (!$lcs) continue;
                        $lcs = Json::decode($lcs->getContent());
                        if (!isset($lcs['result'])) continue;
                        $lcs = ArrayHelper::getColumn($lcs['result'], 'lch');
                        if ($lcs) {
                            foreach ($lcs as $lch) {
                                if ($row['id'] == $minId && $dyh == $mindy && $lch < $minlc) continue;
                                $fjs = $this->queryInfoFJ($row['fldcbriefname'], $dyh, $lch);
                                sleep(1);
                                if (!$fjs) continue;
                                $fjs = Json::decode($fjs->getContent());
                                if (!isset($fjs['result'])) continue;
                                $fjs = ArrayHelper::map($fjs['result'], 'fjh', 'fjid');
                                if ($fjs) {
                                    foreach ($fjs as $fjh => $fjid) {
                                        if ($row['id'] == $minId && $dyh == $mindy && $lch == $minlc && $fjh<$minfj) continue;
                                        $bzdz = '';//$this->queryInfoBZDZ("{$row['fldcbriefname']}_{$fjid}_0");
                                        if ($bzdz) {
                                            $bzdz = $bzdz->getContent();
                                        } else {
                                            $bzdz = '';
                                        }
                                        echo " {$row['id']} : $dyh - $lch - $fjh => $fjid : ".date("y-m-d H:i:s - ");
                                        try {
                                            $info = new LyDylcfj();
                                            $info->fldid = intval($row['fldid']);
                                            $info->fldcbriefname = strval($row['fldcbriefname']);
                                            $info->dyh = intval($dyh);
                                            $info->lch = intval($lch);
                                            $info->fjh = strval($fjh);
                                            $info->fjid = strval($fjid);
                                            $info->bzdz = strval($bzdz);
                                            $info->roomid = "{$row['fldcbriefname']}_{$fjid}_0";
                                            if ($info->save()) {
                                                echo 'y-yes';
                                            } else {
                                                echo 'n-no';
                                            }
                                        } catch (\Exception $e) {
                                            echo '--exist';
                                        }
                                        echo PHP_EOL;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        Jeen::echoln('done');
    }


    /**
     * Decompress data
     * @param string $data compressed string
     * @return string result string
     */
    public function gzdecode($data)
    {
        return gzinflate(substr($data, 10, -8));
    }


    protected function xmlToArr($xmlStr)
    {
        $xmlStr = trim(strval($xmlStr));
        $ret = @ json_decode(json_encode((array) simplexml_load_string($xmlStr)), true);
        return $ret;
    }
}
