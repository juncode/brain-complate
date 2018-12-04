<?php 
return array (
  'get' => 
  array (
    'test/:action' => 
    array (
      'rule' => 'test/:action',
      'route' => 'TestController/get:action',
      'var' => 
      array (
        'action' => 1,
      ),
      'option' => 
      array (
      ),
      'pattern' => 
      array (
      ),
    ),
    'user/info' => 
    array (
      'rule' => 'user/info',
      'route' => 'UserController/user',
      'var' => 
      array (
      ),
      'option' => 
      array (
      ),
      'pattern' => 
      array (
      ),
    ),
  ),
  'post' => 
  array (
    'test/:action' => 
    array (
      'rule' => 'test/:action',
      'route' => 'TestController/post:action',
      'var' => 
      array (
        'action' => 1,
      ),
      'option' => 
      array (
      ),
      'pattern' => 
      array (
      ),
    ),
    'user/login' => 
    array (
      'rule' => 'user/login',
      'route' => 'UserController/login',
      'var' => 
      array (
      ),
      'option' => 
      array (
        'complete_match' => true,
      ),
      'pattern' => 
      array (
      ),
    ),
    'user/update' => 
    array (
      'rule' => 'user/update',
      'route' => 'UserController/updateInfo',
      'var' => 
      array (
      ),
      'option' => 
      array (
      ),
      'pattern' => 
      array (
      ),
    ),
  ),
  'put' => 
  array (
    'test/:action' => 
    array (
      'rule' => 'test/:action',
      'route' => 'TestController/put:action',
      'var' => 
      array (
        'action' => 1,
      ),
      'option' => 
      array (
      ),
      'pattern' => 
      array (
      ),
    ),
  ),
  'delete' => 
  array (
    'test/:action' => 
    array (
      'rule' => 'test/:action',
      'route' => 'TestController/delete:action',
      'var' => 
      array (
        'action' => 1,
      ),
      'option' => 
      array (
      ),
      'pattern' => 
      array (
      ),
    ),
  ),
  'patch' => 
  array (
    'test/:action' => 
    array (
      'rule' => 'test/:action',
      'route' => 'TestController/patch:action',
      'var' => 
      array (
        'action' => 1,
      ),
      'option' => 
      array (
      ),
      'pattern' => 
      array (
      ),
    ),
  ),
  'head' => 
  array (
  ),
  'options' => 
  array (
  ),
  '*' => 
  array (
  ),
  'alias' => 
  array (
  ),
  'domain' => 
  array (
  ),
  'pattern' => 
  array (
    'nsid' => '^[0-9a-zA-Z]{5,10}$',
    'cid' => '^[0-9]+$',
    'page' => '^[0-9]+$',
  ),
  'name' => 
  array (
    'testcontroller/get:action' => 
    array (
      0 => 
      array (
        0 => 'test/:action',
        1 => 
        array (
          'action' => 1,
        ),
        2 => NULL,
        3 => NULL,
      ),
    ),
    'testcontroller/post:action' => 
    array (
      0 => 
      array (
        0 => 'test/:action',
        1 => 
        array (
          'action' => 1,
        ),
        2 => NULL,
        3 => NULL,
      ),
    ),
    'testcontroller/put:action' => 
    array (
      0 => 
      array (
        0 => 'test/:action',
        1 => 
        array (
          'action' => 1,
        ),
        2 => NULL,
        3 => NULL,
      ),
    ),
    'testcontroller/delete:action' => 
    array (
      0 => 
      array (
        0 => 'test/:action',
        1 => 
        array (
          'action' => 1,
        ),
        2 => NULL,
        3 => NULL,
      ),
    ),
    'testcontroller/patch:action' => 
    array (
      0 => 
      array (
        0 => 'test/:action',
        1 => 
        array (
          'action' => 1,
        ),
        2 => NULL,
        3 => NULL,
      ),
    ),
    'usercontroller/login' => 
    array (
      0 => 
      array (
        0 => 'user/login',
        1 => 
        array (
        ),
        2 => NULL,
        3 => NULL,
      ),
    ),
    'usercontroller/updateinfo' => 
    array (
      0 => 
      array (
        0 => 'user/update',
        1 => 
        array (
        ),
        2 => NULL,
        3 => NULL,
      ),
    ),
    'usercontroller/user' => 
    array (
      0 => 
      array (
        0 => 'user/info',
        1 => 
        array (
        ),
        2 => NULL,
        3 => NULL,
      ),
    ),
  ),
);