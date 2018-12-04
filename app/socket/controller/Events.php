<?php
/**
 * User: huangjun<j@wonhsi.com>
 * Date: 2018/2/9
 * Time: 18:11.
 */

namespace app\socket\controller;

//declare(ticks=1);

/*
 * 聊天主逻辑
 * 主要是处理 onMessage onClose
 */
use app\common\logic\account\SessionLogic;
use app\common\logic\RoomLogic;
use app\common\map\ExcCode;
use app\common\util\UserID;
use GatewayWorker\Lib\Gateway;
use model\QuestionLIst;
use model\User;
use model\UserContinues;
use model\UserSummary;
use think\Log;

class Events
{
    public static function onConnect($client_id)
    {
        // 向当前client_id发送数据
        // 向所有人发送
        Log::record('用户链接中: '.$client_id);
    }

    /**
     * 有消息时.
     *
     * @param $client_id
     * @param $message
     *
     * @return bool|void
     *
     * @throws \Exception
     */
    public static function onMessage($client_id, $message)
    {
        // 客户端传递的是json数据
        $message_data = json_decode($message, true);
        Log::record('发生login 操作:'.$message);
        if (!$message_data) {
            return;
        }

        // 根据类型执行不同的业务
        switch ($message_data['type']) {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'test':
                Gateway::sendToClient($client_id, _json_encode(['test' => 'ok']));

                return;
            case 'login':
                // 判断是否有房间号
                $token = $message_data['token'];

                Log::record('进入游戏房间');
                Log::record('玩家：'.$token);

                $user = (new SessionLogic($token))->getBySocket($token);

                if (empty($user) || empty($user['unid'])) {
                    Gateway::sendToClient($client_id, _json_encode(ExcCode::SOCKET_LOGIN_NO_SESSION));

                    return;
                }

                // 用户存储
                $_SESSION = $user;

                $share = isset($message_data['s']) ? $message_data['s'] : '';
                Log::record('分享者：'.$share);

                // 实例化 房间管理逻辑类

                if (empty($share)) {
                    // 非分享房间进来，返回成功
                    $_SESSION['room_id'] = '';
                } else {
                    $_SESSION['room_id'] = $share;
                }
                $register = self::getRoom();

                $roomNums = $register->getUserNums();

                $userInfo = User::getByUnid($_SESSION['unid']);
                if (empty($userInfo)) {
                    Gateway::sendToCurrentClient(_json_encode(ExcCode::SOCKET_LOGIN_NO_SESSION));

                    return;
                }

                if ($roomNums >= 2) {
                    // 房间已满，提前结束
                    $ret = ExcCode::SOCKET_ROOM_IS_FULL;
                    $ret['data'] = [
                        $_SESSION['unid'] => [
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'avatar' => $userInfo->wx_avatar_url,
                            'nickname' => $userInfo->wx_nick_name,
                            'create_time' => get_date_time(),
                            'isfanzhu' => false,
                        ],
                    ];
                    Gateway::sendToCurrentClient(_json_encode($ret));

                    return;
                }

                $isFanZhu = false;
                if ($roomNums == 0) {
                    $isFanZhu = true;
                } else {
                    $user = $register->getBattalInfo();
                    if ($_SESSION['unid'] == $register->getRoomId()) {
                        $isFanZhu = true;
                    }
                    foreach ($user as $uid => $datum) {
                        if ($datum['uid'] != $_SESSION['unid'] && $datum['isfanzhu'] && $isFanZhu == true) {
                            $datum['isfanzhu'] = false;
                            $register->updateRoom($datum);
                        }
                    }
                }

                $register->enterRoom($_SERVER['REMOTE_ADDR'], $userInfo->wx_avatar_url, $userInfo->wx_nick_name, $isFanZhu);

                $_SESSION['room_id'] = $register->getRoomId();

                Gateway::joinGroup($client_id, $_SESSION['room_id']);

                $battle = $register->getBattalInfo();

                $ret = ExcCode::SOCKET_DO_SUCCESS;
                $ret['data'] = $battle;

                // 如果有两个用户了，群发通知
                if (count($battle) == 2) {
                    Gateway::sendToGroup($_SESSION['room_id'], _json_encode($ret));
                } else {
                    Gateway::sendToCurrentClient(_json_encode($ret));
                }

                return;
            //开始游戏
            case 'begin':
                // 初始化题目列表
                self::initQuestion();

                return;
            case 'again':
                self::gameAgain();
                return ;
            // 回答问题
            case 'answered':
                self::answerQuestion($message_data['choose'], $message_data['seconds']);

                return;
            case 'next':
                self::sendToUserQuestion();
                break;
            case 'giveup':
                self::exitGame($client_id);

                return;
        }
    }

    public static function gameAgain()
    {
        $room = self::getRoom();

        $data = $room->gameAgain();

        $ret = ExcCode::SOCKET_USER_AGAIN;

        $ret['data'] = $data;

        Gateway::sendToGroup($_SESSION['room_id'], _json_encode($ret));
    }

    /**
     * socket 通信 中断.
     *
     * @param $client_id
     */
    public static function onClose($client_id)
    {
        self::exitGame($client_id);

        return;
    }

    /**
     * 答题状态
     *
     * @param $option
     * @param $seconds
     */
    public static function answerQuestion($option, $seconds)
    {
        Log::record('答题状态');
        $room = self::getRoom();
        $question = $room->currentQuestion();
        Log::record('题目'._json_encode($question));
        $qid = $question['id'];

        $scores = 0;
        // 回答状态
        $status = false;

        if ($question['correct_answer'] == $option) {
            Log::record('正确');

            if ($seconds > 0) {
                $scores = $seconds * 20;
            } else {
                $scores = 0;
            }

            $status = true;
        }
        Log::record('用户:'.$_SESSION['unid']);
        Log::record('得分:'.$scores);

        $correct = $question['correct_answer'];

        $data = compact('scores', 'option', 'status');

        $answerinfo = $room->setIncAnswers($qid, $data);

        Log::record('答题内容：');
        Log::record($answerinfo);

        $room->setUserScores($_SESSION['unid'], $status == true ? 1 : 0, $scores);

        $ret = ExcCode::SOCKET_ANSWER_RESULT;

        $ret['data']['num'] = 5 - $room->getLeftQuestion();

        $ret['data']['result'] = self::getResult();

        $ret['data']['detail'] = $answerinfo;

        $ret['data']['correct'] = $correct;

        if (count($answerinfo) == 2) {
            Gateway::sendToGroup($_SESSION['room_id'], _json_encode($ret));
            self::recordScore($ret['data']['num'], $ret['data']['result']);
        } else {
            Gateway::sendToCurrentClient(_json_encode($ret));
        }

        return;
    }

    /**
     * 发送题目
     * TODO 倒计时未答题，推送答案.
     */
    public static function initQuestion()
    {
        $room = self::getRoom();
        $user = $room->getUserInfo();
        $room->gameStatus('running');
        Log::record('初始化题目：');
        Log::record($user);
        if (!$user['isfanzhu']) {
            Log::record('非房间主人');
            Gateway::sendToCurrentClient(_json_encode(ExcCode::SOCKET_READY_ERROR));

            return;
        }

        $question = (new QuestionLIst())->getQuestions();

        foreach ($question as $key => $datum) {
            $question[$key]['answers'] = _json_decode($datum['answers']);
        }

        //把数据记录下来
        $room->initQuestion($question);

        self::sendToUserQuestion();
    }

    /**
     * 触发题目.
     */
    public static function sendToUserQuestion()
    {
        $room = self::getRoom();
        $question = $room->getOneQuestion();

        if (empty($question)) {
            //如果没有题目，终结
            return;
        }

        $ret = ExcCode::SOCKET_SEND_QUESTION;

        $question['number'] = 5 - $room->getLeftQuestion();

        $ret['data'] = $question;

        Gateway::sendToGroup($_SESSION['room_id'], _json_encode($ret));

        return;
    }

    public static function getResult()
    {
        $scores = self::getRoom()->getUserScores();

        Log::record('====');
        Log::record($scores);

        foreach ($scores as $uid => $datum) {
            $scores[$uid]['win'] = 0;
        }

        if (count($scores) < 2) {
            return $scores;
        }

        $uids = array_keys($scores);

        $user1 = $uids[0];
        $user2 = $uids[1];

        if ($scores[$user1]['scores'] > $scores[$user2]['scores']) {
            $scores[$user1]['win'] = 1;
            $scores[$user2]['win'] = -1;
        } elseif ($scores[$user1]['scores'] < $scores[$user2]['scores']) {
            $scores[$user1]['win'] = -1;
            $scores[$user2]['win'] = 1;
        }

        return $scores;
    }

    public static function exitGame($client_id)
    {
        $room = self::getRoom();

        $delNum = $room->delRoom();
        $room->gameStatus('close');

        if ($delNum == 0 || !isset($_SESSION['unid'])) { // 已经删除房间，不再触发
            return;
        }

        $gaming = $room->getLeftQuestion();
        $ret = ExcCode::SOCKET_USER_EXIT;
        $ret['data']['uid'] = $_SESSION['unid'];
        $ret['data']['result'] = null;

        Gateway::leaveGroup($client_id, $_SESSION['room_id']);

        if (!empty($gaming)) {
            $scores = $room->getUserScores();

            $battle = $room->getBattalInfo();

            if (empty($scores)) {
                $uid = key($battle);
                $scores[$uid] = ['corrects' => 0, 'scores' => 0];
            }

            foreach ($scores as $uid => $val) {
                if ($uid != $_SESSION['unid']) {
                    // 赢 1， 输-1， 平局0
                    $scores[$uid]['win'] = 1;
                } else {
                    $scores[$uid]['win'] = -1;
                }
            }

            $question = $room->currentQuestion();

            $ret['data']['num'] = 5 - $room->getLeftQuestion();

            $ret['data']['result'] = $scores;

            $ret['data']['detail'] = $scores;

            $ret['data']['correct'] = $question['correct_answer'];
        }

        self::recordScore(5, $ret['data']['result']);

        Gateway::sendToGroup($_SESSION['room_id'], _json_encode($ret));
    }

    public static function getRoom()
    {
        return new RoomLogic($_SESSION['unid'], $_SESSION['room_id']);
    }

    public static function recordScore($leftNum, $result)
    {
        Log::record('当前局数'.$leftNum);

        if ($leftNum < 5 || empty($result)) {
            return;
        }

        Log::record('当前比赛结果'._json_encode($result));

        $continueModel = new UserContinues();
        $summaryModel = (new UserSummary());

        foreach ($result as $uid => $datum) {
            $uid = (new UserID($uid))->toNumber();
            Log::record('uid:'.$uid);
            Log::record('message:'._json_encode($datum));
            if ($datum['win'] > 0) {
                // 记录玩家赢了
                $continues = $continueModel->incContinues($uid);
                $summaryModel->updateInfo($continues, $uid);
            } elseif ($datum['win'] == 0) {
                $summaryModel->updateInfo(0, $uid);
                // 记录玩家平局
            } else {
                $continueModel->setZero($uid);
                $summaryModel->updateInfo(-1, $uid);
                // 记录玩家输了
            }
        }
    }
}
