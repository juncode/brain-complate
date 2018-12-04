<?php
/**
 * User: huangjun<j@wonhsi.com>
 * Date: 2018/2/12
 * Time: 09:18.
 */

namespace model;

use think\Model;

class UserContinues extends Model
{
    protected $table = 'brain_user_continues';

    public function setZero($uid)
    {
        return $this->updateOrInsert($uid, ['continues' => 0]);
    }

    private function updateOrInsert($uid, $update)
    {
        $where = ['user_id' => $uid];
        $update['update_time'] = get_date_time();

        $ret = self::where('user_id', $uid)->update($update);

        if (empty($ret)) {
            self::insert(array_merge($where, $update));
        }

        return true;
    }

    public function incContinues($uid)
    {
        $data = self::where('user_id', $uid)->find();

        if (empty($data)) {
            // 初始化数据
            $insert = [
                'user_id' => $uid,
                'add_time' => get_date_time(),
                'update_time' => get_date_time(),
                'continues' => 1,
            ];

            return self::insert($insert);
        }

        $data->continues = $data->continues + 1;

        $data->save();

        return $data->continues;
    }
}
