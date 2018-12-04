<?php
/**
 * User: huangjun<j@wonhsi.com>
 * Date: 2018/2/12
 * Time: 09:18
 */

namespace model;

use think\Model;

class QuestionLIst extends Model
{
    protected $table = "brain_question";

    public function getQuestions()
    {
        $sql = "SELECT * FROM `brain_question` ORDER BY rand() LIMIT 5;";

        return self::query($sql);
    }
}