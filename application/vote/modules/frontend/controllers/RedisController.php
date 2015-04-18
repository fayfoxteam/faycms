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
    
    public function getValue()
    {
        $redis = $this->redis();
        for ($i = 1000; $i < 1100; $i++)
        {
            $key = getTeacherKey($i);
            if ($redis->sSize($key) == 0)
            {
                break;
            }
            dump($redis->sMembers($key));
        }
        
    }

    public function redisFlushDb()
    {
        $redis = $this->redis();
        dump($redis->flushDB());
    }
    
    //获取已经投票的人数
    public function getVoteStudent()
    {
        $redis = $this->redis();
        for ($i = 1000; $i < 1100; $i++)
        {
            $key = getTeacherKey($i);
            if ($redis->sSize($key) == 0)
            {
                break;
            }
            $redis->sUnionStore('new',$key);
        }
        echo "已投票人数：".$redis->sSize('new')."人， ID为：";
        dump($redis->sMembers('new'));
    }
    
    //获取老师的票数
    public function getTeacherCount()
    {
        $redis = $this->redis();
        for ($i = 1000; $i< 1020; $i++)
        {
            $key = getTeacherKey($i);
            echo $i.'老师的票数是:'.$redis->sSize($key).'<br />';
        }
    }
    
    public function test()
    {
    }
}