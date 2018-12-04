<?php

/* 根目录 */
define('ROOT_PATH', __DIR__ . '/../');
/* 定义应用目录 */
define('APP_PATH', ROOT_PATH . 'app/');
/* 定义配置文件目录和应用目录同级 */
define('CONF_PATH', __DIR__ . '/../config/');

/*
 * 定义项目运行环境种类
 */
define('APP_ENV_PRODUCTION', 'production');
define('APP_ENV_LOCAL', 'local');
define('APP_ENV_TEST', 'test');

/*
 * 定义当前运行环境
 */
$_envfile = ROOT_PATH . 'env.php';
if (is_file($_envfile) && ($_env = require $_envfile) && is_string($_env)) {
    define('APP_ENV', $_env);
} else {
    define('APP_ENV', APP_ENV_PRODUCTION);
}
/* 是否是生产模式 */
define('APP_ENV_IS_PRODUCTION', APP_ENV === APP_ENV_PRODUCTION);
unset($_env, $_envfile);

/**
 * 追加当前运行环境的对应配置项
 *
 * @param array  $config     原始配置数组
 * @param string $configName 配置项名称
 *
 * @return array
 */
function append_env_config($config, $configName)
{
    if (APP_ENV !== APP_ENV_PRODUCTION) {
        $file = CONF_PATH . 'env' . DS . $configName . '.' . APP_ENV . '.php';

        if (is_file($file)) {
            $append = require $file;
            $config = array_replace_recursive($config, $append);
        }
    }

    return $config;
}
