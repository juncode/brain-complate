<?php

namespace app\common\util;

use app\common\map\CodeIDSalt;
use V5\Library\Util\AbstractCodeID;

class UserID extends AbstractCodeID
{
    protected $salt = CodeIDSalt::USER_ID_SALT;
    protected $length = CodeIDSalt::USER_ID_LENGTH;
}
