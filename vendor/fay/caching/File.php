<?php
namespace fay\caching;

/**
 * 文件缓存
 */
class File extends Cache{
	/**
	 * 以某个几率自动删除过期的缓存文件（单位：%）
	 */
	public $gc_probability = 0.1;
	
	/**
	 * 缓存文件权限，默认为775
	 */
	public $file_mode;
	
	/**
	 * 缓存文件夹权限，默认为775
	 */
	public $dir_mode = 0775;
	
	/**
	 * 作为缓存$key的分隔符。
	 */
	public $separator = DS;
	
	/**
	 * 文件缓存不需要前缀，因为每个application已经分开目录了
	 */
	public $key_prefix = '';
	
	/**
	 * 根据$key获取缓存文件路径
	 * @param mix $key
	 */
	protected function getCacheFile($key){
		return APPLICATION_PATH . 'runtimes' . DS . 'cache' . DS . $key;
	}
	
	public function exist($key){
		$file = $this->getCacheFile($this->buildKey($key));
		
		return @filemtime($file) > \F::app()->current_time;
	}
	
	/**
	 * @see \fay\caching\Cache::getValue()
	 */
	protected function getValue($key){
		$file = $this->getCacheFile($this->buildKey($key));
		if(@filemtime($file) > \F::app()->current_time){
			$content = @file_get_contents($file);
			if($content === false){
				return null;
			}else{
				return $content;
			}
		}else{
			return null;
		}
	}
	
	/**
	 * @see \fay\caching\Cache::setValue()
	 */
	protected function setValue($key, $value, $duration){
		$file = $this->getCacheFile($key);
		
		\fay\models\File::createFolder(dirname($file), $this->dir_mode);
		
		if(@file_put_contents($file, $value, LOCK_EX)){
			if($this->file_mode){
				@chmod($file, $this->file_mode);
			}
			if($duration <= 0){
				$duration = 31536000;//一年
			}
			
			return @touch($file, $duration + \F::app()->current_time);
		}else{
			return false;
		}
	}
	
	/**
	 * @see \fay\caching\Cache::deleteValue()
	 */
	protected function deleteValue($key){
		$file = $this->getCacheFile($key);
		
		return @unlink($file);
	}
	
	/**
	 * @see \fay\caching\Cache::flushValues()
	 */
	protected function flushValues($prefix = null){
		$this->gc(true, false, $prefix);
		
		return true;
	}
	
	/**
	 * 垃圾回收机制（garbage collection）
	 * 为防止大量过期缓存文件占用系统空间，可以设定概率自动清理（需要在别处手工调用）
	 * @param bool $force 若为true，则强行清理；若为false，则根据设定搞定概率判断是否清理
	 * @param bool $expired_only 若为true，只清理已过期的缓存文件；若为false，则清空所有缓存文件
	 * @param string $prefix 若非空，则清理以$prefix为前缀的部分子目录
	 */
	public function gc($force = false, $expired_only = true, $prefix = null){
		if($force || mt_rand(0, 1000000) < ($this->gc_probability * 10000)){
			$path = ($prefix ? APPLICATION_PATH . 'runtimes' . DS . 'cache' . DS . $prefix : APPLICATION_PATH . 'runtimes' . DS . 'cache' . DS);
			$this->gcRecursive($path, $expired_only);
		}
	}
	
	/**
	 * 执行缓存清理
	 * @param bool $expired_only 若为true，只清理已过期的缓存文件；若为false，则清空所有缓存文件
	 * @param string $prefix 若非空，则清理以$prefix为前缀的部分子目录
	 */
	protected function gcRecursive($path, $expired_only){
		if(($handle = opendir($path)) !== false){
			while(($file = readdir($handle)) !== false){
				if($file[0] === '.'){
					continue;
				}
				$fullPath = $path . DIRECTORY_SEPARATOR . $file;
				if(is_dir($fullPath)){
					$this->gcRecursive($fullPath, $expired_only);
					if(!$expired_only){
						@rmdir($fullPath);
					}
				}else if(!$expired_only || $expired_only && @filemtime($fullPath) < \F::app()->current_time){
					@unlink($fullPath);
				}
			}
			closedir($handle);
		}
	}
}