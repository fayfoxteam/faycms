<?php
namespace fay\helpers;

class Request{
	/**
	 * 获取客户端IP
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
	 * @param string $ip
	 * @return int
	 */
	public static function ip2int($ip){
		if(!$r = ip2long($ip)) return $r;
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
		if($user_agent === null){
			if(isset($_SERVER['HTTP_USER_AGENT'])){
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
			}else{
				return array('other', '', '', '');
			}
		}
		
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
	 * @return array
	 */
	public static function getSearchEngine($refer = null){
		$refer === null && $refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		
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
			}else if(strpos($parse_url['host'], '.sm.cn') !== false){
				//神马(sm.cn)
				$data['se'] = '神马(sm.cn)';
				if(isset($output['q'])){
					$data['keywords'] = urldecode($output['q']);
				}
			}else if(strpos($parse_url['host'], '.sogou.com') !== false){
				//神马(sm.cn)
				$data['se'] = '搜狗(sogou.com)';
				if(isset($output['query'])){
					$word = $output['query'];
				}else{
					$word = '';
				}
				if(isset($output['ie']) && strtolower($output['ie']) != 'utf8'){
					$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($word));
				}else{
					$data['keywords'] = urldecode($word);
				}
				if(!mb_check_encoding($data['keywords'], 'utf-8')){
					if(mb_check_encoding($data['keywords'], 'gb2312')){
						$data['keywords'] = iconv('gb2312', 'utf-8', $data['keywords']);
					}
				}
			}else if(strpos($parse_url['host'], '.haosou.com') !== false){
				//好搜(haosou.com)
				$data['se'] = '好搜(haosou.com)';
				if(isset($output['q'])){
					$data['keywords'] = urldecode($output['q']);
				}
				if(isset($output['q'])){
					$word = $output['q'];
				}else{
					$word = '';
				}
				if(isset($output['ie']) && strtolower($output['ie']) != 'utf-8'){
					$data['keywords'] = iconv($output['ie'], 'UTF-8', urldecode($word));
				}else{
					$data['keywords'] = urldecode($word);
				}
			}
		}
		return $data;
	}
	
	/**
	 * 是否为安卓系统
	 * @param string $user_agent
	 * @return bool
	 */
	public static function isAndroid($user_agent = null){
		$user_agent === null && $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		return !!stripos($user_agent, 'Android');
	}
	
	/**
	 * 是否为苹果移动设备（不包含mac，但包含ipad）
	 * @param string $user_agent
	 * @return bool
	 */
	public static function isIOS($user_agent = null){
		$user_agent === null && $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		return !!(stripos($user_agent, 'iPhone') || stripos($user_agent, 'iPad') || stripos($user_agent, 'iPod'));
	}
	
	/**
	 * 是否为windows phone
	 * @param string $user_agent
	 * @return bool
	 */
	public static function isWP($user_agent = null){
		$user_agent === null && $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		return !!stripos($user_agent, 'windows phone');
	}
	
	/**
	 * 是否为手机访问
	 * ipad不作为手机
	 * @param string $user_agent
	 * @return bool
	 */
	public static function isMobile($user_agent = null){
		$user_agent === null && $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		return !!(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$user_agent) ||
			preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($user_agent, 0, 4)));
	}
	
	/**
	 * 是否是IE浏览器（IE11也视为IE，因为有兼容模式，debug工具模拟什么的，无法确定到底是IE几）
	 * @param string $user_agent
	 * @return bool
	 */
	public static function isIE($user_agent = null){
		$user_agent === null && $user_agent = $_SERVER['HTTP_USER_AGENT'];
		return !!(preg_match('/MSIE ([\d\.]+)/i', $user_agent) || preg_match('/rv:([\d\.]+)\) like gecko/i', $user_agent));
	}
	
	/**
	 * 判断是否为ajax访问
	 */
	public static function isAjax(){
		if(\F::input()->request('ajax')){
			return true;
		}else{
			if((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') ||
					isset($_SERVER['HTTP_POSTMAN_TOKEN'])//postman发起的请求视为ajax请求
			){
				return true;
			}else{
				return false;
			}
		}
	}
}