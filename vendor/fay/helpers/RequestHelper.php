<?php
namespace fay\helpers;

class RequestHelper{
	/**
	 * 获取客户端IP
	 * @return ip
	 */
	public static function getIP(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$arr = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
			foreach($arr as $a){
				if(substr($a, 0, 2) == '10'){
					continue;
				}else if(substr($a, 0, 3) == '192'){
					continue;
				}else if(substr($a, 0, 3) == '172' && substr($a, 4, 2) >= 16 && substr($a, 4, 2) <= 31){
					continue;
				}else{
					return trim($a);
				}
			}
		}
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'cli';
	}
	
	/**
	 * 将ip转换为int存储，返回32位机器的int值
	 * @param ip $ip
	 * @return int
	 */
	public static function ip2int($ip){
		if(!$r = ip2long($ip)) return 0;
		if($r > 2147483647)
			$r -= 4294967296;
		return $r;
	}
	
	/**
	 * 根据$_SERVER['HTTP_USER_AGENT']获取浏览器类型
	 * @param string $user_agent
	 * @return array
	 */
	public static function getBrowser($user_agent = null){
		$user_agent === null && $user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		if(preg_match('/MicroMessenger\/([\.\w]+)$/i', $user_agent, $s)){
			$s = array('MicroMessenger', $s[1]);
		}else if(preg_match('/MQQBrowser\/([\d\.]+)/i', $user_agent, $s)){
			$s = array('MQQBrowser', $s[1]);
		}else if(preg_match('/QQBrowser\/([\d\.]+)/i', $user_agent, $s)){
			$s = array('QQBrowser', $s[1]);
		}else if(preg_match('/QQ\/([\d\.]+)$/i', $user_agent, $s)){
			$s = array('QQ', $s[1]);
		}else if(preg_match('/QQ\/([\d\.]+)$/i', $user_agent, $s)){
			$s = array('QQ', $s[1]);
		}else if(preg_match('/Qzone\/([\w_\.]+)/i', $user_agent, $s)){
			$s = array('Qzone', $s[1]);
		}else if(preg_match('/TaoBrowser\/([\d\.]+)/i', $user_agent, $s)){
			$s = array('Tao', $s[1]);
		}else if(preg_match('/BIDUBrowser\/([\w\.]+)/i', $user_agent, $s)){
			$s = array('BaiDu', $s[1]);
		}else if(preg_match('/LBBROWSER/i', $user_agent, $s)){
			$s = array('LBBROWSER', '');
		}else if(preg_match('/Maxthon[\/ ]([\d\.]+)/i', $user_agent, $s)){
			$s = array('Maxthon', $s[1]);
		}else if(preg_match('/SE ([\w\.]+) MetaSr 1.0/i', $user_agent, $s)){
			$s = array('Sougou', $s[1]);
		}else if(preg_match('/TheWorld ([\d\.]+)/i', $user_agent, $s)){
			$s = array('TheWorld', $s[1]);
		}else if(preg_match('/TheWorld/i', $user_agent, $s)){
			$s = array('TheWorld', '');
		}else if(preg_match('/2345Explorer ([\d\.]+)/i', $user_agent, $s)){
			$s = array('2345Explorer', $s[1]);
		}else if(preg_match('/360SE/i', $user_agent, $s)){
			$s = array('360SE', '');
		}else{
			$s = array('', '');
		} 
		
		if(preg_match('/rv:([\d\.]+)\) like gecko/i', $user_agent, $b)) return array('IE', $b[1], $s[0], $s[1]);
		if(preg_match('/MSIE ([\d\.]+)/i', $user_agent, $b)) return array('IE', $b[1], $s[0], $s[1]);
		
		if(preg_match('/Firefox\/([\d\.]+)/i', $user_agent, $b)) return array('Firefox', $b[1], $s[0], $s[1]);
		if(preg_match('/Chrome\/([\d\.]+)/i', $user_agent, $b)) return array('Chrome', $b[1], $s[0], $s[1]);
		if(preg_match('/Opera.([\d\.]+)/i', $user_agent, $b)) return array('Opera', $b[1], $s[0], $s[1]);
		if(preg_match('/Safari\/([\d\.]+)/i', $user_agent, $b)) return array('Safari', $b[1], $s[0], $s[1]);
		
		return array('other', '', $s[0], $s[1]);
	}
	
	/**
	 * 根据$_SERVER['HTTP_USER_AGENT']判断是否是蜘蛛访问
	 */
	public static function isSpider(){
		if(!isset($_SERVER['HTTP_USER_AGENT'])){
			return false;
		}
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if (!empty($agent)) {
			$spiders = \F::app()->config->get('*', 'spiders');
			foreach($spiders as $val) {
				$str = strtolower($val);
				if (strpos($agent, $str) !== false) {
					return $val;
				}
			}
		}
		return false;
	}
	
	/**
	 * 根据$_SERVER['HTTP_REFERER']判断来源是否是搜索引擎
	 * @param string $refer
	 * @return multitype:string
	 */
	public static function getSearchEngine($refer = null){
		$refer === null && $refer = $_SERVER['HTTP_REFERER'];
		$parse_url = parse_url($refer);
		$data = array();
		if(isset($parse_url['host']) && isset($parse_url['query'])){
			parse_str($parse_url['query'], $output);
			if(strpos($parse_url['host'], '.soso.') !== false){
				//soso搜索
				$data['se'] = 'soso';
				if(isset($output['w'])){
					if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
						$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($output['w']));
					}else{
						setcookie('refer', 'soso:'.urldecode($output['w']), $this->current_time + 86400 * 30, '/');
						$data['keywords'] = urldecode($output['w']);
					}
				}
			}else if(strpos($parse_url['host'], '.so.') !== false){
				//360搜索
				$data['se'] = '360';
				if(isset($output['q'])){
					if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
						$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($output['q']));
					}else{
						$data['keywords'] = urldecode($output['q']);
					}
				}
			}else if(strpos($parse_url['host'], '.baidu.') !== false){
				//百度
				if(strpos($parse_url['host'], 'm.baidu') !== false){
					//百度手机
					$data['se'] = 'm.baidu';
					if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
						$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($output['word']));
					}else{
						$data['keywords'] = urldecode($output['word']);
					}
				}else{
					//百度网页
					$data['se'] = 'baidu';
					if(isset($output['wd'])){
						$word = $output['wd'];
					}else if(isset($output['word'])){
						$word = $output['word'];
					}else{
						$word = '';
					}
					if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
						$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($word));
					}else{
						$data['keywords'] = urldecode($word);
					}
				}
			}else if(strpos($parse_url['host'], '.google.') !== false){
				//谷歌搜索
				$data['se'] = 'google';
				if(isset($output['q'])){
					$data['keywords'] = urldecode($output['q']);
				}
			}
		}
		return $data;
	}
}