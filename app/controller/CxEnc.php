<?php

namespace app\controller;

use http\Params;

class CxEnc
{
    public function Enc(Params $params): string
    {
        $classId = $params['a'];
        $userId = $params['b'];
        $jobId = $params['c'];
        $objectId = $params['d'];
        $playingTime = $params['e'];
        $duration = $params['f'];
        $clipTime = $params['g'];

        $enc = sprintf("[%s][%s][%s][%s][%s][%s][%s][%s]",$classId,$userId,$jobId,$objectId,$playingTime*1000,'d_yHJ!$pdA~5',$duration*1000,$clipTime);
        // # classId, userid, jobid, objectid,playingTime * 1000, "d_yHJ!$pdA~5",duration * 1000, clipTime
        // enc = "[{0}][{1}][{2}][{3}][{4}][{5}][{6}][{7}]".format(str(a), b, c, d,
        //                                                             e * 1000, "d_yHJ!$pdA~5",
        //                                                             f * 1000, g)
        return md5($enc);
    }
}