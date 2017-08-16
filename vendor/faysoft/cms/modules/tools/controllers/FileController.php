<?php
namespace cms\modules\tools\controllers;
 
use cms\library\ToolsController;
use cms\services\file\QiniuService;
use cms\services\file\WeixinFileService;
use fay\core\Response;

class FileController extends ToolsController{
    /*
     * 从微信下载图片
     * 有微信上传需求的项目，需要在后台跑此进程，将图片从微信服务器下载到本地
     * 一次下载一张，不支持并发，尽量不要起多个进程，可能导致重复下载
     * 此方法不支持指定id下载，会自动从老到新依次下载
     */
    public function downloadFromWeixin(){
        WeixinFileService::download();
    }
    
    /**
     * 将本地图片上传到七牛
     * 必须先在后台配置七牛参数
     * 一次上传一张，不支持并发，尽量不要起多个进程，可能导致重复执行上传，虽然也不会有什么不良后果，有点浪费带宽。
     * 此方法不支持指定id上传，会自动从老到新依次上传
     */
    public function uploadToQiniu(){
        $result = QiniuService::service()->put();
        if($result['status']){
            return Response::notify(Response::NOTIFY_SUCCESS, array(
                'data'=>array(
                    'file_id'=>$result['file']['id']
                )
            ));
        }else{
            return Response::notify(Response::NOTIFY_FAIL, $result['message']);
        }
    }
}