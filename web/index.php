<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', TRUE);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
