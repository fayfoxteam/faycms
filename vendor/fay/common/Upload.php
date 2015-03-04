<?php 
namespace fay\common;

use fay\core\FBase;
use fay\models\File;

class Upload extends FBase{
	private $upload_path;
	private $allowed_types = array();
	private $max_size;
	
	private $error_msg = array();
	
	/**
	 * 构造函数
	 * 设置参数信息
	 * @param array $config 未设置的选项默认读取配置文件中的设置
	 */
	public function __construct($config = array()){
		$default_config = \F::app()->config->get('upload');
		isset($config['upload_path']) ? $this->upload_path = $config['upload_path'] : $this->upload_path = $default_config['upload_path'];
		isset($config['allowed_types']) ? $this->setAllowedTypes($config['allowed_types']) : $this->setAllowedTypes($default_config['allowed_types']);
		isset($config['max_size']) ? $this->max_size = $config['max_size'] : $this->max_size = $default_config['max_size'];
		
	}
	
	/**
	 * 执行上传操作
	 * 若成功，则返回上传文件的各种属性信息
	 * 若失败，则设置错误信息并返回false
	 * @param string $field
	 */
	public function run($field = false){
		if($field === false){
			$file = array_shift($_FILES);
			if($file === null){
				$this->setErrorMsg('没有文件被上传');
				//此处有个BUG，
				//如果上传文件大于php.ini里post_max_size（即便此文件小于upload_max_filesize），
				//则也会出现$_FILES数组为空的现象，此时也会提示为没有文件被上传
				return false;
			}
		}else{
			if(isset($_FILES[$field])){
				$file = $_FILES[$field];
			}else{
				$this->setErrorMsg('没有文件被上传');
				return false;
			}
		}

		if(!is_uploaded_file($file['tmp_name'])){
			$error = (!isset($file['error'])) ? 4 : $file['error'];

			switch($error){
				case 1:	// UPLOAD_ERR_INI_SIZE
					$this->setErrorMsg('上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值');
					break;
				case 2: // UPLOAD_ERR_FORM_SIZE
					$this->setErrorMsg('上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值');
					break;
				case 3: // UPLOAD_ERR_PARTIAL
					$this->setErrorMsg('文件只有部分被上传');
					break;
				case 4: // UPLOAD_ERR_NO_FILE
					$this->setErrorMsg('没有文件被上传');
					break;
				case 6: // UPLOAD_ERR_NO_TMP_DIR
					$this->setErrorMsg('找不到临时文件夹');
					break;
				case 7: // UPLOAD_ERR_CANT_WRITE
					$this->setErrorMsg('文件写入失败');
					break;
				case 8: // UPLOAD_ERR_EXTENSION
					$this->setErrorMsg('upload_stopped_by_extension');
					break;
				default :   $this->setErrorMsg('没有文件被上传');
					break;
			}
			return false;
		}
		
		if(!$this->isAllowedType($file['type'])){
			$this->setErrorMsg('非法文件类型');
			return false;
		}
		
		if(!$this->isAllowedSize($file['size'])){
			$this->setErrorMsg('文件过大');
			return false;
		}
		$ext = File::getFileExt($file['name']);
		$filename = File::getFilename($this->upload_path, $ext);
		
		//没开启URL重写的话，"./uploads/"这样的路径就不是public下了
		if(defined('NO_REWRITE')){
			$destination = './public/'.$this->upload_path.$filename;
		}else{
			$destination = $this->upload_path.$filename;
		}
		if( move_uploaded_file($file['tmp_name'], $destination)){
			$data = array(
				'file_name'=>$filename,
				'raw_name'=>str_replace($ext, '', $filename),
				'file_ext'=>$ext,
				'file_type'=>trim($file['type'], '"'),
				'file_size'=>$file['size'],
				'file_path'=>$this->upload_path,
				'full_path'=>$this->upload_path . $filename,
				'client_name'=>$file['name'],
			);
			$data = array_merge($data, $this->setImgProperties($destination));
			return $data;
		}else{
			$this->setErrorMsg('未知的错误类型');
			return false;
		}
	}
	
	/**
	 * 获取错误信息
	 */
	public function getErrorMsg(){
		return $this->error_msg;
	}
	
	/**
	 * 设置允许的文件类型
	 * 若为*，则允许所有类型的文件
	 * $types参数为允许的文件类型数组，一般为文件扩展名
	 * 自动读取config文件夹中的mimes.php文件，转换为标准mimetype类型
	 * @param mix $types
	 */
	private function setAllowedTypes($types){
		if(!is_array($types) && $types == '*'){
			$this->allowed_types = '*';
		}else{
			$allowed_types_array = explode('|', $types);
			$mimes = \F::app()->config->get('*', 'mimes');
			foreach($allowed_types_array as $type){
				if(is_array( $mimes[$type] )){
					$this->allowed_types = array_merge($this->allowed_types, $mimes[$type]);
				}else{
					$this->allowed_types[] = $mimes[$type];
				}
			}
		}
	}
	
	/**
	 * 判断上传的文件是否是允许的文件类型
	 * @param string $type
	 */
	private function isAllowedType($type){
		if($this->allowed_types == '*'){
			return true;
		}else{
			return in_array($type, $this->allowed_types);
		}
	}
	
	/**
	 * 判断上传的文件大小是否符合设置
	 * @param string $size
	 */
	private function isAllowedSize($size){
		if ($this->max_size != 0 && $size > $this->max_size){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 设置错误信息
	 * @param string $content
	 */
	private function setErrorMsg($content){
		$this->error_msg[] = $content;
	}
	
	/**
	 * 获取图片相关属性数组，若不是图片则将is_image设为flase，不设置其他属性值
	 * @param string $path
	 */
	private function setImgProperties($path){
		$x = explode('.', $path);
		if(in_array(strtolower(array_pop($x)), array('jpg', 'png', 'jpeg', 'gif')) && false !== ($D = @getimagesize($path))){
			$this->is_image = true;
			$this->image_width = $D[0];
			$this->image_height = $D[1];
			$this->image_mime_type = $D['mime'];
			return array(
				'is_image'=>true,
				'image_width'=>$D[0],
				'image_height'=>$D[1],
				'image_mime_type'=>$D['mime'],
			);
		}else{
			return array(
				'is_image'=>false,
			);
		}
	}
}