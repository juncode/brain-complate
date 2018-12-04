<?php
/**
 * User: huangjun<j@wonhsi.com>
 * Date: 2018/2/12
 * Time: 09:18.
 */

namespace model;

use think\Log;
use think\Model;

class UserSummary extends Model
{
    protected $table = 'brain_user_summary';

    public static function getSummary($uid)
    {
        return self::where('user_id', $uid)->find();
    }

    public function updateInfo($continues, $uid)
    {
        Log::record('SUMMARY_MODEL_uid:'.$uid);
        $data = self::where('user_id', $uid)->find();

        if (empty($data)) {
            $insert = [
                'totals' => 1,
                'user_id' => $uid,
                'add_time' => get_date_time(),
                'update_time' => get_date_time(),
                'losts' => $continues > 0 ? 0 : 1,
                'continues' => $continues <= 0 ? 0 : 1,
                'wins' => $continues > 0 ? 1 : 0,
            ];

            self::insert($insert);

            return;
        }

        if ($continues > 0) {
            ++$data->wins;
        } elseif ($continues < 0) {
            ++$data->losts;
        }

        ++$data->totals;

        if ($continues > $data->continues) {
            $data->continues = $continues;
        }

        $data->update_time = get_date_time();

        $data->save();
    }
}
