<?php
/**
 * User: huangjun<j@wonhsi.com>
 * Date: 2018/2/9
 * Time: 01:14.
 */

namespace app\common\logic;

use app\common\util\UserID;
use model\UserSummary;

class GameResult
{
    private $user_id = null;

    /**
     * @var UserSummary|null
     */
    private $summary = null;

    /**
     * GameResult constructor.
     *
     * @param $userId
     */
    public function __construct($userId)
    {
        if(!is_numeric($userId) && is_string($userId)){
            $userId = (new UserID($userId))->toNumber();
        }

        $this->user_id = $userId;

        $this->summary = UserSummary::getSummary($userId);
    }

    /**
     * 获取用户id
     * @return null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * 获取总参与场数
     * @return int|mixed
     */
    public function getTotals()
    {
        return empty($this->summary) ? 0 : $this->summary->totals;
    }

    /**
     * 获取最大持续胜利数
     * @return int|mixed
     */
    public function getContinues()
    {
        return empty($this->summary) ? 0 : $this->summary->continues;
    }

    /**
     * 获得总胜利场数
     * @return int|mixed
     */
    public function getWins()
    {
        return empty($this->summary) ? 0 : $this->summary->wins;
    }

    /**
     * 获取输了场数
     * @return int|mixed
     */
    public function getLost()
    {
        return empty($this->summary) ? 0 : $this->summary->losts;
    }

    /**
     * 获取最后比赛时间
     * @return mixed|null|string
     */
    public function getLastTime()
    {
        return empty($this->summary) ? null : $this->summary->update_time;
    }
}
