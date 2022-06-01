<?php
namespace app\dao;

use app\BaseController;
use app\model\Topic;
use think\facade\Request;

class CxDao
{

    private $question;
    private $answer;
    private $hash;
    private $type;
    private $ip;

    /**
     * 获取题目答案
     * 
     * @param String $question
     * @return String $answer
     */
    public function GetAnswer(string $question): string
    {
        $this->ip = $this->GetIP();
        $this->hash = $this->GetHash($question);
        $this->question = $question;
        return $this->QueryAnswer();
    }

    public function UpDateAnswer($date):bool
    {
            $courseData = json_decode($date, true);
            if ($courseData == []) {
                return false;
            }
            $this->ip = $this->GetIP();
            foreach ($courseData as $v) {
                $this->hash         = md5($v['question']);
                $this->question     = $v['question'];
                $this->answer       = $v['answer'];
                $this->type         = $v['type'];
                $this->UpDate();
            }
            return true;
    }

    protected function GetHash($str): string
    {
        return md5($str);
    }

    protected function GetIP(): string
    {
        $request = Request::instance();
        return $request->ip();
    }

    protected function QueryAnswer()
    {
        if ($this->QueryRedis()) {
            return 'redis get';
        } else {
            $res = $this->QueryDb();
            return $res[0]['answer'];
        }
    }

    protected function QueryDb()
    {
        $db = new Topic();
        return $db->withSearch(['hash'],[
            'hash' => $this->hash,
        ])->field('answer')->select();
    }

    protected function QueryRedis()
    {
        return false;
    }

    protected function UpDate()
    {
        $result = $this->QueryDb();
        $db = new Topic();
        if ($result->isEmpty()){
            $data = [
                'type'          => $this->type,
                'topic'         => $this->question,
                'hash'          => $this->hash,
                'answer'        => $this->answer,
                'ip'            => $this->ip,
            ];
            $db->save($data);
        } else {
            $db->save([
                'answer'        => $this->answer,
                'type'          => $this->type,
            ],['hash' => $this->hash]);
        }
    }
}