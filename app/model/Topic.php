<?php
namespace app\model;

use think\Model;

class Topic extends Model
{
    // 自动时间戳
    protected $autoWriteTimestamp = 'datetime';
    // 自动时间别名
    

    // 答案搜索器
    public function searchHashAttr($query,$value)
    {
        $query->where('hash', $value);
        
    }

}