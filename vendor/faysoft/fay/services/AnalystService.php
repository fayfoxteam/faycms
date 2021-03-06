<?php
namespace fay\services;

use fay\core\Service;
use fay\core\Sql;
use fay\helpers\StringHelper;
use fay\models\tables\AnalystMacsTable;
use fay\models\tables\AnalystVisitsTable;
use fay\models\tables\AnalystCachesTable;

class AnalystService extends Service{
	/**
	 * @var string Y-m-d日期格式
	 */
	public $today;
	
	/**
	 * @param string $class_name
	 * @return AnalystService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	public function __construct(){
		$this->today = date('Y-m-d', \F::app()->current_time);
	}
	
	/**
	 * 以天为单位，统计某一天的新访客，默认为今天
	 * @param string $date Y-m-d日期格式，若为null，则为null，则使用当前日期
	 * @param bool $hour 若为false，则不搜hour字段
	 * @param bool|int $site 若为false，则不搜site字段
	 * @return int
	 */
	public function getNewVisitors($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$macs = AnalystMacsTable::model()->fetchRow(array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		), 'COUNT(*) AS count');
		
		return $macs['count'];
	}
	
	/**
	 * 以天为单位，统计某一天的PV量，默认为今天
	 * @param string $date Y-m-d日期格式，若为null，则为null，则使用当前日期
	 * @param bool|int $hour 若为false，则不搜hour字段
	 * @param bool|int $site 若为false，则不搜site字段
	 * @return int
	 */
	public function getPV($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$pv = AnalystVisitsTable::model()->fetchRow(array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		), 'SUM(views) AS sum');
		
		return empty($pv['sum']) ? 0 : $pv['sum'];
	}
	
	/**
	 * 获取历史访问总量
	 * return int
	 */
	public function getAllPV(){
		$pv = AnalystVisitsTable::model()->fetchRow(array(), 'SUM(views) AS sum');
		
		return empty($pv['sum']) ? '0' : $pv['sum'];
	}
	
	/**
	 * 以天为单位，统计某一天的UV量，默认为今天
	 * @param string $date Y-m-d日期格式，若为null，则为null，则使用当前日期
	 * @param bool|int $hour 若为false，则不搜hour字段
	 * @param bool|int $site 若为false，则不搜site字段
	 * @return int
	 */
	public function getUV($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$uv = AnalystVisitsTable::model()->fetchRow(array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		), 'COUNT(DISTINCT mac) AS count');
		
		return $uv['count'];
	}
	
	/**
	 * 获取某一时段内的独立IP数，默认为当日IP数
	 * @param null|string $date Y-m-d日期格式，若为null，则为null，则使用当前日期
	 * @param bool|int $hour 若为false，则不搜hour字段
	 * @param bool|int $site 若为false，则不搜site字段
	 * @return int
	 */
	public function getIP($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$ip = AnalystVisitsTable::model()->fetchRow(array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		), 'COUNT(DISTINCT ip_int) AS count');
		
		return $ip['count'];
	}
	
	/**
	 * 获取某一时段内的跳出率，默认为当日跳出率
	 * @param null|string $date Y-m-d日期格式，若为null，则为null，则使用当前日期
	 * @param bool|int $hour 若为false，则不搜hour字段
	 * @param bool|int $site 若为false，则不搜site字段
	 * @return int
	 */
	
	public function getBounceRate($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$sub_sql = new Sql();
		$sub_sql->from('analyst_visits', 'mac')
			->where(array(
				'create_date = ?'=>$date,
				'hour = ?'=>$hour,
				'site = ?'=>$site,
			))
			->group('mac')
			->having('COUNT(*) = 1');
		$sql = new Sql();
		$result = $sql->from(array('t'=>$sub_sql), 'COUNT(*) AS count')
			->fetchRow();
		
		$uv = $this->getUV($date, $hour, $site);
		if($uv == 0){
			return 0;
		}else{
			return StringHelper::money($result['count'] * 100 / $uv);
		}
	}
	
	/**
	 * 缓存非当日的访问数据
	 * @param string $date Y-m-d日期格式，若为null，则为null，则使用当前日期
	 * @param bool|int $hour 若为false，则不搜hour字段
	 * @param bool|int $site 若为false，则不搜site字段
	 * @return array
	 */
	public function setCache($date, $hour = false, $site = false){
		if(($date == $this->today && intval($hour) == date('G')) ||
			($date == $this->today && $hour === false)) return false;//当日当时不产生缓存
		
		if($this->getCache($date, $hour, $site)) return false;//不重复生成缓存
		
		$sql = new Sql();
		$result = $sql->from(array('v'=>'analyst_visits'), 'SUM(views) AS pv,COUNT(DISTINCT mac) AS uv,COUNT(DISTINCT ip_int) AS ip')
			->where(array(
				'create_date = ?'=>$date,
				'hour = ?'=>$hour,
				'site = ?'=>$site,
			))
			->fetchRow()
		;
		$data = array(
			'date'=>$date,
			'hour'=>$hour === false ? -1 : $hour,
			'site'=>$site ? $site : 0,
			'pv'=>$result['pv'] ? $result['pv'] : 0,
			'uv'=>$result['uv'] ? $result['uv'] : 0,
			'ip'=>$result['ip'] ? $result['ip'] : 0,
			'new_visitors'=>$this->getNewVisitors($date, $hour, $site),
			'bounce_rate'=>$this->getBounceRate($date, $hour, $site),
		);
		AnalystCachesTable::model()->insert($data);
		return $data;
	}
	
	/**
	 * 获取一条缓存的访问数据
	 * @param string $date Y-m-d日期格式，若为null，则为null，则使用当前日期
	 * @param bool|int $hour 若为false，则不搜hour字段
	 * @param bool|int $site 若为false，则不搜site字段
	 * @return array|bool
	 */
	public function getCache($date, $hour = false, $site = false){
		return AnalystCachesTable::model()->fetchRow(array(
			'date = ?'=>$date,
			'hour = ?'=>$hour === false ? -1 : $hour,
			'site = ?'=>$site ? $site : 0,//site为0视为全站数据
		));
	}
	
	/**
	 * 获取某一天，以小时为单位的访问数据
	 * @param string $date Y-m-d日期格式，若为null，则为null，则使用当前日期
	 * @param bool|int $site 若为false，则不搜site字段
	 * @return array
	 */
	public function getHourCacheByDay($date, $site = false){
		$result = AnalystCachesTable::model()->fetchAll(array(
			'date = ?'=>$date,
			'hour != -1',
			'site = ?'=>$site ? $site : 0,//site为0视为全站数据
		));
		
		$return = array();
		foreach($result as $r){
			$return[$r['hour']] = $r;
		}
		return $return;
	}
	
	/**
	 * 根据客户端MAC地址获取对应记录ID
	 * @param null|string $fmac
	 * @return int
	 */
	public function getMacId($fmac = null){
		$fmac || $fmac = $this->getFMac();
		$mac = AnalystMacsTable::model()->fetchRow(array(
			'fmac = ?'=>$fmac,
		), 'id');
		
		return $mac ? $mac['id'] : 0;
	}
	
	/**
	 * 获取客户端传过来的FMac
	 */
	public function getFMac(){
		if(!empty($_REQUEST['fmac'])){
			return $_REQUEST['fmac'];
		}else if(!empty($_COOKIE['fmac'])){
			return $_COOKIE['fmac'];
		}else{
			return '';
		}
	}
}