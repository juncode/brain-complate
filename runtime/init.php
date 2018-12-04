<?php 
\think\Config::set(array (
  'app_host' => '',
  'app_debug' => true,
  'app_trace' => false,
  'app_status' => '',
  'app_multi_module' => true,
  'auto_bind_module' => false,
  'root_namespace' => 
  array (
  ),
  'extra_file_list' => 
  array (
    0 => ROOT_PATH . 'vendor/thinkphp/helper.php',
    1 => ROOT_PATH . 'helper/common.php',
  ),
  'default_return_type' => 'json',
  'default_ajax_return' => 'json',
  'default_jsonp_handler' => 'jsonpReturn',
  'var_jsonp_handler' => 'callback',
  'default_timezone' => 'PRC',
  'lang_switch_on' => false,
  'default_filter' => '',
  'default_lang' => 'zh-cn',
  'class_suffix' => false,
  'controller_suffix' => false,
  'default_module' => '',
  'deny_module_list' => 
  array (
    0 => 'common',
  ),
  'default_controller' => 'Index',
  'default_action' => 'index',
  'default_validate' => '',
  'empty_controller' => 'Error',
  'use_action_prefix' => false,
  'action_suffix' => '',
  'controller_auto_search' => false,
  'var_pathinfo' => 's',
  'pathinfo_fetch' => 
  array (
    0 => 'ORIG_PATH_INFO',
    1 => 'REDIRECT_PATH_INFO',
    2 => 'REDIRECT_URL',
  ),
  'pathinfo_depr' => '/',
  'https_agent_name' => '',
  'url_html_suffix' => 'html',
  'url_common_param' => false,
  'url_param_type' => 0,
  'url_route_on' => true,
  'route_config_file' => 
  array (
    0 => 'route',
  ),
  'route_complete_match' => false,
  'url_route_must' => false,
  'url_domain_deploy' => false,
  'url_domain_root' => '',
  'url_convert' => true,
  'url_controller_layer' => 'controller',
  'var_method' => '_method',
  'var_ajax' => '_ajax',
  'var_pjax' => '_pjax',
  'request_cache' => false,
  'request_cache_expire' => NULL,
  'request_cache_except' => 
  array (
  ),
  'template' => 
  array (
    'type' => 'Think',
    'view_path' => '',
    'view_suffix' => 'html',
    'view_depr' => '/',
    'tpl_begin' => '{',
    'tpl_end' => '}',
    'taglib_begin' => '{',
    'taglib_end' => '}',
  ),
  'view_replace_str' => 
  array (
  ),
  'dispatch_success_tmpl' => ROOT_PATH . 'vendor/thinkphp/tpl/dispatch_jump.tpl',
  'dispatch_error_tmpl' => ROOT_PATH . 'vendor/thinkphp/tpl/dispatch_jump.tpl',
  'exception_tmpl' => ROOT_PATH . 'vendor/thinkphp/tpl/think_exception.tpl',
  'error_message' => '页面错误！请稍后再试～',
  'show_error_msg' => false,
  'exception_handle' => 'V5\\Library\\Exception\\ExceptionHandle',
  'record_trace' => false,
  'log' => 
  array (
    'type' => 'File',
    'path' => ROOT_PATH . 'runtime/log/',
    'level' => 
    array (
    ),
  ),
  'trace' => 
  array (
    'type' => 'Html',
  ),
  'cache' => 
  array (
    'type' => 'File',
    'path' => ROOT_PATH . 'runtime/cache/',
    'prefix' => '',
    'expire' => 0,
  ),
  'session' => 
  array (
    'name' => '_SSID_',
    'id' => '',
    'var_session_id' => 'PHPS',
    'httponly' => true,
    'prefix' => 'NOVEL',
    'type' => 'redis',
    'host' => '127.0.0.1',
    'port' => 6379,
    'expire' => 259200,
    'auto_start' => false,
  ),
  'cookie' => 
  array (
    'prefix' => '',
    'expire' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => '',
    'setcookie' => true,
  ),
  'database' => 
  array (
    'type' => 'mysql',
    'dsn' => '',
    'hostname' => '47.93.137.217',
    'database' => 'wangzhe',
    'username' => 'root',
    'password' => 'CSFS@2018',
    'hostport' => '',
    'params' => 
    array (
    ),
    'charset' => 'utf8mb4',
    'prefix' => '',
    'debug' => true,
    'deploy' => 0,
    'rw_separate' => false,
    'master_num' => 1,
    'slave_no' => '',
    'fields_strict' => true,
    'resultset_type' => 'collection',
    'auto_timestamp' => false,
    'datetime_format' => false,
    'sql_explain' => false,
  ),
  'paginate' => 
  array (
    'type' => 'bootstrap',
    'var_page' => 'page',
    'list_rows' => 15,
  ),
  'redis' => 
  array (
    'session' => 
    array (
      'host' => '127.0.0.1',
      'port' => 6379,
      'database' => 0,
      'password' => NULL,
      'persistent' => true,
    ),
  ),
  'reward' => 
  array (
    'config_key_prefix' => 'wechat_as:app_config',
    'signature_passwod' => '4235674dfsafdhj32fe',
    'mini_program' => 
    array (
    ),
    'easywechat' => 
    array (
      'app_id' => 'wx5460cbebb6f5f9e4',
      'secret' => 'b190cc624f29fe7cb905368ca3d03a08',
      'token' => 'xxmixiangjianwen1',
      'debug' => false,
      'log' => 
      array (
        'level' => 'emergency',
        'file' => ROOT_PATH . 'runtime/wechat/easywechat.log',
      ),
      'oauth' => 
      array (
        'scopes' => 
        array (
          0 => 'snsapi_userinfo',
        ),
      ),
      'payment' => 
      array (
        'merchant_id' => '1497429332',
        'key' => 'nJzbBqYGk4QEDkT7rskQF4bTqLwQqVUG',
      ),
      'guzzle' => 
      array (
      ),
    ),
  ),
  'wechat' => 
  array (
    'config_key_prefix' => 'wechat_as:app_config',
    'signature_passwod' => '4235674dfsafdhj32fe',
    'mini_program' => 
    array (
      'app_id' => 'wxd84c8fafe419cb93',
      'secret' => '4d7cc0ac11b1183d87416abcc13d133f',
    ),
    'easywechat' => 
    array (
      'app_id' => 'wxd84c8fafe419cb93',
      'secret' => '4d7cc0ac11b1183d87416abcc13d133f',
      'debug' => false,
      'log' => 
      array (
        'level' => 'emergency',
        'file' => ROOT_PATH . 'runtime/wechat/easywechat.log',
      ),
      'oauth' => 
      array (
        'scopes' => 
        array (
          0 => 'snsapi_userinfo',
        ),
      ),
      'payment' => 
      array (
      ),
      'guzzle' => 
      array (
      ),
    ),
  ),
));