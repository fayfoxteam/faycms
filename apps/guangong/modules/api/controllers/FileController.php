<?php
namespace guangong\modules\api\controllers;

use fay\core\Response;
use cms\services\file\FileService;

class FileController extends \cms\modules\api\controllers\FileController{
    /**
     * 从指定链接获取图片存到本地
     */
    public function uploadFromUrl(){
        //表单验证
        $this->form()->setRules(array(
            array(array('url'), 'required'),
            array(array('url'), 'url'),
        ))->setFilters(array(
            'url'=>'trim',
            'cat'=>'trim',
            'client_name'=>'trim',
        ))->setLabels(array(
            'url'=>'链接地址',
            'cat'=>'分类',
            'client_name'=>'文件名',
        ))->check();
        
        set_time_limit(0);
        $url = $this->form()->getData('url');
        $cat = $this->form()->getData('cat');
        $client_name = $this->form()->getData('client_name');
        
        $data = FileService::service()->uploadFromUrl($url, $cat, true, $client_name);
        return Response::json($data);
    }
    
    public function test(){
        header('Content-Type:image/jpeg');
        echo file_get_contents('http://wx.qlogo.cn/mmopen/fHHbBcmoMRtU7dOgXbKkicspfGaMQqTkz0jXQrr1P3EBicJhtjTpjNAaXzt7C1drzxd6al1WoRoaCkH7J0h2XpOSrHW5P3shFX/0');
        die;
    }
    
    public function test2(){
        $url = 'http://wx.qlogo.cn/mmopen/fHHbBcmoMRtU7dOgXbKkicspfGaMQqTkz0jXQrr1P3EBicJhtjTpjNAaXzt7C1drzxd6al1WoRoaCkH7J0h2XpOSrHW5P3shFX/0';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response =  curl_exec($ch);
        curl_close($ch);
        
        header('Content-Type:image/jpeg');
        echo $response;
    }
}