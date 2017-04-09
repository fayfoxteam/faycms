<?php
namespace fay\log;

use fay\core\ErrorException;
use cms\services\file\FileService;

class FileTarget extends Target{
    /**
     * 日志文件路径（runtimes/logs目录下的相对路径）默认为app.Y-m-d.log
     */
    public $logFile;
    
    /**
     * 日志文件的读写权限，若设置了读写权限，则会尝试用chmod命令去设置权限，若未设置，则跟随系统。
     */
    public $fileMode;
    
    /**
     * 目录权限。若置顶的logFile在二级目录下，且此目录不存在，则会尝试创建，并通过chmod命令指定为此权限级别。
     */
    public $dirMode = 0775;
    
    /**
     * 将日志写入文件
     * @param string $messages
     * @throws ErrorException
     */
    public function export($messages){
        if(!$messages){
            //若无可记录的日志，直接返回
            return;
        }
        $this->logFile || $this->logFile = APPLICATION_PATH . 'runtimes/logs/app.'.date('Y-m-d').'.log';
        
        $logPath = dirname($this->logFile);
        if (!is_dir($logPath)) {
            FileService::createFolder($logPath, $this->dirMode);
        }
        
        $text = implode("\n", array_map(array($this, 'formatMessage'), $messages)) . "\n";
        if (($fp = @fopen($this->logFile, 'a')) === false) {
            throw new ErrorException("日志文件写入失败: {$this->logFile}");
        }
        @flock($fp, LOCK_EX);
        @fwrite($fp, $text);
        @flock($fp, LOCK_UN);
        @fclose($fp);
        if ($this->fileMode !== null) {
            @chmod($this->logFile, $this->fileMode);
        }
    }
}
