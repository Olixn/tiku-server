<?php

namespace app\dao;

use app\common\Utils;
use app\model\CxCode;
use app\model\CxUser;

class CxUserDao
{

    public function AuthStatus($uid): bool
    {
        $db = new CxUser();
        $res = $db->where('uid',$uid)->find();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

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
        if (!is_numeric($uid) || strlen($code) != 32) {
            return ['code'=>0,'msg'=>'不要想，去感受。Just Fuck You!'];
        }
        $dbUser = new CxUser();
        $dbCode = new CxCode();
        $ip = (new Utils())->GetIP();
        $user = $dbUser->where('uid',$uid)->find();
        if (!$user) {
            $res = $dbCode->where('encode',$code)->find();
            if (!$res) {
                return ['code'=>0,'msg'=>'😓激活码不存在'];
            } else if ($res['s']) {
                return ['code'=>0,'msg'=>'👉激活码已于'.$res['update_time'].'被使用'];
            }else {
                $dbCode->where('id',$res['id'])->update(['s'=>1]);
                $dbUser->insert(['uid'=>$uid,'encode'=>$code,'ip'=>$ip]);
                return ['code'=>1,'msg'=>'🎈授权激活成功！'];
            }
        } else {
            return  ['code'=>1,'msg'=>'🔔该用户ID已于'.$user['create_time'].'激活，请到期后再试！'];
        }
    }
}