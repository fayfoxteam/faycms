<?php
namespace fay\helpers;

/**
 * 时间相关帮助方法
 * 此方法全面支持时间伪造，即通过修改F::app()->current_time修改系统当前时间，方便某些特殊场景的测试等需求。
 */
class Date{
	/**
	 * 返回今天零点的时间戳
	 */
	public static function today(){
		return mktime(0, 0, 0, date('m', \F::app()->current_time), date('d', \F::app()->current_time), date('Y', \F::app()->current_time));
	}
	
	/**
	 * 返回明天零点的时间戳
	 */
	public static function tomorrow(){
		return mktime(0, 0, 0, date('m', \F::app()->current_time), date('d', \F::app()->current_time) + 1, date('Y', \F::app()->current_time));
	}
	
	/**
	 * 返回n天后零点的时间戳
	 * @param int $n 天数
	 */
	public static function daysLater($n){
		return mktime(0, 0, 0, date('m', \F::app()->current_time), date('d', \F::app()->current_time) + $n, date('Y', \F::app()->current_time));
	}

	/**
	 * 返回昨天零点的时间戳
	 */
	public static function yesterday(){
		return mktime(0, 0, 0, date('m', \F::app()->current_time), date('d', \F::app()->current_time) - 1, date('Y', \F::app()->current_time));
	}
	
	/**
	 * 返回相n天前零点的时间戳
	 * @param int $n 天数
	 */
	public static function daysbefore($n){
		return mktime(0, 0, 0, date('m', \F::app()->current_time), date('d', \F::app()->current_time) - $n, date('Y', \F::app()->current_time));
	}
	
	/**
	 * 返回本周第一天的零点和最后一天的23点59分59秒的时间戳
	 */
	public static function thisWeek(){
		$result['first_day'] = mktime(0, 0, 0, date('m', \F::app()->current_time), date('d', \F::app()->current_time) - date('N', \F::app()->current_time) + 1, date('Y', \F::app()->current_time));
		$result['last_day'] = mktime(23, 59, 59, date('m', \F::app()->current_time), date('d', \F::app()->current_time) - date('N', \F::app()->current_time) + 7, date('Y', \F::app()->current_time));
		return $result;
	}
	
	/**
	 * 返回本月第一天的零点和最后一天的23点59分59秒的时间戳
	 */
	public static function thisMonth(){
		$result['first_day'] = mktime(0, 0, 0, date('m', \F::app()->current_time), 1, date('y', \F::app()->current_time));
		$result['last_day'] = mktime(23, 59, 59, date('m', \F::app()->current_time)+1, 0, date('y', \F::app()->current_time));
		return $result;
	}
	
	/**
	 * 返回指定月第一天的零点和最后一天的23点59分59秒时间戳
	 * 默认为今年
	 * @param int $month 月份（1-12）
	 * @param int $year 年份，若为null，视为今年（默认为null）
	 */
	public static function month($month, $year = null){
		$year || $year = date('y', \F::app()->current_time);
		$result['first_day'] = mktime(0, 0, 0, $month, 1, $year);
		$result['last_day'] = mktime(23, 59, 59, $month+1, 0, $year);
		return $result;
		
	}
	
	/**
	 * 判断是否是今天
	 * @param string $timestamp Unix时间戳
	 */
	public static function isToday($timestamp) {
		return date('Ymd', $timestamp) == date('Ymd', \F::app()->current_time);
	}
	
	/**
	 * 判断是否是本月
	 * @param string $timestamp Unix时间戳
	 */
	public static function isThisMonth($timestamp) {
		return date('mY', $timestamp) == date('mY', \F::app()->current_time);
	}
	
	/**
	 * 判断是否是今年
	 * @param string $timestamp Unix时间戳
	 */
	public static function isThisYear($timestamp) {
		return date('Y', $timestamp) == date('Y', \F::app()->current_time);
	}
	
	/**
	 * 判断是否是昨天
	 * @param string $timestamp Unix时间戳
	 */
	public static function isYesterday($timestamp) {
		return date('Ymd', $timestamp) == date('Ymd', self::yesterday());
	}


	/**
	 * 判断是否是本周
	 * @param string $timestamp Unix时间戳
	 */
	public static function isThisWeek($timestamp){
		$week = self::thisWeek();
		return ($timestamp > $week['first_day'] && $timestamp < $week['last_day']);
	}
	
	/**
	 * 根据main.php文件中设置的时间格式返回时间字符串
	 * @param string $timestamp Unix时间戳
	 */
	public static function format($timestamp){
		if($timestamp != 0){
			static $format;
			if(!empty($format)){
				return date($format, $timestamp);
			}else{
				$config_date = \F::app()->config->get('date');
				$format = $config_date['format'];
				return date($format, $timestamp);
			}
		}else{
			return null;
		}
		
	}
	
	/**
	 * 返回一个简单美化过的时间，例如：“刚刚”，“10秒前”，“昨天 17:43”，“3天前”等。
	 * @param string $timestamp 时间戳，若不指定或指定为等价于0的值，则返回null
	 */
	public static function niceShort($timestamp = null) {
		if($timestamp == 0){
			return null;
		}
		
		$dv = \F::app()->current_time - $timestamp;
		if($dv < 0){
			//当前时间之后
			$dv = - $dv;
			if($dv < 60){
				//一分钟内
				return $dv.'秒后';
			}else if($dv < 3600){
				//一小时内
				return floor($dv / 60).'分钟后';
			}else if(self::isToday($timestamp)){
				//今天内
				return floor($dv / 3600).'小时后';
			}else if($dv < (\F::app()->current_time - self::today())+86400*6){
				//7天内
				return ceil(($dv - (\F::app()->current_time - self::today())) / 86400) . '天后';
			}else if(self::isThisYear($timestamp)){
				//今年
				return date('n月j日', $timestamp);
			}else{
				return date('y年n月j日', $timestamp);
			}
		}else{
			//当前时间之前
			if($dv < 3){
				return '刚刚';
			}else if($dv < 60){
				//一分钟内
				return $dv.'秒前';
			}else if($dv < 3600){
				//一小时内
				return floor($dv / 60).'分钟前';
			}else if(self::isToday($timestamp)){
				//今天内
				return floor($dv / 3600).'小时前';
			}else if($dv < (\F::app()->current_time - self::today())+86400*6){
				//7天内
				return ceil(($dv - (\F::app()->current_time - self::today())) / 86400) . '天前';
			}else if(self::isThisYear($timestamp)){
				//今年
				return date('n月j日', $timestamp);
			}else{
				return date('y年n月j日', $timestamp);
			}
		}
	}
	
	/**
	 * 返回两个时间戳之间的时差，例如：“1分30秒”，“1小时20分6秒”，“1天3小时16分32秒”。
	 */
	public static function diff($start_time, $end_time){
		$dv = $end_time - $start_time;
		
		if($dv < 60){
			return $dv.'秒';
		}else if($dv < 3600){
			return floor($dv / 60).'分'.($dv % 60).'秒';
		}else if($dv < 86400){
			$remainder = $dv % 3600;
			return floor($dv / 3600).'小时'.floor($remainder / 60).'分'.($remainder % 60).'秒';
		}else{
			$date_remainder = $dv % 86400;
			$minute_remainder = $date_remainder % 3600;
			return floor($dv / 86400).'天'.floor($date_remainder / 3600).'小时'.floor($minute_remainder / 60).'分'.($minute_remainder % 60).'秒';
		}
	}
	
	/**
	 * 当输入为空字符串时，返回空字符串，其它返回时间戳
	 * @param string $timestamp
	 * @return empty_string|number
	 */
	public static function strtotime($timestamp){
		if($timestamp === ''){
			return '';
		}else{
			return strtotime($timestamp);
		}
	}
}