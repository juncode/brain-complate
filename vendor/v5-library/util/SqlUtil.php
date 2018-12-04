<?php

namespace V5\Library\Util;

use think\Db;
use think\exception\PDOException;

class SqlUtil
{
    /**
     * 判断一个错误是否是 : Mysql 唯一索引 或者 主键(Duplicate Entry Error)
     *
     * @param \Exception $e
     *
     * @return bool
     */
    public static function isDuplicateEntryException($e)
    {
        $DuplicateEntryErrorCode = 1062;

        if ($e instanceof PDOException) {
            $data = $e->getData();
            $code = (int) $data['PDO Error Info']['Driver Error Code'];

            if ($code === $DuplicateEntryErrorCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * 如果某张表不存在,则创建,并返回表名
     *
     * @param $tablePrefix
     * @param $template
     *
     * @return string 需要的表名
     */
    public static function getMonthlyTableOrCreate($tablePrefix, $template)
    {
        $table = $tablePrefix . date('Ym');
        $sql = "CREATE TABLE IF NOT EXISTS `{$table}` LIKE `{$template}`";

        Db::execute($sql);

        return $table;
    }
}
