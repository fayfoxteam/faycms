<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Qiniu;
use fay\core\Response;

class QiniuController extends AdminController{
	public function put(){
		$file_id = $this->input->get('id', 'intval');
		$result = Qiniu::model()->put($file_id);
		
		if($result['status']){
			Response::notify('success', array(
				'message'=>'文件已被上传至七牛',
				'data'=>$result['data'],
				'url'=>Qiniu::model()->getUrl($file_id),
			));
		}else{
			Response::notify('error', array(
				'message'=>'上传七牛出错'.$result['message']->Err,
			));
		}
	}
	
	public function delete(){
		$result = Qiniu::model()->delete($this->input->get('id', 'intval'));
		
		if($result !== true){
			Response::notify('error', array(
				'message'=>'从七牛删除文件出错:'.$result->Err,
			));
		}else{
			Response::notify('success', array(
				'message'=>'文件从七牛删除',
			));
		}
	}
}