<?php

namespace app\controller;

use app\BaseController;
use app\model\CxCode;
use app\Request;
use think\response\Json;

class Admin extends BaseController
{
    protected $sign = 'ne-21!!@';

    public function AddCode(Request $request): Json
    {
        $code = $request->param('code');
        $sign = $request->param('sign');


        if (!$sign || $sign != md5($this->sign)) {
            return json(['code'=>2]);
        }

        $db = new CxCode();
        $res = $db->where('encode',$code)->select();
        if ($res->isEmpty() && $code && strlen($code) == 32) {
            $db->insert(['encode'=>$code]);
            return json(['code'=>1]);
        }
        return json(['code'=>2]);
    }
}