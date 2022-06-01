<?php

namespace app\model;

use think\Model;

class CxCode extends Model
{
    protected $table = 'cx-code';
    // 自动时间戳
    protected $autoWriteTimestamp = 'datetime';
}