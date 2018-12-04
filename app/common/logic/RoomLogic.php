<?php
/**
 * User: huangjun<j@wonhsi.com>
 * Date: 2018/2/9
 * Time: 22:00.
 */

namespace app\common\logic;

use app\common\util\Redis;
use think\Log;

class RoomLogic
{
    //当前用户编号
    private $_user_id = null;
    //当前房间唯一编码
    private $_source = null;

    private $_redis = null;

    private $_room_prefix = 'ROOM_';
    private $_user_prefix = 'USER_';

    public function __construct($enterUserId, $source = null)
    {
        $this->_user_id = $enterUserId;

        $this->_source = !empty($source) ? $source : $enterUserId;

        $this->_redis = Redis::init('session');
    }

    /**
     * 获取房间编号.
     */
    public function getRoomId()
    {
        return $this->_source;
    }

    /**
     * 获取参战信息.
     *
     * @return array
     */
    public function getBattalInfo()
    {
        $info = $this->_redis->hGetAll($this->getRoom());

        Log::record("获取用户信息");
        Log::record($info);
        if(empty($info)){
            return [];
        }

        $ret = [];
        foreach ($info as $key => $value){
            $value = _json_decode($value);
            $ret[$value['uid']] = $value;
        }

        return $ret;
    }
    /**
     * 获取当前用户信息.
     */
    public function getUserInfo()
    {
        $data = $this->_redis->hGet($this->getRoom(), $this->getHashKey());

        $data = _json_decode($data);

        return $data;
    }

    /**
     * 加入房间.
     *
     * @param $ip
     * @param $avatar
     * @param $nickname
     * @param $isFanZhu
     */
    public function enterRoom($ip, $avatar, $nickname, $isFanZhu = false)
    {
        $info = [
            'ip' => $ip,
            'uid' => $this->_user_id,
            'avatar' => $avatar,
            'nickname' => $nickname,
            'isfanzhu' => $isFanZhu,
            'create_time' => get_date_time(),
        ];

        $this->_redis->hSet($this->getRoom(), $this->getHashKey(), _json_encode($info));
    }

    public function updateRoom($data)
    {
        $this->_redis->hSet($this->getRoom(), $this->getHashKey($data['uid']), _json_encode($data));
    }

    public function delRoom()
    {
        return $this->_redis->hDel($this->getRoom(), $this->getHashKey());
    }

    private $_correct_nums = '{_uid_}_corrects';
    private $_correct_scores = '{_uid_}_scores';

    /**
     * 用户分数.
     *
     * @param $uid
     * @param $correctNums
     * @param $scores
     */
    public function setUserScores($uid, $correctNums, $scores)
    {
        empty($uid) && $uid = $this->_user_id;

        $corrects = str_replace('{_uid_}', $uid, $this->_correct_nums);
        $corrects_scores = str_replace('{_uid_}', $uid, $this->_correct_scores);

        /* 累计 计分*/
        $this->_redis->hIncrBy($this->getRoomScoreLabel(), $corrects, $correctNums);
        $this->_redis->hIncrBy($this->getRoomScoreLabel(), $corrects_scores, $scores);
    }

    /**
     * 答题记录, 满两人答完，返回结果且删除记录.
     *
     * @param $qid
     * @param $info
     *
     * @return array
     */
    public function setIncAnswers($qid, $info = null)
    {
        $keyQid = $this->getRoom().'_question_'.$qid;

        if(empty($info)){
            $ret = $this->_redis->hGetAll($keyQid);

            foreach ($ret as $key => $value) {
                $ret[$key] = _json_decode($value);
            }

            return $ret;
        }

        $info = _json_encode($info);

        $this->_redis->hSet($keyQid, $this->_user_id, $info);

        $ret = $this->_redis->hGetAll($keyQid);

        foreach ($ret as $key => $value) {
            $ret[$key] = _json_decode($value);
        }

        return $ret;
    }

    /**
     * @param $qid
     */
    public function delAnswers($qid)
    {
        $keyQid = $this->getRoom().'_question_'.$qid;

        $this->_redis->del($keyQid);
    }

    /**
     * @param string $status
     * @return bool|string
     */
    public function gameStatus($status = '')
    {
        $key = $this->getRoom() . "_STATUS";

        if(!empty($status)){
            $this->_redis->set($key, $status);
        } else {
            return $this->_redis->get($key);
        }

        return true;
    }

    /**
     * 获得用户分数.
     *
     * @return array
     */
    public function getUserScores()
    {
        //获取分数数据
        $data = $this->_redis->hGetAll($this->getRoomScoreLabel());

        $ret = [];
        foreach ($data as $key => $value){
            $key = explode('_', $key);

            $ret[$key[0]][$key[1]] = $value;
        }
        //返回
        return $ret;
    }

    public function getRoomScoreLabel()
    {
        return $this->getRoom().'_SCORES';
    }

    /**
     * 退出房间
     * ###.
     *
     * @return string
     */
    public function ExitRoom()
    {
        return $this->_redis->hDel($this->getRoom(), $this->getHashKey());
    }

    /**
     * 获取用户数量.
     */
    public function getUserNums()
    {
        return $this->_redis->hLen($this->getRoom());
    }

    /**
     * 获得房间.
     *
     * @return string
     */
    private function getRoom()
    {
        return $this->_room_prefix.strtoupper($this->_source);
    }

    /**
     * 获得hashKey.
     *
     * @param  $user_id
     * @return string
     */
    private function getHashKey($user_id = null)
    {
        $user_id = empty($user_id) ? $this->_user_id : $user_id;
        return $this->_user_prefix.strtoupper($user_id);
    }

    private function getQuestionKey()
    {
        return $qKey = $this->getRoom().'_question_list';
    }

    private function getCurrectQuestionKey()
    {
        return $qKey = $this->getRoom().'_current_question';
    }

    /**
     * 添加题目到缓存库处理逻辑.
     *
     * @param $question
     */
    public function initQuestion($question)
    {
        $qKey = $this->getQuestionKey();

        //清理之前可能存在的数据
        $this->_redis->del($qKey);
        $this->_redis->del($this->getRoomScoreLabel());
        $this->_redis->del($this->getQuestionKey());
        $this->_redis->del($this->getCurrectQuestionKey());
        $this->_redis->del($this->getAgainGameKey());

        foreach ($question as $key => $q) {
            $this->_redis->lPush($qKey, _json_encode($q));
        }
    }

    /**
     * 获取剩余题数.
     *
     * @return int
     */
    public function getLeftQuestion()
    {
        return $this->_redis->lLen($this->getQuestionKey());
    }

    /**
     * 返回题目.
     *
     * @return string
     */
    public function getOneQuestion()
    {
        $question = $this->_redis->lPop($this->getQuestionKey());

        $question = _json_decode($question);

        $this->delAnswers($question['id']);

        $this->currentQuestion($question);

        return $question;
    }

    /**
     * 设置或者获取当前答题内容.
     *
     * @param null $question
     *
     * @return bool|string
     */
    public function currentQuestion($question = null)
    {
        if (!empty($question)) {
            $question['add_time'] = get_date_time();
            $this->_redis->set($this->getCurrectQuestionKey(), _json_encode($question));
        } else {
            $question = $this->_redis->get($this->getCurrectQuestionKey());

            return _json_decode($question);
        }

        return true;
    }



    private function getAgainGameKey()
    {
        return $qKey = $this->getRoom().'_again';
    }

    /**
     * 记录再来一局用户id
     * @return array
     */
    public function gameAgain()
    {
        $key = $this->getAgainGameKey();

        $this->_redis->lPush($key, $this->_user_id);

        $data = $this->_redis->lRange($key, 0, -1);

        return $data;
    }

}
