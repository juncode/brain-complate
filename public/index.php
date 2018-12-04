<?php
// +----------------------------------------------------------------------
// | 微信小程序 模块入口文件
// +----------------------------------------------------------------------

/* 绑定模块 */
define('BIND_MODULE', 'wxapp');

require __DIR__.'/define.php';

/* wxapp sessionid不用cookie传输 */
ini_set('session.use_cookies', 0);


/* 加载框架引导文件 */
require __DIR__.'/../vendor/thinkphp/start.php';
