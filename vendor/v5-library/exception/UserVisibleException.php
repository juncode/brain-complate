<?php

namespace V5\Library\Exception;

class UserVisibleException extends \Exception
{
    public function __construct(array $excode, $appendMsg = '')
    {
        $msg = $excode['message'];
        if (!empty($appendMsg)) {
            $msg = implode(' : ', [$msg, $appendMsg]);
        }

        parent::__construct($msg, $excode['code']);
    }
}
