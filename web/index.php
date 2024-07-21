<?php
//phpinfo();die();
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('WEB_PATH') or define('WEB_PATH', __DIR__);
defined('BASE_PATH') or define('BASE_PATH', dirname(__DIR__));

require(BASE_PATH . '/vendor/autoload.php');
require(BASE_PATH . '/vendor/yiisoft/yii2/Yii.php');

$config = require(BASE_PATH . '/config/web.php');

(new yii\web\Application($config))->run();
