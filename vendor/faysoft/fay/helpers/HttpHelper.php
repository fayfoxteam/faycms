<?php
namespace fay\helpers;

use fay\core\ErrorException;

class HttpHelper{
	
	/**
	 * 拼接url
	 * @param string $base_url 基于的url
	 * @param array $values 参数列表数组
	 * @return string 返回拼接的url
	 * @return string
	 */
	public static function combineURL($base_url, $values){
		return $base_url . '?' . http_build_query($values);
	}
	
	/**
	 * 服务器通过get请求获得内容
	 * @param string $url 请求的url,拼接后的
	 * @return string 请求返回的内容
	 */
	public static function getContents($url){
		if (ini_get("allow_url_fopen") == "1") {
			$response = file_get_contents($url);
		}else{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$response =  curl_exec($ch);
			curl_close($ch);
		}
	
		return $response;
	}
	
	/**
	 * get方式请求资源
	 * @param string $url 基于的baseUrl
	 * @param array $values 参数列表数组
	 * @return string 返回的资源内容
	 */
	public static function get($url, $values = array()){
		$combined = self::combineURL($url, $values);
		return self::getContents($combined);
	}
	
	/**
	 * get方式请求json，解析成数组后返回（若服务端返回非json类型，会抛出一个异常）
	 * @param string $url
	 * @param array $values
	 * @return mixed
	 * @throws ErrorException
	 */
	public static function getJson($url, $values = array()){
		$response = trim(self::get($url, $values));
		
		if($response == 'null'){
			//若返回的json就是null，返回null
			return null;
		}
		
		$response = json_decode($response, true);
		if(!$response){
			throw new ErrorException('请求JSON数据格式异常');
		}
		
		return $response;
	}
	
	/**
	 * post方式请求资源
	 * @param string $url 请求地址
	 * @param array $post_fields 请求参数
	 * @return bool
	 */
	public static function post($url, $post_fields = null){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		
		if (is_array($post_fields) && 0 < count($post_fields)){
			$postBodyString = "";
			$postMultipart = false;
			foreach ($post_fields as $k => $v){
				if("@" != substr($v, 0, 1)){//判断是不是文件上传
					$postBodyString .= "$k=" . urlencode($v) . "&";
				}else{//文件上传用multipart/form-data，否则用www-form-urlencoded
					$postMultipart = true;
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart){
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
			}else{
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		$response = curl_exec($ch);
		
		if (curl_errno($ch)){
			return false;
			//throw new Exception(curl_error($ch),0);
		}else{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode){
				return $httpStatusCode;
				//throw new Exception($response,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $response;
	}
}