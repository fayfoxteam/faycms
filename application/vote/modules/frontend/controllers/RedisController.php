<?php
namespace vote\modules\frontend\controllers;

use vote\library\FrontendController;
class RedisController extends FrontendController
{
    //对redis额外操作的控制器
    public function index()
    {
        echo 'redis';
    }
    
    public function redisCount()
    {
        $key = getTeacherKey(1000);
        $redis = $this->redis();
        echo $redis->sSize($key);
        
    }

    public function redisFlushDb()
    {
        $redis = $this->redis();
        dump($redis->flushDB());
    }
}