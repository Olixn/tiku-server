<?php

namespace app\controller;

use app\BaseController;
use app\model\CxCode;
use app\Request;
use think\response\Json;

class Admin extends BaseController
{
    public function AddCode(Request $request): Json
    {
        $code = $request->param('code');
        $db = new CxCode();
        $res = $db->where('encode',$code)->select();
        if ($res->isEmpty()) {
            $db->insert(['encode'=>$code]);
            return json(['code'=>1]);
        }
        return json(['code'=>2]);
    }
}