<?php
namespace fay\helpers;

/**
 * 本地文件（代码文件）操作类
 */
class LocalFileHelper{
    /**
     * 获取指定路径下的文件列表，如果第二个参数为true，
     * 则会递归的列出子目录下的文件
     * @param string $dir 目录
     * @param bool $recursion
     * @return array
     */
    public static function getFileList($dir, $recursion = false){
        $filelist = array();
        $real_path = realpath($dir);
        if (is_dir($real_path)) {
            if ($dh = opendir($real_path)) {
                while (($file = readdir($dh)) !== false) {
                    if (strpos($file, '.') === 0) {
                        continue;
                    }
                    $full_path = $real_path . DIRECTORY_SEPARATOR . $file;
                    $filetype = filetype($full_path);
                    $is_dir = $filetype == 'dir';
                    $relative_path = str_ireplace(BASEPATH, '', $full_path);
                    $relative_path = str_replace('\\', '/', $relative_path);
                    $filelist[] = array(
                        'name'=>$file,
                        'path'=>$full_path,
                        'relative_path'=>$relative_path,
                        'is_dir'=>$is_dir,
                    );
                    if($is_dir == true && $recursion == true){
                        $subdir = self::getFileList($real_path . DIRECTORY_SEPARATOR . $file, true);
                        $filelist = array_merge($filelist, $subdir);
                    }
                }
                closedir($dh);
            }
        }
        return $filelist;
    }

    /**
     * 获取文件名扩展名并转换为小写
     * @param string $filename 文件名
     * @return string
     */
    public static function getFileExt($filename){
        return strtolower(strrchr($filename, '.'));
    }

    /**
     * 创建多级目录
     *  - 若目录已存在，直接返回true
     *  - 若父目录也不存在，会自动创建父目录
     * @param string $path 目录
     * @param int $mode 模式
     * @return bool
     */
    public static function createFolder($path, $mode = 0775){
        if(is_dir($path)) {
            return true;
        }
        $parentDir = dirname($path);
        if(!is_dir($parentDir)){
            static::createFolder($parentDir, $mode);
        }
        $result = mkdir($path, $mode);
        chmod($path, $mode);

        return $result;
    }

    /**
     * 创建一个文件。
     * - 若文件不存在，则创建文件
     * - 若文件存在，会覆盖
     * - 若目录也不存在，则会先创建目录
     * @param string $file
     * @param string $data
     * @param int $mode
     */
    public static function createFile($file, $data, $mode = 0775){
        $dir = dirname($file);
        if(!is_dir($dir)){
            self::createFolder($dir, $mode);
        }
        file_put_contents($file, $data);
        @chmod($file, $mode);
    }

    /**
     * 删除整个文件夹
     * 若第二个参数为true，则连同文件夹一同删除（包括自身）
     * @param string $path
     * @param bool|string $del_dir
     * @param int $level
     * @return bool
     */
    public static function deleteFiles($path, $del_dir = false, $level = 0){
        // Trim the trailing slash
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if (!$current_dir = @opendir($path)){
            return false;
        }

        while(false !== ($filename = @readdir($current_dir))){
            if ($filename != "." and $filename != ".."){
                if (is_dir($path.DIRECTORY_SEPARATOR.$filename)){
                    // Ignore empty folders
                    if (substr($filename, 0, 1) != '.'){
                        self::deleteFiles($path.DIRECTORY_SEPARATOR.$filename, $del_dir, $level + 1);
                    }
                }else{
                    unlink($path.DIRECTORY_SEPARATOR.$filename);
                }
            }
        }
        @closedir($current_dir);

        if ($del_dir == true){
            return @rmdir($path);
        }
        return true;
    }

    /**
     * 获取文件的一行或前后N行
     * @param string $file 文件路径
     * @param int $line 行号
     * @param int $adjacent 前后行数
     * @return string
     */
    public static function getFileLine($file, $line, $adjacent = 0){
        if(!file_exists($file)){
            return '';
        }
        $file = file($file);
        if($adjacent){
            $offset = $line - $adjacent - 1;//开始截取位置
            $offset < 0 && $offset = 0;
            $end = $line + $adjacent;//结束截取位置
            $file_line_count = count($file);//文件行数
            $end > $file_line_count && $end = $file_line_count;

            $fragment = array_slice($file, $offset, $end - $offset);
            return implode('', $fragment);
        }else{
            return $file[$line - 1];
        }
    }
}