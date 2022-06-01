<?php
namespace app\controller;

use app\BaseController;
use app\dao\CxDao;
use think\Request;
use app\dao\CxUserDao;
use think\response\Json;
use app\common\Jwt as TokenServer;

class Api extends BaseController
{
    protected $version = '1.5.3';

    public function GetNotice(Request $request): Json
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(3);

        $v = $request->param('v');
        $uid = $request->param('u');
        if ($uid != '' && (new CxUserDao)->CheckAuth($uid)) {
            $token = (new TokenServer())->createJwt($uid);
            $redis->set($uid,$token,10800);
            $array = [
                'version'       => '1.5.3',
                'injection'     => "<img src='https://pic.521daigua.cn/qr.png' width='100%'><p style='color:red;text-align:center;'>Tips:一个纯粹的搜题公众号，脚本没有答案可以试试哦</p><p style='color:red;'>AD：</p><img src='https://pic.521daigua.cn/a.jpg!/format/webp?end=2022-06-27' width='100%'><hr><p style='color:green;text-align:center;'>用户ID:".$uid." | 当前版本:".$v." | 最新版本:".$this->version."</p><p style='text-align:right;width:100%;margin:0;'><a href='http://script.521daigua.cn' target='_blank' style='color: #9933CC;'>
               脚本官网</a>|<a target='_blank' href='https://qun.qq.com/qqweb/qunpro/share?_wv=3&_wwv=128&inviteCode=1PT4BL&from=246610&biz=ka'>脚本交流QQ频道</a></p>\n<p style='text-align:right;width:100%;margin:0;'>[<a href='http://t.cn/A6qFbO2t' target='_blank'>省钱不吃土群</a>|<a href='http://bbs.tampermonkey.net.cn/?fromuser=Ne-21' target='_blank'>油猴中文网</a>|<a href='https://afdian.net/@Ne_21/plan' target='_blank' style='color:red;font-weight: bold;'>😊请作者喝杯奶茶~</a></p>",
                'url'           => 'http://script.521daigua.cn',
                'token'         => $token
            ];
        } else {
            $array = [
                'version'       => '1.5.3',
                'injection'     => "<img src='https://pic.521daigua.cn/qr.png' width='100%'><p style='color:red;text-align:center;'>Tips:一个纯粹的搜题公众号，脚本没有答案可以试试哦</p><p style='color:red;'>AD：</p><img src='https://pic.521daigua.cn/a.jpg!/format/webp?end=2022-06-27' width='100%'><hr><p style='color:green;text-align:center;'>用户ID:".$uid."，此用户未激活脚本，请先激活脚本享受完整功能：<a href='https://script.521daigua.cn/active.html?uid=".$uid."'target='_blank' style='color:red;font-weight: bold;'>点此激活</a></p><p style='text-align:right;width:100%;margin:0;'><a href='http://script.521daigua.cn' target='_blank' style='color: #9933CC;'>
               脚本官网</a>|<a target='_blank' href='https://qun.qq.com/qqweb/qunpro/share?_wv=3&_wwv=128&inviteCode=1PT4BL&from=246610&biz=ka'>脚本交流QQ频道</a></p>\n<p style='text-align:right;width:100%;margin:0;'>[<a href='http://t.cn/A6qFbO2t' target='_blank'>省钱不吃土群</a>|<a href='http://bbs.tampermonkey.net.cn/?fromuser=Ne-21' target='_blank'>油猴中文网</a>|<a href='https://afdian.net/@Ne_21/plan' target='_blank' style='color:red;font-weight: bold;'>😊请作者喝杯奶茶~</a></p>",
                'url'           => 'http://script.521daigua.cn',
                'token'         => ''
            ];
        }
        return json($array);
    }

    public function ActiveScript(Request $request): Json
    {
        $data = $request->post('data');
        $uid = $data['uid'];
        $code = $data['code'];
        if (!$uid && !$code) {
            return json([
                'code'=>0,
                'msg'=>'error'
            ]);
        }
        $cxUserDao = new CxUserDao();
        $res = $cxUserDao->Auth($uid,$code);
        return json([
           'code'=>$res['code'],
            'msg'=>$res['msg']
        ]);
    }


    public function GetCxAnswer(Request $request): Json
    {
        $question = $request->param('question');

        $cxDao = new CxDao();
        $res = $cxDao->GetAnswer($question);
        if (!empty($res['answer'])) {
            $array = [
                'code'    => 1,
                'data'    => $res['answer'],
                'answer'  => $res['answer'],
                'success' => 'true',
                'kid'     => $res['kid'],
            ];
        } else {
            $array = [
                'code'    => 0,
                'data'    => '',
                'answer'  => '',
                'success' => 'false'
            ];
        }
        return json($array);
    }

    public function UpDateCxAnswer(Request $request): Json
    {
        $data = $request->post('data');
        $cxDao = new CxDao();
        $res = $cxDao->UpDateAnswer($data);
        if ($res) {
            $array = [
                'code'    => 1,
                'msg'     => strlen($data),
                's'       => true
            ];
        } else {
            $array = [
                'code'    => 0,
                'msg'     => strlen($data),
                's'       => false
            ];
        }
        return json($array);
    }

    public function GetEnc(Request $request): Json
    {
        $cxEnc = new CxEnc();
        $enc = $cxEnc->Enc($request->param());
        return json(['code'=>1,'enc'=>$enc]);
    }
}
