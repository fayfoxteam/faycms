<?php
namespace cms\modules\tools\controllers;

use fay\core\Controller;
use fay\models\File;
use fay\models\tables\Files;
use fay\helpers\Image;
use fay\helpers\SecurityCode;
use fay\core\Validator;
use fay\core\HttpException;
use fay\core\Loader;
use fay\helpers\String;

class FileController extends Controller{
	public function pic(){
		$validator = new Validator();
		$check = $validator->check(array(
			array(array('f'), 'required'),
			array(array('t'), 'range', array('range'=>array('1', '2', '3', '4'))),
			array(array('x','y', 'dw', 'dh', 'w', 'h'), 'int'),
		));
		
		if($check !== true){
			header('Content-type: image/jpeg');
			readfile(BASEPATH . 'assets/images/no-image.jpg');
		}
		
		//显示模式
		$t = $this->input->get('t', 'intval', File::PIC_ORIGINAL);
		
		//文件名或文件id号
		$f = $this->input->get('f');
		if(String::isInt($f)){
			if($f == 0){
				$spares = $this->config->get('spares');
				$spare = $spares[$this->input->get('s', null, 'default')];
					
				header('Content-type: image/jpeg');
				readfile(BASEPATH . 'assets/' . $spare);
				die;
			}else{
				$file = Files::model()->find($f);
			}
		}else{
			$file = Files::model()->fetchRow(array('raw = ?'=>$f));
		}

		if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $file['raw_name'] == $_SERVER['HTTP_IF_NONE_MATCH']){
			header('HTTP/1.1 304 Not Modified');
			die;
		}
		
		//设置缓存
		header("Expires: Sat, 26 Jul 2020 05:00:00 GMT");
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $file['upload_time']).' GMT');
		header("Cache-control: max-age=3600");
		header("Pragma: cache");
		header('Etag:'.$file['raw_name']);
		
		switch ($t) {
			case File::PIC_ORIGINAL:
				//直接输出图片
				$this->_pic($file);
				break;
			case File::PIC_THUMBNAIL:
				//输出图片的缩略图
				$this->_thumbnail($file);
				break;
			case File::PIC_CROP:
				/**
				 * 根据起始坐标，宽度及宽高比裁剪后输出图片
				 * @param $_GET['x'] 起始点x坐标
				 * @param $_GET['y'] 起始点y坐标
				 * @param $_GET['dw'] 输出图像宽度
				 * @param $_GET['dh'] 输出图像高度
				 * @param $_GET['w'] 截图图片的宽度
				 * @param $_GET['h'] 截图图片的高度
				 */
				$this->_crop($file);
				break;
			case File::PIC_RESIZE:
				/**
				 * 根据给定的宽高对图片进行裁剪后输出图片
				 * @param $_GET['dw'] 输出图像宽度
				 * @param $_GET['dh'] 输出图像高度
				 * 若仅指定高度或者宽度，则会按比例缩放
				 * 若均不指定，则默认为200*200
				 */
				$this->_resize($file);
				break;
		
			default:
				;
				break;
		}
	}
	
	private function _pic($file){
		if($file !== false){
			if(file_exists((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext'])){
				header('Content-type: '.$file['file_type']);
				readfile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
			}else{
				header('Content-type: image/jpeg');
				readfile(BASEPATH . 'images/no-image.jpg');
			}
		}else{
			header('Content-type: image/jpeg');
			readfile(BASEPATH . 'images/no-image.jpg');
		}
	}
	
	private function _thumbnail($file){
		if($file !== false){
			header('Content-type: '.$file['file_type']);
			readfile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'-100x100.jpg');
		}else{
			$spares = $this->config->get('spares');
			$spare = $spares[$this->input->get('s', null, 'default')];
			
			header('Content-type: image/jpeg');
			readfile(BASEPATH . 'assets/' . $spare);
		}
	}
	
	private function _crop($file){
		//x坐标位置
		$x = $this->input->get('x', 'intval', 0);
		//y坐标
		$y = $this->input->get('y', 'intval', 0);
		//输出宽度
		$dw = $this->input->get('dw', 'intval', 0);
		//输出高度
		$dh = $this->input->get('dh', 'intval', 0);
		//选中部分的宽度
		$w = $this->input->get('w', 'intval');
		//选中部分的高度
		$h = $this->input->get('h', 'intval');
		
		if(!$w || !$h){
			throw new HttpException('不完整的请求', 500);
		}
		
		if($file !== false){
			$img = Image::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
			
			if($dw == 0){
				$dw = $w;
			}
			if($dh == 0){
				$dh = $h;
			}
			$img = Image::crop($img, $x, $y, $w, $h);
			if($dw != $w || $dh != $h){
				$img = Image::resize($img, $dw, $dh);
			}
			
			//处理过的图片统一以jpg方式显示
			header('Content-type: image/jpeg');
			imagejpeg($img, null, $this->input->get('q', 'intval', 75));
		}else{
			//图片不存在，显示一张默认图片吧
		}
	}
	
	private function _resize($file){
		$spares = $this->config->get('spares');
		$spare = $spares[$this->input->get('s', null, 'default')];
		//输出宽度
		$dw = $this->input->get('dw', 'intval');
		//输出高度
		$dh = $this->input->get('dh', 'intval');
		
		if($dw && !$dh){
			$dh = $dw * ($file['image_height'] / $file['image_width']);
		}else if($dh && !$dw){
			$dw = $dh * ($file['image_width'] / $file['image_height']);
		}else if(!$dw && !$dh){
			$dw = $file['image_width'];
			$dh = $file['image_height'];
		}
		
		if($file !== false){
			$img = Image::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
			
			$img = Image::resize($img, $dw, $dh);
			
			//处理过的图片统一以jpg方式显示
			header('Content-type: image/jpeg');
			imagejpeg($img, null, $this->input->get('q', 'intval', 75));
		}else{
			$img = Image::getImage('assets/' . $spare);
			header('Content-type: image/jpeg');
			$img = Image::resize($img, $dw, $dh);
			imagejpeg($img);
		}
	}
	
	public function vcode(){
		$sc = new SecurityCode($this->input->get('l', 'intval', 4), $this->input->get('w', 'intval', 110), $this->input->get('h', 'intval', 40));
		//$sc->ext_line = false;
		$sc->create();
		$this->session->set('vcode', strtolower($sc->randnum));
	}
	
	public function qrcode(){
		Loader::vendor('phpqrcode/qrlib');
		\QRcode::png(base64_decode($this->input->get('data')), false, QR_ECLEVEL_M, 4, 2);
	}
	
	/**
	 * 下载一个文件
	 */
	public function download(){
		if($file_id = $this->input->get('id', 'intval')){
			if($file = Files::model()->find($file_id)){
				if(substr((defined('NO_REWRITE') ? './public/' : '').$file['file_path'], 0, 4) == './..'){
					//私有文件不允许在此方法下载
					throw new HttpException('文件不存在');
				}
				
				//可选下载文件名格式
				if($this->input->get('name') == 'date'){
					$filename = date('YmdHis', $file['upload_time']).$file['file_ext'];
				}else if($this->input->get('name') == 'timestamp'){
					$filename = $file['upload_time'].$file['file_ext'];
				}else if($this->input->get('name') == 'client_name'){
					$filename = $file['client_name'];
				}else{
					$filename = $file['raw_name'].$file['file_ext'];
				}
				
				Files::model()->inc($file_id, 'downloads', 1);
				$data = file_get_contents((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
				if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
					header('Content-Type: "'.$file['file_type'].'"');
					header('Content-Disposition: attachment; filename="'.$filename.'"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header("Content-Transfer-Encoding: binary");
					header('Pragma: public');
					header("Content-Length: ".strlen($data));
				}else{
					header('Content-Type: "'.$file['file_type'].'"');
					header('Content-Disposition: attachment; filename="'.$filename.'"');
					header("Content-Transfer-Encoding: binary");
					header('Expires: 0');
					header('Pragma: no-cache');
					header("Content-Length: ".strlen($data));
				}
				die($data);
			}else{
				throw new HttpException('文件不存在');
			}
		}else{
			throw new HttpException('参数不正确', 500);
		}
	}
}