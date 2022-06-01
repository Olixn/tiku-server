<?php
namespace app\controller;

use app\BaseController;
use app\dao\CxDao;
use think\Request;
use think\response\Json;

class Api extends BaseController
{
    public function GetCxAnswer(Request $request): Json
    {
        $question = $request->param('question');

        $cxDao = new CxDao();
        $answer = $cxDao->GetAnswer($question);
        if (!empty($answer)) {
            $array = [
                'code'    => 1,
                'data'    => $answer,
                'answer'  => $answer,
                'success' => 'true',
                'kid'     => 3,
            ];
        } else {
            $array = [
                'code'    => 0,
                'data'    => '',
                'answer'  => '',
                'success' => 'false',
                'kid'     => 3,
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
