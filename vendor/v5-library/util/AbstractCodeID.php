<?php

namespace V5\Library\Util;

use Hashids\Hashids;

/**
 * 对不便于直接暴露到外部的数字ID进行编解码操作的抽象类
 * 需要使用的类,需要重写 salt 和 length两个属性
 */
abstract class AbstractCodeID
{
    protected $stringID = null;
    protected $numberID = null;

    protected $salt = null;
    protected $length = null;

    /**
     * 传入数字形式或者字符串形式的ID都可以
     * AbstractCodeID constructor.
     *
     * @param $id
     *
     * @throws \Exception
     */
    public function __construct($id)
    {
        if (empty($id)) {
            throw new \Exception('construct code id format error');
        }

        if (is_numeric($id)) {
            $this->numberID = $id;
        } else {
            $this->stringID = $id;
        }
    }

    /**
     *  获得编码后字符串形式的ID
     *
     * @return string
     */
    public function toString()
    {
        if (is_null($this->stringID)) {
            $id = $this->coder()->encode($this->numberID);
            if (empty($id)) {
                throw new \Exception('encode number id error');
            }

            $this->stringID = $id;
        }

        return $this->stringID;
    }

    /**
     * 获得数字形式的ID
     *
     * @return array|int|null|string
     *
     * @throws \Exception
     */
    public function toNumber()
    {
        if (is_null($this->numberID)) {
            $decode = $this->coder()->decode($this->stringID);
            if (empty($decode)) {
                throw new \Exception('decode string id error');
            }

            $this->numberID = $decode[0];
        }

        return $this->numberID;
    }

    /**
     * 获得编解码器
     *
     * @return Hashids
     */
    protected function coder()
    {
        return new Hashids($this->salt, $this->length);
    }

    public function __toString()
    {
        return $this->toString();
    }
}
