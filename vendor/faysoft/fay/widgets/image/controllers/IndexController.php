<?php
namespace fay\widgets\image\controllers;

use fay\models\tables\FilesTable;
use fay\widget\Widget;
use fay\services\file\FileService;

class IndexController extends Widget{
	public function initConfig($config){
		//默认表单元素
		isset($config['file_id']) || $config['file_id'] = '0';
		isset($config['target']) || $config['target'] = '_blank';
		
		return $this->config = $config;
	}
	
	public function getData(){
		if(!$this->config['file_id']){
			return array(
				'file_id'=>'0',
				'link'=>'',
				'target'=>'',
				'src'=>'',
			);
		}
		
		$file = FilesTable::model()->find($this->config['file_id']);
		
		if(!$file){
			//图片不存在
			$src = FileService::getUrl($file, FileService::PIC_ORIGINAL, array(
				'spare'=>'default',
			));
		}else if(!$file['is_image']){
			//该文件不是图片
			$src = FileService::getThumbnailUrl($file);
		}else if(!empty($this->config['width']) &&
			!empty($this->config['height']) &&
			$this->config['width'] == $file['image_width'] &&
			$this->config['height'] == $file['image_height']
		){
			//有设置宽高，且宽高等于图片真实宽高，直接返回原图
			$src = FileService::getUrl($file, FileService::PIC_ORIGINAL);
		}else{
			//返回缩略图
			$src = FileService::getUrl($file, FileService::PIC_RESIZE, array(
				'dw'=>empty($this->config['width']) ? false : $this->config['width'],
				'dh'=>empty($this->config['height']) ?  false : $this->config['height'],
			));
		}
		
		return array(
			'file_id'=>$this->config['file_id'],
			'link'=>$this->config['link'],
			'target'=>$this->config['target'],
			'src'=>$src,
		);
	}
	
	public function index(){
		$this->renderTemplate(array(
			'data'=>$this->getData(),
		));
	}
}