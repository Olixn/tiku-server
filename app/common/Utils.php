<?php

namespace app\common;

use think\facade\Request;

class Utils
{

    public function GetIP()
    {
        $request = Request::instance();
        return $request->ip();
    }
}