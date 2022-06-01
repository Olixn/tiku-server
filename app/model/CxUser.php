<?php

namespace app\model;

use think\Model;

class CxUser extends Model
{
    protected $table = 'cx-user';
    // 自动时间戳
    protected $autoWriteTimestamp = 'datetime';
}