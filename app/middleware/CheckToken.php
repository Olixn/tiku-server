<?php

namespace app\middleware;

use app\common\Jwt as TokenServer;
class CheckToken
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //第一步先取token
        // var_dump($request->header('Authorization'));
        $token = $request->header('Authorization');
        //jwt进行校验token
        if (!$token) {
            $token = '';
        }
        $res = (new TokenServer())->verifyJwt($token);
        if ($res['status'] != 1001 ){
            return json(['code'=>0,'msg'=>$res['msg'],'data'=>'']);
        }
        return $next($request);
    }
}