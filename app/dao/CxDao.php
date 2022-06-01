<?php
namespace app\dao;

use app\BaseController;
use app\model\Topic;
use think\cache\driver\Redis;
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
     * @return array $answer
     */
    public function GetAnswer(string $question): array
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

    protected function QueryAnswer(): array
    {
        $res = $this->QueryRedis();
        if ($res) {
            return ['kid'=>4,'answer'=>$res];
        } else {
            $res = $this->QueryDb();
            if ($res) {
                $this->save2redis($res[0]['answer']);
                return ['kid'=>1,'answer'=>$res[0]['answer']];
            } else {
                return ['kid'=>1,'answer'=>null];
            }

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
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(1);

        return $redis->get($this->hash);
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
            $db->where('hash',$this->hash)->update([
                'answer'        => $this->answer,
                'type'          => $this->type,
            ]);
        }
    }

    protected function save2redis($answer)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(1);

        $redis->set($this->hash,$answer,1800);
    }
}