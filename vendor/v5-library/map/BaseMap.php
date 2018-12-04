<?php

namespace V5\Library\Map;

class BaseMap
{
    /** 正确返回的状态码 */
    const COMMON_SUCCESS_CODE = 200;

    /**
     * 开启|是
     */
    const ON = 'on';
    /**
     * 关闭|否
     */
    const OFF = 'off';

    /**
     * 等待处理
     */
    const WAIT = 'wait';
    /**
     * 处理完成
     */
    const DONE = 'done';
    /**
     * 处理失败
     */
    const FAIL = 'fail';
}
