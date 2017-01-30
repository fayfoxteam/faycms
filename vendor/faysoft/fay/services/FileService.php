<?php
namespace fay\services;

use fay\core\HttpException;
use fay\core\Service;
use fay\helpers\ArrayHelper;
use fay\helpers\FieldHelper;
use fay\helpers\UrlHelper;
use fay\models\tables\FilesTable;
use fay\common\Upload;
use fay\helpers\ImageHelper;
use fay\helpers\StringHelper;
use fay\core\ErrorException;

/**
 * 文件相关操作类，本类仅包含本地文件操作方法，不集成任何第三方的存储
 */
class FileService extends Service{
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
	 * 切割
	 */
	const PIC_CUT = 5;
	
	/**
	 * @param string $class_name
	 * @return FileService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	public function getIconById($id){
		
	}
	
	/**
	 * 根据文件的mimetype类型，获取对应的小图标
	 * @param string $mimetype 例如：image/png
	 * @return int|string
	 */
	public static function getIconByMimetype($mimetype){
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
	 * 返回一个可访问的url
	 * 若指定文件不存在，返回null
	 * 若是图片
	 *   若是公共文件，且不裁剪，返回图片真实url（若上传到七牛，返回的是七牛的url）
	 *   若是私有文件，或进行裁剪，返回图片file/pic方式的一个url（若上传到七牛，返回的是七牛的url）
	 * 若不是图片，返回下载地址
	 * @param int|array $file 可以是文件ID或包含文件信息的数组
	 * @param int $type 返回图片类型。可选原图、缩略图、裁剪图和缩放图。（仅当指定文件是图片时有效）
	 * @param array $options 图片的一些裁剪，缩放参数（仅当指定文件是图片时有效）
	 * @return string url 返回文件可访问的url，若指定文件不存在且未指定替代图，则返回null
	 */
	public static function getUrl($file, $type = self::PIC_ORIGINAL, $options = array()){
		if(StringHelper::isInt($file)){
			if($file <= 0){
				return '';
			}
			$file = FilesTable::model()->find($file, 'id,raw_name,file_ext,file_path,is_image,image_width,image_height,qiniu');
		}
		
		if(!$file){
			//指定文件不存在，返回null
			if(isset($options['spare']) && $spare = \F::config()->get($options['spare'], 'noimage')){
				return UrlHelper::createUrl($spare);
			}else{
				return '';
			}
		}
		
		if($file['is_image']){
			switch($type){
				case self::PIC_THUMBNAIL://缩略图
					if($file['qiniu'] && OptionService::get('qiniu:enabled')){
						//若开启了七牛云存储，且文件已上传，则显示七牛路径
						return QiniuService::service()->getUrl($file, array(
							'dw'=>'100',
							'dh'=>'100',
						));
					}else{
						if(substr($file['file_path'], 0, 4) == './..'){
							//私有文件，不能直接访问文件
							return UrlHelper::createUrl('file/pic', array(
								't'=>self::PIC_THUMBNAIL,
								'f'=>$file['id'],
							));
						}else{
							//公共文件，直接返回真实路径
							return UrlHelper::createUrl() . ltrim($file['file_path'], './') . $file['raw_name'] . '-100x100.jpg';
						}
					}
				break;
				case self::PIC_CROP://裁剪
					$img_params = array(
						't'=>self::PIC_CROP,
					);
					isset($options['x']) && $img_params['x'] = $options['x'];
					isset($options['y']) && $img_params['y'] = $options['y'];
					isset($options['dw']) && $img_params['dw'] = $options['dw'];
					isset($options['dh']) && $img_params['dh'] = $options['dh'];
					isset($options['w']) && $img_params['w'] = $options['w'];
					isset($options['h']) && $img_params['h'] = $options['h'];
					
					ksort($img_params);
					
					return UrlHelper::createUrl('file/pic/f/'.$file['id'], $img_params, false);
				break;
				case self::PIC_RESIZE://缩放
					if($file['qiniu'] && OptionService::get('qiniu:enabled')){
						//若开启了七牛云存储，且文件已上传，则显示七牛路径
						return QiniuService::service()->getUrl($file, array(
							'dw'=>isset($options['dw']) ? $options['dw'] : false,
							'dh'=>isset($options['dh']) ? $options['dh'] : false,
						));
					}else{
						$img_params = array('t'=>self::PIC_RESIZE);
						isset($options['dw']) && $img_params['dw'] = $options['dw'];
						isset($options['dh']) && $img_params['dh'] = $options['dh'];
						
						return UrlHelper::createUrl('file/pic/f/'.$file['id'], $img_params, false);
					}
				break;
				case self::PIC_CUT://缩放
					if($file['qiniu'] && OptionService::get('qiniu:enabled')){
						//若开启了七牛云存储，且文件已上传，则显示七牛路径
						empty($options['dw']) && $options['dw'] = $file['image_width'];
						empty($options['dh']) && $options['dh'] = $file['image_height'];
						
						return QiniuService::service()->getUrl($file, array(
							'dw'=>$options['dw'],
							'dh'=>$options['dh'],
						));
					}else{
						$img_params = array('t'=>self::PIC_RESIZE);
						isset($options['dw']) && $img_params['dw'] = $options['dw'];
						isset($options['dh']) && $img_params['dh'] = $options['dh'];
						
						return UrlHelper::createUrl('file/pic/f/'.$file['id'], $img_params, false);
					}
					break;
				case self::PIC_ORIGINAL://原图
				default:
					if($file['qiniu'] && OptionService::get('qiniu:enabled')){
						//若开启了七牛云存储，且文件已上传，则显示七牛路径
						return QiniuService::service()->getUrl($file);
					}else{
						if(substr($file['file_path'], 0, 4) == './..'){
							//私有文件，不能直接访问文件
							return UrlHelper::createUrl('file/pic', array(
								'f'=>$file['id'],
							));
						}else{
							//公共文件，直接返回真实路径
							return UrlHelper::createUrl() . ltrim($file['file_path'], './') . $file['raw_name'] . $file['file_ext'];
						}
					}
				break;
			}
			
		}else{
			return UrlHelper::createUrl('file/download', array('id'=>$file['id']));
		}
	}
	
	/**
	 * 返回文件本地路径
	 * @param int|array $file 可以是文件ID或包含文件信息的数组
	 * @param bool $realpath 若为true，返回完整路径，若为false，返回相对路径，默认为true
	 * @return string
	 */
	public static function getPath($file, $realpath = true){
		if(StringHelper::isInt($file)){
			$file = FilesTable::model()->find($file, 'raw_name,file_ext,file_path');
		}
		if($realpath){
			return realpath($file['file_path'] . $file['raw_name'] . $file['file_ext']);
		}else{
			return $file['file_path'] . $file['raw_name'] . $file['file_ext'];
		}
	}
	
	/**
	 * 返回文件缩略图链接（此方法可指定缩略图尺寸）
	 * 若是图片，返回图片缩略图路径
	 *   若是公共文件，直接返回图片真实路径
	 *   若是私有文件，返回图片file/pic方式的一个url
	 * 若是其他类型文件，返回文件图标（图标尺寸是固定的）
	 * @param int|array $file 可以是文件ID或包含文件信息的数组
	 * @param array $options 可以指定缩略图尺寸
	 * @return string
	 */
	public static function getThumbnailUrl($file, $options = array()){
		if(StringHelper::isInt($file)){
			$file = FilesTable::model()->find($file, 'id,raw_name,file_ext,file_path,is_image,image_width,image_height,qiniu,file_type');
		}
		
		if(!$file){
			//指定文件不存在，返回null
			return '';
		}
		
		if(!$file['is_image']){
			//不是图片，返回一张文件类型对应的小图标
			$icon = self::getIconByMimetype($file['file_type']);
			return UrlHelper::createUrl() . 'assets/images/crystal/' . $icon . '.png';
		}
		
		if(isset($options['dw']) || isset($options['dh'])){
			return self::getUrl($file, self::PIC_RESIZE, $options);
		}else{
			if(substr($file['file_path'], 0, 4) == './..'){
				//私有文件，不能直接访问文件
				return UrlHelper::createUrl('file/pic', array(
					't'=>2,
					'f'=>$file['id'],
				));
			}else{
				//公共文件，直接返回真实路径
				if($file['qiniu']){
					return QiniuService::service()->getUrl($file, array(
						'dw'=>'100',
						'dh'=>'100',
					));
				}else{
					return UrlHelper::createUrl() . ltrim($file['file_path'], './') . $file['raw_name'] . '-100x100.jpg';
				}
			}
		}
	}
	
	/**
	 * 获取文件缩略图路径（非图片类型没有缩略图，返回false；指定文件不存在返回null）
	 * @param int|array $file 可以是文件ID或包含文件信息的数组
	 * @param bool $realpath 若为true，返回完整路径，若为false，返回相对路径，默认为true
	 * @return mixed 图片类型返回缩略图路径；非图片类型没有缩略图，返回false；指定文件不存在返回null
	 */
	public static function getThumbnailPath($file, $realpath = true){
		if(StringHelper::isInt($file)){
			$file = FilesTable::model()->find($file, 'id,raw_name,file_ext,file_path,is_image,image_width,image_height,qiniu');
		}
		
		if(!$file){
			//指定文件不存在，返回null
			return '';
		}
		
		if(!$file['is_image']){
			//非图片类型返回false
			return false;
		}
		
		if($realpath){
			//返回完整路径
			return realpath($file['file_path'] . $file['raw_name'] . '-100x100.jpg');
		}else{
			//返回相对路径
			return $file['file_path'] . $file['raw_name'] . '-100x100.jpg';
		}
	}
	
	/**
	 * 执行上传
	 * @param int|string|array $cat 分类ID
	 * @param bool $private
	 * @param null|array $allowed_types
	 * @return array
	 * @throws ErrorException
	 */
	public function upload($cat = 0, $private = false, $allowed_types = null){
		if($cat){
			if(!is_array($cat)){
				$cat = CategoryService::service()->get($cat, 'id,alias', '_system_file');
			}
			
			if(!$cat){
				throw new ErrorException('fay\services\FileService::upload传入$cat不存在');
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
		self::createFolder($upload_config['upload_path']);
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
				$data['id'] = FilesTable::model()->insert($data);
				$src_img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$data['file_path'].$data['raw_name'].$data['file_ext']);
				$img = ImageHelper::resize($src_img, 100, 100);
				imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$data['file_path'].$data['raw_name'].'-100x100.jpg');
				$data['error'] = 0;
				if($private){
					//私有文件通过file/pic访问
					$data['url'] = UrlHelper::createUrl('file/pic', array('f'=>$data['id']));
					$data['thumbnail'] = UrlHelper::createUrl('file/pic', array('t'=>self::PIC_THUMBNAIL, 'f'=>$data['id']));
				}else{
					//公共文件直接给出真实路径
					$data['url'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
					$data['thumbnail'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . '-100x100.jpg';
					//真实存放路径（是图片的话与url路径相同）
					$data['src'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
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
				$data['id'] = FilesTable::model()->insert($data);
				
				$icon = self::getIconByMimetype($data['file_type']);
				$data['thumbnail'] = UrlHelper::createUrl().'assets/images/crystal/'.$icon.'.png';
				//下载地址
				$data['url'] = UrlHelper::createUrl('file/download', array(
					'id'=>$data['id'],
				));
				//真实存放路径
				$data['src'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
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
	
	public function uploadFromUrl($url, $cat = 0, $client_name = null, $private = false){
		if($cat){
			if(!is_array($cat)){
				$cat = CategoryService::service()->get($cat, 'id,alias', '_system_file');
			}
			
			if(!$cat){
				throw new ErrorException('fay\services\FileService::upload传入$cat不存在');
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
		$filename = self::getFileName($upload_path, '.jpg');
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
		$data['id'] = FilesTable::model()->insert($data);
		$img = ImageHelper::resize($file, 100, 100);
		imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$data['file_path'].$data['raw_name'].'-100x100.jpg');
		
		$data['error'] = 0;
		if($private){
			//私有文件通过file/pic访问
			$data['url'] = UrlHelper::createUrl('file/pic', array('f'=>$data['id']));
			$data['thumbnail'] = UrlHelper::createUrl('file/pic', array('t'=>self::PIC_THUMBNAIL, 'f'=>$data['id']));
		}else{
			//公共文件直接给出真实路径
			$data['url'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
			$data['thumbnail'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . '-100x100.jpg';
			//真实存放路径（是图片的话与url路径相同）
			$data['src'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
		}
		
		return array(
			'status'=>1,
			'data'=>$data,
		);
	}
	
	/**
	 * 编辑一张图片
	 * @param int|array $file 可以传入文件ID或包含足够信息的数组
	 * @param string $handler 处理方式。resize(缩放)和crop(裁剪)可选
	 * @param array $params
	 *  - $params['dw'] 输出宽度
	 *  - $params['dh'] 输出高度
	 *  - $params['x'] 裁剪时x坐标点
	 *  - $params['y'] 裁剪时y坐标点
	 *  - $params['w'] 裁剪时宽度
	 *  - $params['h'] 裁剪时高度
	 * @return array|bool|int
	 * @throws ErrorException
	 */
	public function edit($file, $handler, $params){
		if(StringHelper::isInt($file)){
			$file = FilesTable::model()->find($file);
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
				
				$img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
				
				$img = ImageHelper::resize($img, $params['dw'], $params['dh']);
				
				//处理过的图片统一以jpg方式保存
				imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'.jpg', isset($params['q']) ? $params['q'] : 75);
				
				//重新生成缩略图
				$img = ImageHelper::resize($img, 100, 100);
				imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'-100x100.jpg');
				
				$new_file_size = filesize((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'.jpg');
				
				//更新数据库字段
				FilesTable::model()->update(array(
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
					throw new ErrorException('fay\services\FileService::edit方法crop处理缺少必要参数');
				}
				
				if($params['w'] && $params['h']){
					//若参数不完整，则不处理
					$img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
					
					if($params['dw'] == 0){
						$params['dw'] = $params['w'];
					}
					if($params['dh'] == 0){
						$params['dh'] = $params['h'];
					}
					$img = ImageHelper::crop($img, $params['x'], $params['y'], $params['w'], $params['h']);
					if($params['dw'] != $params['w'] || $params['dh'] != $params['h']){
						//如果完全一致，则不需要缩放，但依旧会进行清晰度处理
						$img = ImageHelper::resize($img, $params['dw'], $params['dh']);
					}
					
					//处理过的图片统一以jpg方式保存
					imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'.jpg', isset($params['q']) ? $params['q'] : 75);
					
					//重新生成缩略图
					$img = ImageHelper::resize($img, 100, 100);
					imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'-100x100.jpg');
					
					$new_file_size = filesize((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'.jpg');
					
					//更新数据库字段
					FilesTable::model()->update(array(
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
					$file['file_size'] = $new_file_size;
				}
				break;
		}
		return $file;
	}
	
	/**
	 * 获取指定路径下的文件列表，如果第二个参数为true，
	 * 则会递归的列出子目录下的文件
	 * @param string $dir 目录
	 * @param bool $recursion
	 * @return array
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
	 * @param string $path
	 * @param string $ext 扩展名
	 * @return string
	 */
	public static function getFileName($path, $ext){
		$filename = StringHelper::random('alnum', 5).$ext;
		if (!file_exists($path.$filename)){
			return $filename;
		}else{
			return self::getFileName($path, $ext);
		}
	}
	
	/**
	 * 获取文件名扩展名并转换为小写
	 * @param string $filename 文件名
	 * @return string
	 */
	public static function getFileExt($filename){
		return strtolower(strrchr($filename, '.'));
	}
	
	/**
	 * 创建多级目录
	 * @param string $path 目录
	 * @param int $mode 模式
	 * @return bool
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
	 * @param bool|string $del_dir
	 * @param int $level
	 * @return bool
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
	 * @param int $adjacent 前后行数
	 * @return string
	 */
	public static function getFileLine($file, $line, $adjacent = 0){
		if(!file_exists($file)){
			return '';
		}
		$file = file($file);
		if($adjacent){
			$offset = $line - $adjacent - 1;//开始截取位置
			$offset < 0 && $offset = 0;
			$end = $line + $adjacent;//结束截取位置
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
	 * @param string $file
	 * @param string $data
	 * @param int $mode
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
		$file = FilesTable::model()->find($file_id, 'downloads');
		return $file['downloads'];
	}
	
	/**
	 * 返回指定文件是否是图片
	 * @param int $file_id
	 * @return int 返回0|1
	 */
	public static function isImage($file_id){
		$file = FilesTable::model()->find($file_id, 'is_image');
		return $file['is_image'];
	}
	
	/**
	 * 返回一个包含指定字段文件信息的数组
	 * @param $file
	 * @param array $options
	 *  - spare 替代图片（当指定图片不存在时，使用配置的替代图）
	 *  - dw 输出缩略图宽度
	 *  - dh 输出缩略图高度
	 * @param string|array $fields 返回字段，可指定id, url, thumbnail, is_image, width, height, description
	 * @return array
	 */
	public static function get($file, $options = array(), $fields = 'id,url,thumbnail'){
		//解析fields
		$fields = FieldHelper::parse($fields);
		
		if(!is_array($file) && ($file <= 0 ||
			!$file = FilesTable::model()->find($file, 'id,raw_name,file_ext,file_path,is_image,image_width,image_height,qiniu'))
		){
			//显然负数ID不存在，返回默认图数组
			if(isset($options['spare'])){
				//若指定了默认图，则取默认图
				$spare = \F::config()->get($options['spare'], 'noimage');
				if($spare === null){
					//若指定的默认图不存在，返回默认图
					$spare = \F::config()->get('default', 'noimage');
				}
			}else{
				//若未指定默认图，返回默认图
				$spare = \F::config()->get('default', 'noimage');
			}
			
			$return = array();
			if(in_array('id', $fields['fields'])){
				//指定文件不存在，统一返回0
				$return['id'] = '0';
			}
			if(in_array('url', $fields['fields'])){
				if($spare){
					$return['url'] = UrlHelper::createUrl($spare);
				}else{
					$return['url'] = '';
				}
			}
			if(in_array('thumbnail', $fields['fields'])){
				if($spare){
					$return['thumbnail'] = UrlHelper::createUrl($spare);
				}else{
					$return['thumbnail'] = '';
				}
			}
			if(in_array('is_image', $fields['fields'])){
				$return['is_image'] = '0';
			}
			if(in_array('width', $fields['fields'])){
				$return['width'] = '0';
			}
			if(in_array('height', $fields['fields'])){
				$return['height'] = '0';
			}
			if(in_array('description', $fields['fields'])){
				$return['description'] = isset($file['description']) ? $file['description'] : '';
			}
			
			return $return;
		}
		
		$return = array();
		if(in_array('id', $fields['fields'])){
			$return['id'] = $file['id'];
		}
		if(in_array('url', $fields['fields'])){
			$return['url'] = self::getUrl($file);
		}
		if(in_array('thumbnail', $fields['fields'])){
			//如果有头像，将头像图片ID转化为图片对象
			if(isset($fields['extra']['thumbnail']) && preg_match('/^(\d+)x(\d+)$/', $fields['extra']['thumbnail'], $thumbnail_params)){
				$return['thumbnail'] = self::getThumbnailUrl($file, array(
					'dw'=>$thumbnail_params[1],
					'dh'=>$thumbnail_params[2],
				) + $options);
			}else{
				$return['thumbnail'] = self::getThumbnailUrl($file, $options);
			}
		}
		if(in_array('is_image', $fields['fields'])){
			$return['is_image'] = $file['is_image'];
		}
		if(in_array('width', $fields['fields'])){
			$return['width'] = $file['image_width'];
		}
		if(in_array('height', $fields['fields'])){
			$return['height'] = $file['image_height'];
		}
		if(in_array('description', $fields['fields'])){
			$return['description'] = isset($file['description']) ? $file['description'] : '';
		}
		
		return $return;
	}
	
	/**
	 * 批量获取图片对象
	 * @param array $files 数组所有项必须一致（均为数字，或均为文件行数组）
	 *  - 由文件ID构成的一维数组，则会根据文件ID进行搜索
	 *  - 由文件信息对象（其实也是数组）构成的二维数组。至少包含id,raw_name,file_ext,file_path,is_image,image_width,image_height,qiniu字段
	 * @param array $options
	 * @param string $fields 返回字段，可指定id, url, thumbnail, is_image, width, height, description
	 * @return array
	 */
	public static function mget($files, $options, $fields = 'id,url,thumbnail'){
		if(empty($files)){
			return array();
		}
		
		$return = array();
		if(!is_array($files[0])){
			//传入的是文件ID，通过ID获取文件信息
			$file_rows = FilesTable::model()->fetchAll(array(
				'id IN (?)'=>$files,
			), 'id,raw_name,file_ext,file_path,is_image,image_width,image_height,qiniu');
			$file_map = ArrayHelper::column($file_rows, null, 'id');
			
			foreach($files as $f){
				if(isset($file_map[$f])){
					$return[$f] = self::get($file_map[$f], $options, $fields);
				}else{
					//文件ID没搜出来（理论上其实不会这样的）
					$return[$f] = self::get(-1, $options, $fields);
				}
			}
		}else{
			//传入的是文件行数组，无需再搜索数据库
			foreach($files as $f){
				$return[$f['id']] = self::get($f, $options, $fields);
			}
		}
		
		return $return;
	}
}