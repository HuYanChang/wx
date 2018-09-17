<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17
 * Time: 12:43
 */

//应用目录为当前目录
define('APP_PATH', __DIR__);

//开启调试
define('APP_DEBUG', true);

//加载框架文件
require APP_PATH.'/wxphp/Wxphp.php';

//加载配置文件
$config = require  APP_PATH.'/config/config.php';

//实例化框架类
(new wxphp\Wxphp($config))->run();