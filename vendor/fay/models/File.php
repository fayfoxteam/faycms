<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Files;
use fay\common\Upload;
use fay\helpers\Image;
use fay\helpers\String;
use fay\core\ErrorException;

/**
 * 文件相关操作类，本类仅包含本地文件操作方法，不集成任何第三方的存储
 */
class File extends Model{
	/**
	 * 原图
	 */
	const PIC_ORIGINAL = 1;
	
	/**
	 * 缩略图
	 */
	const PIC_THUMBNAIL = 2;
	
	/**
	 * 裁剪
	 */
	const PIC_CROP = 3;
	
	/**
	 * 缩放
	 */
	const PIC_RESIZE = 4;
	
	/**
	 * @return File
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function getIconById($id){
		
	}
	
	public function getIconByMimetype($mimetype){
		$mimetypes = \F::config()->get('*', 'mimes');
		$types = array(
			'image'=>array('jpeg', 'jpg', 'jpe', 'png', 'bmp', 'gif'),
			'archive'=>array('rar', 'gz', 'tgz', 'zip', 'tar'),
			'audio'=>array('mp3', 'midi', 'mpga', 'mid', 'aif', 'aiff', 'aifc', 'ram', 'rm',
				'rpm', 'ra', 'rv', 'wav'),
			'code'=>array('php', 'php4', 'php3', 'phtml', 'phps', 'html', 'htm', 'shtml'),
			'document'=>array('txt', 'text', 'log'),
			'video'=>array(),
			'spreadsheet'=>array('csv', 'doc', 'docx', 'xlsx', 'word', 'xsl', 'ppt'),
		);
		foreach($types as $key=>$val){
			foreach($val as $v){
				if(is_array($mimetypes[$v]) && in_array($mimetype, $mimetypes[$v])){
					return $key;
				}else if($mimetypes[$v] == $mimetype){
					return $key;
				}
			}
		}
		return 'default';
	}

	/**
	 * <pre>
	 * 返回一个可访问的文件url
	 * 若是图片
	 *   若是公共文件，直接返回图片真实路径
	 *   若是私有文件，返回图片file/pic方式的一个url
	 * 若不是图片，返回下载地址
	 * 若第二个参数为false，返回相对路径
	 * </pre>
	 */
	public function getUrl($file, $full_url = true){
		if(String::isInt($file)){
			$file = Files::model()->find($file, 'id,raw_name,file_ext,file_path,is_image');
		}
		if($full_url){
			if($file['is_image']){
				if(substr($file['file_path'], 0, 4) == './..'){
					//私有文件，不能直接访问文件
					return \F::app()->view->url('file/pic', array(
						'f'=>$file['id'],
					));
				}else{
					//公共文件，直接返回真实路径
					return \F::app()->view->url() . ltrim($file['file_path'], './') . $file['raw_name'] . $file['file_ext'];
				}
			}else{
				return \F::app()->view->url('file/download', array('id'=>$file['id']));
			}
		}else{
			return $file['file_path'] . $file['raw_name'] . $file['file_ext'];
		}
	}
	
	/**
	 * 返回文件本地完整路径
	 */
	public function getPath($file){
		if(String::isInt($file)){
			$file = Files::model()->find($file, 'raw_name,file_ext,file_path');
		}
		return realpath($file['file_path'] . $file['raw_name'] . $file['file_ext']);
	}
	
	/**
	 * 返回文件缩略图路径
	 * 若是图片，返回图片缩略图路径
	 *   若是公共文件，直接返回图片真实路径
	 *   若是私有文件，返回图片file/pic方式的一个url
	 * 若是其他类型文件，返回文件图标
	 */
	public function getThumbnailUrl($file, $fullpath = true){
		if(String::isInt($file)){
			$file = Files::model()->find($file, 'id,file_type,raw_name,file_path,is_image');
		}
		if(!$file['is_image']){
			//不是图片，返回一张文件类型对应的小图标
			$icon = File::model()->getIconByMimetype($file['file_type']);
			return \F::app()->view->url() . 'assets/images/crystal/' . $icon . '.png';
		}
		if($fullpath){
			if(substr($file['file_path'], 0, 4) == './..'){
				//私有文件，不能直接访问文件
				return \F::app()->view->url('file/pic', array(
					't'=>2,
					'f'=>$file['id'],
				));
			}else{
				//公共文件，直接返回真实路径
				return \F::app()->view->url() . ltrim($file['file_path'], './') . $file['raw_name'] . '-100x100.jpg';
			}
		}else{
			return $file['file_path'].$file['raw_name'].'-100x100.jpg';
		}
	}
	
	/**
	 * 执行上传
	 * @param string $target uploads目录下的某个子目录
	 * @param int|alias|array $cat 分类ID
	 * @param string $private
	 * @param string $allowed_types
	 */
	public function upload($cat = 0, $private = false, $allowed_types = null){
		if($cat){
			if(!is_array($cat)){
				$cat = Category::model()->get($cat, 'id,alias', '_system_file');
			}
			
			if(!$cat){
				throw new ErrorException('fay\models\File::upload传入$cat不存在');
			}
		}else{
			$cat = array(
				'id'=>0,
				'alias'=>'',
			);
		}
		
		$target = $cat['alias'];
		if($target && substr($target, -1) != '/'){
			//目标路径末尾不是斜杠的话，加上斜杠
			$target .= '/';
		}
		
		//是否存入私有文件
		$upload_config['upload_path'] = $private ? './../uploads/' . APPLICATION . '/' . $target . date('Y/m/')
			: './uploads/' . APPLICATION . '/' . $target . date('Y/m/');
		
		//是否指定上传文件类型
		if($allowed_types !== null){
			$upload_config['allowed_types'] = $allowed_types;
		}
		$result = self::createFolder($upload_config['upload_path']);
		$upload = new Upload($upload_config);
		$result = $upload->run();
		if($result !== false){
			if($result['is_image']){
				$data = array(
					'raw_name'=>$result['raw_name'],
					'file_ext'=>$result['file_ext'],
					'file_type'=>$result['file_type'],
					'file_size'=>$result['file_size'],
					'file_path'=>$result['file_path'],
					'client_name'=>$result['client_name'],
					'is_image'=>$result['is_image'],
					'image_width'=>$result['image_width'],
					'image_height'=>$result['image_height'],
					'upload_time'=>\F::app()->current_time,
					'user_id'=>\F::app()->current_user,
					'cat_id'=>$cat['id'],
				);
				$data['id'] = Files::model()->insert($data);
				$src_img = Image::getImage((defined('NO_REWRITE') ? './public/' : '').$data['file_path'].$data['raw_name'].$data['file_ext']);
				$img = Image::resize($src_img, 100, 100);
				imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$data['file_path'].$data['raw_name'].'-100x100.jpg');
				$data['error'] = 0;
				if($private){
					//私有文件通过file/pic访问
					$data['url'] = \F::app()->view->url('file/pic', array('f'=>$data['id']));
					$data['thumbnail'] = \F::app()->view->url('file/pic', array('t'=>2, 'f'=>$data['id']));
				}else{
					//公共文件直接给出真实路径
					$data['url'] = \F::app()->view->url() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
					$data['thumbnail'] = \F::app()->view->url() . ltrim($data['file_path'], './') . $data['raw_name'] . '-100x100.jpg';
					//真实存放路径（是图片的话与url路径相同）
					$data['src'] = \F::app()->view->url() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
				}
			}else{
				$data = array(
					'raw_name'=>$result['raw_name'],
					'file_ext'=>$result['file_ext'],
					'file_type'=>$result['file_type'],
					'file_size'=>$result['file_size'],
					'file_path'=>$result['file_path'],
					'client_name'=>$result['client_name'],
					'is_image'=>$result['is_image'],
					'upload_time'=>\F::app()->current_time,
					'user_id'=>\F::app()->current_user,
					'cat_id'=>$cat['id'],
				);
				$data['id'] = Files::model()->insert($data);
				
				$icon = File::model()->getIconByMimetype($data['file_type']);
				$data['thumbnail'] = \F::app()->view->url().'assets/images/crystal/'.$icon.'.png';
				//下载地址
				$data['url'] = \F::app()->view->url('file/download', array(
					'id'=>$data['id'],
				));
				//真实存放路径
				$data['src'] = \F::app()->view->url() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
			}
			return array(
				'status'=>1,
				'data'=>$data,
			);
		}else{
			return array(
				'status'=>0,
				'data'=>$upload->getErrorMsg(),
			);
		}
	}
	
	/**
	 * 编辑一张图片
	 * @param int|array $file 可以传入文件ID或包含足够信息的数组
	 * @param string $handler 处理方式。resize(缩放)和crop(裁剪)可选
	 * @param array $params
	 *     $params['dw'] 输出宽度
	 *     $params['dh'] 输出高度
	 *     $params['x'] 裁剪时x坐标点
	 *     $params['y'] 裁剪时y坐标点
	 *     $params['w'] 裁剪时宽度
	 *     $params['h'] 裁剪时高度
	 */
	public function edit($file, $handler, $params){
		if(String::isInt($file)){
			$file = Files::model()->find($file);
		}
		
		switch($handler){
			case 'resize':
				if($params['dw'] && !$params['dh']){
					$params['dh'] = $params['dw'] * ($file['image_height'] / $file['image_width']);
				}else if($params['dh'] && !$params['dw']){
					$params['dw'] = $params['dh'] * ($file['image_width'] / $file['image_height']);
				}else if(!$params['dw'] && !$params['dh']){
					$params['dw'] = $file['image_width'];
					$params['dh'] = $file['image_height'];
				}
				
				$img = Image::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
				
				$img = Image::resize($img, $params['dw'], $params['dh']);
				
				//处理过的图片统一以jpg方式保存
				imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'.jpg', isset($params['q']) ? $params['q'] : 75);
				
				//重新生成缩略图
				$img = Image::resize($img, 100, 100);
				imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'-100x100.jpg');
				
				$new_file_size = filesize((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'.jpg');
				
				//更新数据库字段
				Files::model()->update(array(
					'file_ext'=>'.jpg',
					'image_width'=>$params['dw'],
					'image_height'=>$params['dh'],
					'file_size'=>$new_file_size,
				), $file['id']);
				
				//更新返回值字段
				$file['image_width'] = $params['dw'];
				$file['image_height'] = $params['dh'];
				$file['file_size'] = $new_file_size;
				
				if($file['file_ext'] != '.jpg'){
					//若原图不是jpg，物理删除原图
					@unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
				}
				break;
			case 'crop':
				if(!$params['x'] || !$params['y'] || !$params['w'] || !$params['h']){
					throw new ErrorException('fay\models\File::edit方法crop处理缺少必要参数');
				}
				
				if($params['w'] && $params['h']){
					//若参数不完整，则不处理
					$img = Image::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
					
					if($params['dw'] == 0){
						$params['dw'] = $params['w'];
					}
					if($params['dh'] == 0){
						$params['dh'] = $params['h'];
					}
					$img = Image::crop($img, $params['x'], $params['y'], $params['w'], $params['h']);
					if($params['dw'] != $params['w'] || $params['dh'] != $params['h']){
						//如果完全一致，则不需要缩放，但依旧会进行清晰度处理
						$img = Image::resize($img, $params['dw'], $params['dh']);
					}
					
					//处理过的图片统一以jpg方式保存
					imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'.jpg', isset($params['q']) ? $params['q'] : 75);
					
					//重新生成缩略图
					$img = Image::resize($img, 100, 100);
					imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'-100x100.jpg');
					
					$new_file_size = filesize((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'.jpg');
					
					//更新数据库字段
					Files::model()->update(array(
						'file_ext'=>'.jpg',
						'image_width'=>$params['dw'],
						'image_height'=>$params['dh'],
						'file_size'=>$new_file_size,
					), $file['id']);
					
					if($file['file_ext'] != '.jpg'){
						//若原图不是jpg，物理删除原图
						@unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
					}
					
					//更新返回值字段
					$file['image_width'] = $params['dw'];
					$file['image_height'] = $params['dh'];
					$file['file_size'] = $new_file_size;;
				}
				break;
		}
		return $file;
	}

	/**
	 * 获取指定路径下的文件列表，如果第二个参数为true，
	 * 则会递归的列出子目录下的文件
	 * @param String $dir 目录
	 * @param String $recursion
	 */
	public static function getFileList($dir, $recursion = false){
		$filelist = array();
		$real_path = realpath($dir);
		if (is_dir($real_path)) {
			if ($dh = opendir($real_path)) {
				while (($file = readdir($dh)) !== false) {
					if (strpos($file, '.') === 0) {
						continue;
					}
					$full_path = $real_path . DIRECTORY_SEPARATOR . $file;
					$filetype = filetype($full_path);
					$is_dir = $filetype == 'dir';
					$relative_path = str_ireplace(BASEPATH, '', $full_path);
					$relative_path = str_replace('\\', '/', $relative_path);
					$filelist[] = array(
						'name'=>$file,
						'path'=>$full_path,
						'relative_path'=>$relative_path,
						'is_dir'=>$is_dir,
					);
					if($is_dir == true && $recursion == true){
						$subdir = self::getFileList($real_path . DIRECTORY_SEPARATOR . $file, true);
						$filelist = array_merge($filelist, $subdir);
					}
				}
				closedir($dh);
			}
		}
		return $filelist;
	}
	
	/**
	 * 随机产生一个唯一的文件名<br>
	 * 该方法区分大小写，若是windows系统，可修改files表结构，让raw_name字段不区分大小写<br>
	 * 不过文件系统有文件夹分割，重名概率极低，一般问题不大
	 * @param String $path
	 * @param String $ext 扩展名
	 */
	public static function getFilename($path, $ext){
		$filename = String::random('alnum', 5).$ext;
		if (!file_exists($path.$filename)){
			return $filename;
		}else{
			return self::getFilename($path, $ext);
		}
	}
	
	/**
	 * 获取文件名扩展名
	 * 强制转换为小写
	 */
	public static function getFileExt($filename){
		return strtolower(strrchr($filename, '.'));
	}
	
	/**
	 * 创建多级目录
	 * @param string $path 目录
	 * @param string $mode 模式
	 */
	public static function createFolder($path, $mode = 0775){
		if(is_dir($path)) {
			return true;
		}
		$parentDir = dirname($path);
		if(!is_dir($parentDir)){
			static::createFolder($parentDir, $mode);
		}
		$result = mkdir($path, $mode);
		chmod($path, $mode);
		
		return $result;
	}
	
	/**
	 * 删除整个文件夹
	 * 若第二个参数为true，则连同文件夹一同删除（包括自身）
	 * @param string $path
	 * @param string $del_dir
	 * @param number $level
	 * @return boolean
	 */
	public static function deleteFiles($path, $del_dir = false, $level = 0){
		// Trim the trailing slash
		$path = rtrim($path, DIRECTORY_SEPARATOR);
	
		if (!$current_dir = @opendir($path)){
			return false;
		}
	
		while(false !== ($filename = @readdir($current_dir))){
			if ($filename != "." and $filename != ".."){
				if (is_dir($path.DIRECTORY_SEPARATOR.$filename)){
					// Ignore empty folders
					if (substr($filename, 0, 1) != '.'){
						self::deleteFiles($path.DIRECTORY_SEPARATOR.$filename, $del_dir, $level + 1);
					}
				}else{
					unlink($path.DIRECTORY_SEPARATOR.$filename);
				}
			}
		}
		@closedir($current_dir);
	
		if ($del_dir == true){
			return @rmdir($path);
		}
		return true;
	}
	
	/**
	 * 获取文件的一行或前后N行
	 * @param string $file 文件路径
	 * @param int $line 行号
	 * @param int $adjacents 前后行数
	 */
	public static function getFileLine($file, $line, $adjacents = 0){
		if(!file_exists($file)){
			return '';
		}
		$file = file($file);
		if($adjacents){
			$offset = $line - $adjacents - 1;//开始截取位置
			$offset < 0 && $offset = 0;
			$end = $line + $adjacents;//结束截取位置
			$file_line_count = count($file);//文件行数
			$end > $file_line_count && $end = $file_line_count;
			
			$fragment = array_slice($file, $offset, $end - $offset);
			return implode('', $fragment);
		}else{
			return $file[$line - 1];
		}
	}
	
	/**
	 * 创建一个文件。
	 *   若文件不存在，会先创建文件
	 *   若文件存在，会覆盖
	 *   若目录也不存在，则会先创建目录
	 */
	public static function createFile($file, $data, $mode = 0775){
		$dir = dirname($file);
		if(!is_dir($dir)){
			self::createFolder($dir, $mode);
		}
		file_put_contents($file, $data);
		@chmod($file, $mode);
	}
	
	/**
	 * 获取文件下载次数
	 * @param int $file_id 文件ID
	 */
	public static function getDownloads($file_id){
		$file = Files::model()->find($file_id, 'downloads');
		return $file['downloads'];
	}
}