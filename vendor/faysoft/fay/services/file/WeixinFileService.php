<?php
namespace fay\services\file;

use fay\core\ErrorException;
use fay\core\HttpException;
use fay\core\Service;
use fay\helpers\ImageHelper;
use fay\models\tables\FilesTable;
use fay\services\CategoryService;
use fay\services\OptionService;
use fay\services\wechat\core\AccessToken;

/**
 * 文件相关操作类，本类仅包含本地文件操作方法，不集成任何第三方的存储
 */
class WeixinFileService extends Service{
	const GET_MEDIA_URL = 'http://file.api.weixin.qq.com/cgi-bin/media/get';
	
	/**
	 * @param string $class_name
	 * @return WeixinFileService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 添加文件，先将微信的server_id存入files表，等到后台服务去微信服务器下载文件
	 * @param $server_id
	 * @param int $cat_id
	 * @param string $client_name
	 * @return int
	 * @throws ErrorException
	 */
	public static function add($server_id, $cat_id = 0, $client_name = ''){
		if($cat_id){
			if(!CategoryService::service()->get($cat_id, 'id', '_system_file')){
				throw new ErrorException('fay\services\file\WeixinFileService::addFile传入$cat_id不存在');
			}
		}
		
		return FilesTable::model()->insert(array(
			'weixin_server_id'=>$server_id,
			'cat_id'=>$cat_id,
			'client_name'=>$client_name,
			'upload_time'=>\F::app()->current_time,
			'user_id'=>\F::app()->current_user,
			'is_image'=>1,
		));
	}
	
	/**
	 * 从微信服务器下载文件到本地
	 * 目前不支持并发，后台跑一个服务就好了，并发会导致重复下载
	 * @param int $file_id
	 * @return int
	 * @throws HttpException
	 */
	public static function download($file_id = 0){
		if(!$file_id){
			//若$file_id为0，则尝试去files表获取老的一条weixin_server_id非空的数据
			$file = FilesTable::model()->fetchRow(array(
				"weixin_server_id != ''"
			), '*', 'id');
		}else{
			$file = FilesTable::model()->find($file_id);
		}
		if(!$file || !$file['weixin_server_id']){
			//未获取到为下载文件，或指定文件已下载，直接返回0
			return 0;
		}
		
		if($file['cat_id']){
			$cat = CategoryService::service()->get($file['cat_id'], 'id,alias', '_system_file');
			//文件入库之后，分类被删除（其实不太可能出现这种情况）
			$cat || $cat = array(
				'id'=>0,
				'alias'=>'',
			);
		}else{
			$cat = array(
				'id'=>0,
				'alias'=>'',
			); 
		}
		
		$url = self::getUrl($file['weixin_server_id']);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$response = curl_exec($ch);
		curl_close($ch);
		
		$server_file = @imagecreatefromstring($response);
		if(!$server_file){
			throw new HttpException('获取远程文件失败', 500);
		}
		
		$target = $cat['alias'] ? $cat['alias'] . '/' : '';
		$upload_path = './uploads/' . APPLICATION . '/' . $target . date('Y/m/');
		//若指定目录不存在，则创建目录
		FileService::createFolder($upload_path);
		$filename = FileService::getFileName($upload_path, '.jpg');
		if(defined('NO_REWRITE')){
			$destination = './public/'.$upload_path . $filename;
		}else{
			$destination = $upload_path . $filename;
		}
		
		//存储原图
		imagejpeg($server_file, $destination);
		
		//更新数据库数据
		$data = array(
			'raw_name'=>substr($filename, 0, -4),
			'file_ext'=>'.jpg',
			'file_type'=>'image/jpeg',
			'file_size'=>filesize($destination),
			'file_path'=>$upload_path,
			'is_image'=>1,
			'image_width'=>imagesx($server_file),
			'image_height'=>imagesy($server_file),
			'cat_id'=>$cat['id'],
			'weixin_server_id'=>'',
		);
		FilesTable::model()->update($data, $file['id']);
		
		//存储缩略图
		$img = ImageHelper::resize($server_file, 100, 100);
		imagejpeg($img, (defined('NO_REWRITE') ? './public/' : '').$data['file_path'].$data['raw_name'].'-100x100.jpg');
		
		return $file['id'];
	}
	
	/**
	 * 根据server_id获取可访问的微信服务器图片地址
	 * @param $server_id
	 * @return string
	 * @throws ErrorException
	 */
	public static function getUrl($server_id){
		//获取Access Token
		$app_config = OptionService::getGroup('oauth:weixin');
		if(!$app_config['app_id'] || !$app_config['app_secret']){
			throw new ErrorException('尝试获取微信图片url，但微信公众号参数未设置');
		}
		
		$access_token = new AccessToken($app_config['app_id'], $app_config['app_secret']);
		
		//从微信服务器获取文件
		return self::GET_MEDIA_URL . "?access_token={$access_token->getToken()}&media_id={$server_id}";
	}
}