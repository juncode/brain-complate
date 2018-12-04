<?php

namespace app\common\map;

class ExcCode
{
    const UPLOAD_FILE_ERROR = ['code' => '501', 'message' => '上传文件失败'];
    /**
     * 通用错误码
     */
    const PARAM_ERROR = ['code' => 3001, 'message' => 'param error'];
    const LOGIC_ERROR = ['code' => 3002, 'message' => 'logic error'];

    /**
     * 通用状态码
     */
    const LOGIN_NO_SESSION = ['code' => 201, 'message' => 'SESSION 过期'];
    const LOGIN_TIME_OUT = ['code' => 202, 'message' => '登录超时'];
    const LOGIN_WECHAT_LOGIN_FAIL = ['code' => 203, 'message' => '登录失败'];
    const USER_NOT_EXIST = ['code' => 204, 'message' => '用户不存在'];
    const LOGIN_COOKIE_ERROR = ['code' => 205, 'message' => '用户cookie不存在'];

    /**
     *
     */
    const SOCKET_DO_SUCCESS = ['type' => 'login', 'code' => 200, 'info' => '', 'data' => ''];
    const SOCKET_LOGIN_NO_SESSION = ['type' => 'login', 'code' => 210, 'info' => '登录失效', 'data' => ''];
    const SOCKET_ROOM_IS_FULL = ['type' => 'login', 'code' => 220, 'info' => '房主正在对战中', 'data' => ''];
    const SOCKET_QUESTION_FATAL = ['type' => 'answer', 'code' => 221, 'info' => '回答问题异常', 'data' => ''];
    const SOCKET_ANSWER_RESULT = ['type' => 'answer', 'code' => 200, 'info' => '回答结果', 'data' => ''];
    const SOCKET_READY_ERROR = ['type' => 'ready', 'code' => 222, 'info' => '非房主触发发送题目', 'data' => ''];
    const SOCKET_SEND_QUESTION = ['type' => 'question', 'code' => 200, 'info' => '题目内容', 'data' => ''];
    const SOCKET_USER_EXIT = ['type' => 'exit', 'code' => 200, 'info' => '用户退出', 'data' => ''];
    const SOCKET_USER_AGAIN = ['type' => 'again', 'code' => 200, 'info' => '再来一局', 'data' => ''];
}
