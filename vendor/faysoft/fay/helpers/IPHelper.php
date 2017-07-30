<?php
namespace fay\helpers;

class IPHelper{
    /**
     * 将ip转换为int存储，返回32位机器的int值
     * @param string $ip
     * @return int
     */
    public static function ip2int($ip){
        if(!$r = ip2long($ip)) return $r;
        if($r > 2147483647)
            $r -= 4294967296;
        return $r;
    }
    
}