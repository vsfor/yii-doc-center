<?php
namespace app\components;

use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\Response;
use yii\helpers\StringHelper;

class RestCurl
{
    public $client;
    public $request;
    public $response;

    public $requestUrl;
    public $headers = [];
    public $options = [
        'timeout' => 3,
        'maxRedirects' => 3
    ];

    public $getParams = [];
    public $postParams = [];
    public $paramType = 'form';

    public function __construct($url='')
    {
        $this->client = new Client();
        $this->request = new Request();
        $this->requestUrl = $url;
    }
    
    public function setRequestUrl($url) {
        $this->requestUrl = $url;
    }

    public function call()
    {
        if ($this->options) {
            $this->request->addOptions($this->options);
        }
        if ($this->headers) {
            $this->request->addHeaders($this->headers);
        }
        if ($this->getParams) {
            if (strpos($this->requestUrl,'?')) {
                $this->requestUrl .= '&'.http_build_query($this->getParams);
            }
        }
        if ($this->request->getMethod() != 'get' &&$this->postParams) {
            if ($this->paramType == 'json') {
                $this->request->setContent(json_encode($this->postParams));
            } else {
                $this->request->setContent(http_build_query($this->postParams));
            }
        }
        $this->request->setUrl($this->requestUrl);
        $this->response = $this->client->send($this->request);
        return $this;
    }

    public function handleHeaders(array $headers=[])
    {
        foreach ($headers as $item) {
            $this->headers[strtolower($item['key'])] = $item['value'];
        }
        return $this;
    }

    public function handleParams(array $params=[])
    {
        foreach ($params as $item) {
            $paramType = strtolower($item['type']);
            if (!in_array($paramType, ['get','post'])) continue;
            if (StringHelper::endsWith($item['key'],'[]')) {
                $this->{$paramType.'Params'}[$item['key']][] = $item['value'];
            } else {
                $this->{$paramType.'Params'}[$item['key']] = $item['value'];
            }
        }
        return $this;
    }

    public function handleMethodType($method='get', $type='form')
    {
        $this->request->setMethod($method);
        $this->paramType = strtolower($type);
    }

    public function handleRest($info)
    {
        
    }
    
}