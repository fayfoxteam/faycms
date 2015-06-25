<?php 
namespace fay\common;

use fay\models\File;

class Upload{
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
		
		if(!$this->isAllowedSize($file['size'])){
			$this->setErrorMsg('文件过大');
			return false;
		}
		
		$this->file_temp = $file['tmp_name'];
		$this->file_size = $file['size'];
		$this->_file_mime_type($file);
		$this->file_type = preg_replace('/^(.+?);.*$/', '\\1', $this->file_type);
		$this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
		
		$this->file_ext = File::getFileExt($file['name']);
		//随机一个唯一文件名
		$this->file_name = File::getFilename($this->upload_path, $this->file_ext);
		//客户端文件名
		$this->client_name = $file['name'];
		
		if(!$this->isAllowedType()){
			$this->setErrorMsg('非法文件类型');
			return false;
		}
		
		//没开启URL重写的话，"./uploads/"这样的路径就不是public下了
		if(defined('NO_REWRITE')){
			$destination = './public/'.$this->upload_path.$this->file_name;
		}else{
			$destination = $this->upload_path.$this->file_name;
		}
		if(move_uploaded_file($file['tmp_name'], $destination)){
			$data = array(
				'file_name'=>$this->file_name,
				'raw_name'=>str_replace($this->file_ext, '', $this->file_name),
				'file_ext'=>$this->file_ext,
				'file_type'=>trim($file['type'], '"'),
				'file_size'=>$file['size'],
				'file_path'=>$this->upload_path,
				'full_path'=>$this->upload_path . $this->file_name,
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
		if(is_array($types) || $types === '*'){
			$this->allowed_types = $types;
		}else{
			$types = explode('|', $types);
			if(is_array($types)){
				$this->allowed_types = $types;
			}else{
				$this->allowed_types = array();
			}
		}
	}
	
	/**
	 * 判断上传的文件是否是允许的文件类型
	 * @param string $type
	 */
	private function isAllowedType(){
		$ext = strtolower(ltrim($this->file_ext, '.'));
		
		//任何情况下不允许直接上传php文件
		if($ext == 'php'){
			return false;
		}
		
		if($this->allowed_types == '*'){
			return true;
		}
		
		if (!in_array($ext, $this->allowed_types)){
			return false;
		}
		
		if(in_array($ext, array('gif', 'jpg', 'jpeg', 'jpe', 'png'), true) && @getimagesize($this->file_temp) === false){
			return false;
		}
		
		//不做mime type类型验证，因为无法确保能真的获取到
		return true;
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

	/**
	 * 从CI上抄来的，效果也不太好，依旧不一定能获取到文件的mime type
	 * File MIME type
	 *
	 * Detects the (actual) MIME type of the uploaded file, if possible.
	 * The input array is expected to be $_FILES[$field]
	 *
	 * @param	array	$file
	 * @return	void
	 */
	protected function _file_mime_type($file)
	{
		// We'll need this to validate the MIME info string (e.g. text/plain; charset=us-ascii)
		$regexp = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';

		/* Fileinfo extension - most reliable method
		 *
		 * Unfortunately, prior to PHP 5.3 - it's only available as a PECL extension and the
		 * more convenient FILEINFO_MIME_TYPE flag doesn't exist.
		 */
		if (function_exists('finfo_file'))
		{
			$finfo = @finfo_open(FILEINFO_MIME);
			if (is_resource($finfo)) // It is possible that a FALSE value is returned, if there is no magic MIME database file found on the system
			{
				$mime = @finfo_file($finfo, $file['tmp_name']);
				finfo_close($finfo);

				/* According to the comments section of the PHP manual page,
				 * it is possible that this function returns an empty string
				 * for some files (e.g. if they don't exist in the magic MIME database)
				 */
				if (is_string($mime) && preg_match($regexp, $mime, $matches))
				{
					$this->file_type = $matches[1];
					return;
				}
			}
		}

		/* This is an ugly hack, but UNIX-type systems provide a "native" way to detect the file type,
		 * which is still more secure than depending on the value of $_FILES[$field]['type'], and as it
		 * was reported in issue #750 (https://github.com/EllisLab/CodeIgniter/issues/750) - it's better
		 * than mime_content_type() as well, hence the attempts to try calling the command line with
		 * three different functions.
		 *
		 * Notes:
		 *	- the DIRECTORY_SEPARATOR comparison ensures that we're not on a Windows system
		 *	- many system admins would disable the exec(), shell_exec(), popen() and similar functions
		 *	  due to security concerns, hence the function_usable() checks
		 */
		if (DIRECTORY_SEPARATOR !== '\\')
		{
			$cmd = function_exists('escapeshellarg')
				? 'file --brief --mime '.escapeshellarg($file['tmp_name']).' 2>&1'
				: 'file --brief --mime '.$file['tmp_name'].' 2>&1';

			if (function_usable('exec'))
			{
				/* This might look confusing, as $mime is being populated with all of the output when set in the second parameter.
				 * However, we only need the last line, which is the actual return value of exec(), and as such - it overwrites
				 * anything that could already be set for $mime previously. This effectively makes the second parameter a dummy
				 * value, which is only put to allow us to get the return status code.
				 */
				$mime = @exec($cmd, $mime, $return_status);
				if ($return_status === 0 && is_string($mime) && preg_match($regexp, $mime, $matches))
				{
					$this->file_type = $matches[1];
					return;
				}
			}

			if ( ! ini_get('safe_mode') && function_usable('shell_exec'))
			{
				$mime = @shell_exec($cmd);
				if (strlen($mime) > 0)
				{
					$mime = explode("\n", trim($mime));
					if (preg_match($regexp, $mime[(count($mime) - 1)], $matches))
					{
						$this->file_type = $matches[1];
						return;
					}
				}
			}

			if (function_usable('popen'))
			{
				$proc = @popen($cmd, 'r');
				if (is_resource($proc))
				{
					$mime = @fread($proc, 512);
					@pclose($proc);
					if ($mime !== FALSE)
					{
						$mime = explode("\n", trim($mime));
						if (preg_match($regexp, $mime[(count($mime) - 1)], $matches))
						{
							$this->file_type = $matches[1];
							return;
						}
					}
				}
			}
		}

		// Fall back to the deprecated mime_content_type(), if available (still better than $_FILES[$field]['type'])
		if (function_exists('mime_content_type'))
		{
			$this->file_type = @mime_content_type($file['tmp_name']);
			if (strlen($this->file_type) > 0) // It's possible that mime_content_type() returns FALSE or an empty string
			{
				return;
			}
		}

		$this->file_type = $file['type'];
	}
}