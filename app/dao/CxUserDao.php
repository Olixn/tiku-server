<?php

namespace app\dao;

use app\common\Utils;
use app\model\CxCode;
use app\model\CxUser;

class CxUserDao
{

    public function CheckAuth($uid): bool
    {
        $db = new CxUser();
        $res = $db->where('uid',$uid)->select();
        if ($res->isEmpty()) {
            return false;
        } else {
            return true;
        }
    }

    public function Auth($uid,$code)
    {
        $dbUser = new CxUser();
        $dbCode = new CxCode();
        $ip = (new Utils())->GetIP();
        $res = $dbCode->where('encode',$code)->find();
        if (!$res || $res['s']) {
            return ['code'=>0,'msg'=>'激活码不存在或已被使用！'];
        } else {
            $dbCode->where('id',$res['id'])->update(['s'=>1]);
            $ress = $dbUser->where('uid',$uid)->find();
            if (!$ress) {
                $dbUser->insert(['uid'=>$uid,'encode'=>$code,'ip'=>$ip]);
                return ['code'=>1,'msg'=>'授权激活成功！'];
            } else {
                $dbUser->where('id',$ress['id'])->update(['encode'=>$code,'ip'=>$ip]);
                return  ['code'=>1,'msg'=>'授权更新成功！'];
            }
        }
    }
}