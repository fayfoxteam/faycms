<?php
namespace guangong\modules\api\controllers;

use fay\core\HttpException;
use fay\core\Response;
use fay\helpers\ImageHelper;
use fay\models\tables\FilesTable;
use fay\services\CategoryService;
use fay\services\FileService;

class FileController extends \cms\modules\api\controllers\FileController{
	/**
	 * 从指定链接获取图片存到本地
	 */
	public function uploadFromUrl(){
		//表单验证
		$this->form()->setRules(array(
			array(array('url'), 'required'),
			array(array('url'), 'url'),
			array(array('p', 'range', array('range'=>array('0', '1'))))
		))->setFilters(array(
			'url'=>'trim',
			'cat'=>'trim',
			'p'=>'intval',
			'client_name'=>'trim',
		))->setLabels(array(
			'url'=>'链接地址',
			'cat'=>'分类',
			'p'=>'私密空间',
			'client_name'=>'文件名',
		))->check();
		
		set_time_limit(0);
		$url = $this->form()->getData('url');
		$cat = $this->form()->getData('cat');
		$private = !!$this->form()->getData('p');
		$client_name = $this->form()->getData('client_name');
		
		if($cat){
			$cat = CategoryService::service()->get($cat, 'id,alias');
			if(!$cat){
				throw new HttpException('指定的文件分类不存在');
			}
		}else{
			$cat = array(
				'id'=>0,
				'alias'=>'',
			);
		}
		$client_name || $client_name = $url;
		
		$file = @imagecreatefromstring(file_get_contents($url));
		if(!$file){
			throw new HttpException('获取远程文件失败', 500);
		}
		
		$target = $cat['alias'];
		if($target && substr($target, -1) != '/'){
			//目标路径末尾不是斜杠的话，加上斜杠
			$target .= '/';
		}
		$upload_path = $private ? './../uploads/' . APPLICATION . '/' . $target . date('Y/m/')
			: './uploads/' . APPLICATION . '/' . $target . date('Y/m/');
		$filename = FileService::getFileName($upload_path, '.jpg');
		if(defined('NO_REWRITE')){
			$destination = './public/'.$upload_path . $filename;
		}else{
			$destination = $upload_path . $filename;
		}
		
		//存储原图
		imagejpeg($file, $destination);
		
		$data = array(
			'raw_name'=>substr($filename, 0, -4),
			'file_ext'=>'.jpg',
			'file_type'=>'image/jpeg',
			'file_size'=>filesize($destination),
			'file_path'=>$upload_path,
			'client_name'=>$client_name,
			'is_image'=>1,
			'image_width'=>imagesx($file),
			'image_height'=>imagesy($file),
			'upload_time'=>\F::app()->current_time,
			'user_id'=>\F::app()->current_user,
			'cat_id'=>$cat['id'],
		);
		//生成缩略图
		$img = ImageHelper::resize($file, 100, 100);
		imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$data['file_path'].$data['raw_name'].'-100x100.jpg');
		
		$data['id'] = FilesTable::model()->insert($data);
		
		Response::json($data);
	}
}