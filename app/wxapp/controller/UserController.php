<?php

namespace app\wxapp\controller;

use app\common\controller\LoginStatusTrait;
use app\common\logic\account\WxAppAccount;
use app\common\logic\GameResult;
use app\common\map\ExcCode;

class UserController
{
    use LoginStatusTrait;

    /**
     * 登录.
     *
     * @return array
     *
     * @throws \V5\Library\Exception\UserVisibleException
     */
    public function login()
    {
        $code = request()->post('wxcode');

        if (empty($code)) {
            send_front_error(ExcCode::PARAM_ERROR, 'code empty');
        }

        $account = WxAppAccount::login($code);
        $authToken = $account->sessionID;
        $uid = $account->unid;

        return success_return(compact('authToken', 'uid'));
    }

    /**
     * 更新信息.
     *
     * @return array
     *
     * @throws \V5\Library\Exception\UserVisibleException
     */
    public function updateInfo()
    {
        $avatar = request()->post('avatar');
        $nickname = request()->post('nickname');
        $gender = request()->post('gender');
        $city = request()->post('city');
        $province = request()->post('province');
        $country = request()->post('country');

        if (empty($nickname)) {
            send_front_error(ExcCode::PARAM_ERROR);
        }

        $unid = $this->getUnid();

        WxAppAccount::updateInfo($unid, $avatar, $nickname, $gender, $city, $province, $country);

        return success_return();
    }

    /**
     * @return string
     */
    public function user()
    {
        $unid = $this->getUnid();

        $user = WxAppAccount::getUserInfo($unid);

        $gameResult = new GameResult($user['unid']);

        $continues = $gameResult->getContinues();
        $wins = $gameResult->getWins();

        return success_return(compact('user', 'continues', 'wins'));
    }

    /**
     * 从登录状态中获得unid.
     *
     * @return int
     *
     * @throws \V5\Library\Exception\UserVisibleException
     */
    private function getUnid()
    {
        $account = $this->getAccountOrExist();

        return $account->unid;
    }
}
