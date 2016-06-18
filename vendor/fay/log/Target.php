<?php
namespace fay\log;

use fay\core\ErrorException;
use fay\helpers\Request;

abstract class Target{
	/**
	 * 是否启用
	 */
	public $enabled = true;
	
	/**
	 * 该容器会记录的日志等级，若为0，则记录所有日志
	 */
	private $_levels = 0;
	
	/**
	 * 该容器会记录的日志分类，若为空，则记录所有分类。默认为空。
	 * 注：可以用通配符的方式匹配，例如：fay*
	 */
	public $categories = array();
	
	/**
	 * 该容器不会记录except中指定的分类。若一个日志分类同时匹配categories和except，则不会记录该条日志。
	 * 同categories，except也可以使用通配符
	 */
	public $except = array();
	
	/**
	 * 将日志写入容器
	 * @param $messages
	 */
	abstract public function export($messages);
	
	/**
	 * 初始化工作
	 * @param array $options
	 */
	public function init($options){
		foreach($options as $key => $option){
			$this->{$key} = $option;
		}
	}
	
	/**
	 * 过滤掉该容器不记录的日志，并将记录写入容器
	 * @param string $messages
	 */
	public function collect($messages){
		$messages = $this->filterMessages($messages);
		
		$this->export($messages);
	}

	/**
	 * 获取当前日志容器会记录的日志等级
	 * @return int
	 */
	public function getLevels(){
		return $this->_levels;
	}
	
	/**
	 * 设置当前日志容器会记录的日志等级
	 * @param int $levels
	 * @throws ErrorException
	 */
	public function setLevels($levels){
		static $levelMap = array(
			'error' => Logger::LEVEL_ERROR,
			'warning' => Logger::LEVEL_WARNING,
			'info' => Logger::LEVEL_INFO,
		);
		if (is_array($levels)) {
			$this->_levels = 0;
			foreach ($levels as $level) {
				if (isset($levelMap[$level])) {
					$this->_levels |= $levelMap[$level];
				} else {
					throw new ErrorException("指定的日志等级不存在: $level");
				}
			}
		} else {
			$this->_levels = $levels;
		}
	}
	
	/**
	 * 根据日志等级，过滤掉不需要记录的日志
	 * @param array $messages
	 * @return array
	 */
	public function filterMessages($messages){
		foreach($messages as $i => $message){
			if ($this->_levels && !($this->_levels & $message[1])) {
				unset($messages[$i]);
				continue;
			}
			
			$matched = empty($this->categories);
			foreach($this->categories as $category){
				if ($message[2] === $category || substr($category, -1) === '*' && strpos($message[2], rtrim($category, '*')) === 0) {
					$matched = true;
					break;
				}
			}
			
			if($matched){
				foreach($this->except as $category){
					$prefix = rtrim($category, '*');
					if(strpos($message[2], $prefix) === 0 && ($message[2] === $category || $prefix !== $category)){
						$matched = false;
						break;
					}
				}
			}
			
			if (!$matched) {
				unset($messages[$i]);
			}
		}
		
		return $messages;
	}

	/**
	 * 格式化日志
	 * @param array $message the log message to be formatted.
	 * @return string the formatted message
	 */
	public function formatMessage($message){
		list($text, $level, $category, $timestamp) = $message;
		$level = Logger::getLevelName($level);
		if(!is_string($text)){
			$text = var_export($text, true);
		}
		
		$ip = Request::getIP();
		//并不是所有情况下都能获取到用户ID，所以这只是个参考
		$user = empty(\F::app()->current_user) ? 'no login' : 'user:' . \F::app()->current_user;
		
		return date('H:i:s', $timestamp) . " [{$ip}][{$user}][{$level}][{$category}] $text";
	}
}