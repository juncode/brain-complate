<?php

namespace model;

use think\Model;

/**
 * Class User.
 *
 * @property int id                        自增id
 * @property string unid                        唯一标识
 * @property string wx_open_id         微信小程序openid
 * @property string wx_union_id            微信unionid
 * @property string wx_avatar_url               头像
 * @property string wx_country                 微信国家
 * @property string wx_province                 微信省份
 * @property string wx_city                 微信城市
 * @property string wx_gender                 性别
 * @property string wx_nick_name               昵称
 * @property int add_time          用户上次登录时间
 * @property int update_time          更新时间
 */
class User extends Model
{
    // 隐藏属性
    protected $table = 'brain_user';
    protected $pk = 'id';

    /**
     * 某个用户ID是否存在.
     *
     * @param int $id 用户数字ID
     *
     * @return bool
     */
    public static function isExist($id)
    {
        $where = compact('id');
        $find = self::where($where)->value('id');

        if (!empty($find)) {
            return true;
        }

        return false;
    }

    public static function getByWxappOpenid($wxappOpenid)
    {
        $where = ['wx_open_id' => $wxappOpenid];

        $user = self::where($where)->find();

        return $user;
    }

    public static function getByWxapp($openid, $unionid = '')
    {
        $query = self::where('wx_open_id', $openid);

        if (!empty($unionid)) {
            $query->whereOr('wx_union_id', $unionid);
        }

        $user = $query->find();

        return $user;
    }

    public static function getByNickName($nickname)
    {
        $query = self::where('wx_nick_name', $nickname);

        $user = $query->find();

        return $user;
    }

    public static function getByUnid($unid)
    {
        $query = self::where('unid', $unid);

        $user = $query->find();

        return $user;
    }
}
