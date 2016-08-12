<?php
//phpinfo();die();
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('WEB_PATH') or define('WEB_PATH', __DIR__);

require(__DIR__ . '/_protected/vendor/autoload.php');
require(__DIR__ . '/_protected/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/_protected/config/web.php');

(new yii\web\Application($config))->run();
