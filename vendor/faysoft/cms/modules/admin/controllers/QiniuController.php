<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\file\QiniuService;
use fay\core\Response;

class QiniuController extends AdminController{
    public function put(){
        $file_id = $this->input->get('id', 'intval');
        $result = QiniuService::service()->put($file_id);
        
        if($result['status']){
            Response::notify('success', array(
                'message'=>'文件已被上传至七牛',
                'data'=>$result['data'] + array('url'=>QiniuService::service()->getUrl($file_id)),
            ));
        }else{
            Response::notify('error', array(
                'message'=>'上传七牛出错：'.$result['message'],
            ));
        }
    }
    
    public function delete(){
        $result = QiniuService::service()->delete($this->input->get('id', 'intval'));
        
        if($result !== true){
            Response::notify('error', array(
                'message'=>'从七牛删除文件出错：'.$result,
            ));
        }else{
            Response::notify('success', array(
                'message'=>'文件从七牛删除',
            ));
        }
    }
}