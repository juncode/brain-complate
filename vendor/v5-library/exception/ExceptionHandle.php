<?php

namespace V5\Library\Exception;

use think\exception\Handle;
use V5\Library\Util\FrontCrypt;

class ExceptionHandle extends Handle
{
    private $defaultMsg = 'internal error';

    private $debugKey = 'debug';
    private $debugValue = 'fordebug';

    protected $ignoreReport = [];

    public function render(\Exception $e)
    {
        if (defined('SHOW_EXCEPTION_STACK') && SHOW_EXCEPTION_STACK === true) {
            return parent::render($e);
        }

        if ($e instanceof UserVisibleException || $this->isDebug()) {
            $info = $e->getMessage();
        } else {
            $info = $this->defaultMsg;
        }

        $code = $e->getCode();

        /* 错误信息增加跨域头,使前端也可以获取到信息 */
        ob_clean();
        $origin = empty($_SERVER['HTTP_ORIGIN']) ? '' : $_SERVER['HTTP_ORIGIN'];
        if (!empty($origin)) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Content-Type:application/json; charset=utf-8');
        }

        return json(compact('code', 'info'));
    }

    private function isDebug()
    {
        if (isset($_GET[$this->debugKey])) {
            if ($_GET[$this->debugKey] === $this->debugValue) {
                return true;
            }
        }

        return false;
    }
}
