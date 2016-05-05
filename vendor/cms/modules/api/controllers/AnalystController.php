<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\helpers\Request;
use fay\helpers\StringHelper;
use fay\helpers\Date;
use fay\models\tables\AnalystMacs;
use fay\models\tables\AnalystVisits;

/**
 * 访问统计
 */
class AnalystController extends ApiController{
	public function visit(){
		//防止直接访问（虽然效果不大）
		if(!empty($_SERVER['HTTP_USER_AGENT']) &&
			!empty($_SERVER['HTTP_REFERER']) && $this->input->get('h') == $_SERVER['HTTP_REFERER']){
			
			$trackid = $this->input->get('t', '');
			$refer = $this->input->get('r');
			$url = isset($_SERVER['HTTP_REFERER']) ? rtrim($_SERVER['HTTP_REFERER'], '?') : '';
			$short_url = current(StringHelper::base62($url));
			$date = date('Y-m-d');
			$hour = date('G');
			
			if(empty($_COOKIE['fmac'])){
				//首次访问
				$fmac = StringHelper::random('unique');
				//设置cookie
				setcookie('fmac', $fmac, $this->current_time + 3600 * 24 * 365, '/', $this->config->get('tld'));
				
				//获取搜索引擎信息
				$se = Request::getSearchEngine($refer);
				$mac_id = AnalystMacs::model()->insert(array(
					'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
					'browser'=>$this->input->get('b'),
					'browser_version'=>$this->input->get('bv'),
					'shell'=>$this->input->get('s'),
					'shell_version'=>$this->input->get('sv'),
					'os'=>$this->input->get('os'),
					'ip_int'=>Request::ip2int($this->ip),
					'screen_width'=>$this->input->get('sw', 'intval'),
					'screen_height'=>$this->input->get('sh', 'intval'),
					'url'=>$url,
					'refer'=>$refer,
					'se'=>isset($se['se']) ? $se['se'] : '',
					'keywords'=>isset($se['keywords']) ? $se['keywords'] : '',
					'hash'=>$fmac,
					'create_time'=>$this->current_time,
					'create_date'=>$date,
					'hour'=>$hour,
					'trackid'=>$trackid,
					'site'=>$this->input->get('si', 'intval'),
				));
				
				AnalystVisits::model()->insert(array(
					'mac'=>$mac_id,
					'ip_int'=>Request::ip2int($this->ip),
					'refer'=>$refer,
					'url'=>$url,
					'trackid'=>$trackid,
					'user_id'=>$this->current_user,
					'create_time'=>$this->current_time,
					'create_date'=>$date,
					'hour'=>$hour,
					'site'=>$this->input->get('si', 'intval'),
					'short_url'=>$short_url,
					'HTTP_CLIENT_IP'=>isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '',
					'HTTP_X_FORWARDED_FOR'=>isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '',
					'REMOTE_ADDR'=>isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
				));
			}else{
				//非首次访问
				$mac = AnalystMacs::model()->fetchRow(array(
					'hash = ?'=>$_COOKIE['fmac'],
				), 'id');
				if($mac){
					$today = Date::today();
					//当日重复访问不新增记录，仅递增views
					if($record = AnalystVisits::model()->fetchRow(array(
						'mac = ?'=>$mac['id'],
						'short_url = ?'=>$short_url,
						'create_time > '.$today,
					), 'id')){
						AnalystVisits::model()->incr($record['id'], 'views', 1);
					}else{
						AnalystVisits::model()->insert(array(
							'mac'=>$mac['id'],
							'ip_int'=>Request::ip2int($this->ip),
							'refer'=>$refer,
							'url'=>$url,
							'trackid'=>$trackid,
							'user_id'=>$this->current_user,
							'create_time'=>$this->current_time,
							'create_date'=>$date,
							'hour'=>$hour,
							'site'=>$this->input->get('si'),
							'short_url'=>$short_url,
							'HTTP_CLIENT_IP'=>isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '',
							'HTTP_X_FORWARDED_FOR'=>isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '',
							'REMOTE_ADDR'=>isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
						));
					}
				}else{
					//cookies值异常，清掉cookies
					setcookie('fmac', '', $this->current_time - 3600, '/', $this->config->get('tld'));
				}
			}
		}
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Cache-Control: public');
		header('Pragma: no-cache');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: image/gif');
		echo base64_decode('R0lGODlhAQABAIABAAAAAP///yH5BAEAAAEALAAAAAABAAEAAAICTAEAOw==');//一个像素点的gif图，原图位置./assets/images/hm.gif
	}
}