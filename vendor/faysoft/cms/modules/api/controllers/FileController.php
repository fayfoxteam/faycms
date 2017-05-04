<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use cms\services\CaptchaService;
use cms\services\file\FileService;
use cms\models\tables\FilesTable;
use fay\helpers\ImageHelper;
use fay\core\Validator;
use fay\core\HttpException;
use fay\helpers\StringHelper;
use PHPQRCode\QRcode;

/**
 * 文件
 */
class FileController extends ApiController{
    /**
     * 输出一张图片
     * @parameter int $f 文件ID
     * @parameter int $t 输出方式
     *  - 1: 原图
     *  - 2: 缩略图
     *  - 3: 裁剪图
     *  - 4: 缩放图
     * @parameter int $x 当$t=3时，裁剪起始x坐标点
     * @parameter int $y 当$t=3时，裁剪起始y坐标点
     * @parameter int $w 当$t=3时，裁剪宽度
     * @parameter int $h 当$t=3时，裁剪高度
     * @parameter int $dw 当$t=3或$t=4时候，图片输出宽度（原图尺寸不足时会被拉伸）
     * @parameter int $dh 当$t=3或$t=4时候，图片输出高度（原图尺寸不足时会被拉伸）
     */
    public function pic(){
        //验证必须get方式发起请求
        $this->checkMethod('GET');
        
        $validator = new Validator();
        $check = $validator->check(array(
            array(array('f'), 'required'),
            array(array('t'), 'range', array('range'=>array(
                FileService::PIC_ORIGINAL,
                FileService::PIC_THUMBNAIL,
                FileService::PIC_CROP,
                FileService::PIC_RESIZE,
                FileService::PIC_CUT
            ))),
            array(array('x','y', 'dw', 'dh', 'w', 'h'), 'int'),
        ));
        
        if($check !== true){
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            header('Content-type: image/png');
            readfile($spare);
            die;
        }
        
        //显示模式
        $t = $this->input->get('t', 'intval', FileService::PIC_ORIGINAL);
        
        //文件名或文件id号
        $f = $this->input->get('f');
        if(StringHelper::isInt($f)){
            if($f == 0){
                //这里不直接返回图片不存在的提示，因为可能需要缩放，让后面的逻辑去处理
                $file = false;
            }else{
                $file = FilesTable::model()->find($f);
            }
        }else{
            $file = FilesTable::model()->fetchRow(array('raw = ?'=>$f));
        }

        if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $file['raw_name'] == $_SERVER['HTTP_IF_NONE_MATCH']){
            header('HTTP/1.1 304 Not Modified');
            die;
        }
        
        //设置缓存
        header("Expires: Sat, 26 Jul 2020 05:00:00 GMT");
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $file['upload_time']).' GMT');
        header("Cache-control: max-age=3600");
        header("Pragma: cache");
        header('Etag:'.$file['raw_name']);
        
        switch ($t) {
            case FileService::PIC_ORIGINAL:
                //直接输出图片
                $this->_pic($file);
                break;
            case FileService::PIC_THUMBNAIL:
                //输出图片的缩略图
                $this->_thumbnail($file);
                break;
            case FileService::PIC_CROP:
                /**
                 * 根据起始坐标，宽度及宽高比裁剪后输出图片
                 * @parameter $_GET['x'] 起始点x坐标
                 * @parameter $_GET['y'] 起始点y坐标
                 * @parameter $_GET['dw'] 输出图像宽度
                 * @parameter $_GET['dh'] 输出图像高度
                 * @parameter $_GET['w'] 截图图片的宽度
                 * @parameter $_GET['h'] 截图图片的高度
                 */
                $this->_crop($file);
                break;
            case FileService::PIC_RESIZE:
                /**
                 * 根据给定的宽高对图片进行裁剪后输出图片
                 * @parameter $_GET['dw'] 输出图像宽度
                 * @parameter $_GET['dh'] 输出图像高度
                 * 若仅指定高度或者宽度，则会按比例缩放
                 * 若均不指定，则默认输出原图
                 */
                $this->_resize($file);
                break;
            case FileService::PIC_CUT:
                /**
                 * 根据给定的宽高对图片进行裁剪后输出图片
                 * @parameter $_GET['dw'] 输出图像宽度
                 * @parameter $_GET['dh'] 输出图像高度
                 * 若未指定高度或者宽度，则会取文件实际宽高（这与RESIZE不同）
                 * 若均不指定，则默认输出原图
                 */
                $this->_cut($file);
                break;
        
            default:
                ;
                break;
        }
    }
    
    private function _pic($file){
        if($file !== false){
            //出于性能考虑，这里不会去判断物理文件是否存在（除非服务器挂了，否则肯定存在）
            header('Content-type: '.$file['file_type']);
            readfile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
        }else{
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            header('Content-type: image/png');
            readfile($spare);
        }
    }
    
    private function _thumbnail($file){
        if($file !== false){
            header('Content-type: '.$file['file_type']);
            readfile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'-100x100.jpg');
        }else{
            $spare = $this->config->get($this->input->get('s', 'trim', 'thumbnail'), 'noimage');
            $spare || $spare = $this->config->get('thumbnail', 'noimage');
            header('Content-type: image/png');
            readfile($spare);
        }
    }
    
    private function _crop($file){
        //x坐标位置
        $x = $this->input->get('x', 'intval', 0);
        //y坐标
        $y = $this->input->get('y', 'intval', 0);
        //输出宽度
        $dw = $this->input->get('dw', 'intval', 0);
        //输出高度
        $dh = $this->input->get('dh', 'intval', 0);
        //选中部分的宽度
        $w = $this->input->get('w', 'intval');
        //选中部分的高度
        $h = $this->input->get('h', 'intval');
        
        if(!$w || !$h){
            throw new HttpException('不完整的请求', 500);
        }
        
        if($file !== false){
            $img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
            
            if($dw == 0){
                $dw = $w;
            }
            if($dh == 0){
                $dh = $h;
            }
            $img = ImageHelper::crop($img, $x, $y, $w, $h);
            if($dw != $w || $dh != $h){
                $img = ImageHelper::resize($img, $dw, $dh);
            }

            ImageHelper::output($img, $file['file_type']);
        }else{
            //图片不存在，显示一张默认图片吧
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            $img = ImageHelper::getImage($spare);
            header('Content-type: image/jpeg');
            $img = ImageHelper::resize($img, $dw ? $dw : 325, $dh ? $dh : 235);
            imagejpeg($img);
        }
    }
    
    private function _resize($file){
        //输出宽度
        $dw = $this->input->get('dw', 'intval');
        //输出高度
        $dh = $this->input->get('dh', 'intval');
        
        if($file !== false){
            if($dw && !$dh){
                $dh = $dw * ($file['image_height'] / $file['image_width']);
            }else if($dh && !$dw){
                $dw = $dh * ($file['image_width'] / $file['image_height']);
            }else if(!$dw && !$dh){
                $dw = $file['image_width'];
                $dh = $file['image_height'];
            }
            
            //获取图片资源
            $img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
            
            //缩放
            $img = ImageHelper::resize($img, $dw, $dh);
            
            //输出
            ImageHelper::output($img, $file['file_type']);
        }else{
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            $img = ImageHelper::getImage($spare);
            header('Content-type: image/jpeg');
            $img = ImageHelper::resize($img, $dw ? $dw : 325, $dh ? $dh : 235);
            imagejpeg($img);
        }
    }
    
    private function _cut($file){
        //输出宽度
        $dw = $this->input->get('dw', 'intval');
        //输出高度
        $dh = $this->input->get('dh', 'intval');
        
        if($file !== false){
            $dw || $dw = $file['image_width'];
            $dh || $dh = $file['image_height'];

            //获取图片资源
            $img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);

            //缩放
            $img = ImageHelper::resize($img, $dw, $dh);

            //输出
            ImageHelper::output($img, $file['file_type']);
        }else{
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            $img = ImageHelper::getImage($spare);
            header('Content-type: image/jpeg');
            $img = ImageHelper::resize($img, $dw ? $dw : 325, $dh ? $dh : 235);
            imagejpeg($img);
        }
    }
    
    public function captcha(){
        CaptchaService::output(
            $this->input->get('w', 'intval', 150),
            $this->input->get('h', 'intval', 40),
            $this->input->get('length', 'intval', 4)
        );
    }
    
    /**
     * 显示一张二维码
     * @parameter string $data 二维码内容，经base64编码后的字符串
     */
    public function qrcode(){
        QRcode::png(base64_decode($this->input->get('data')), false, 'L', 4, 2);
    }
    
    /**
     * 下载一个文件
     * @parameter int $id 文件ID
     */
    public function download(){
        if($file_id = $this->input->get('id', 'intval')){
            if($file = FilesTable::model()->find($file_id)){
                if(substr((defined('NO_REWRITE') ? './public/' : '').$file['file_path'], 0, 4) == './..'){
                    //私有文件不允许在此方法下载
                    throw new HttpException('文件不存在');
                }
                
                //可选下载文件名格式
                if($this->input->get('name') == 'date'){
                    $filename = date('YmdHis', $file['upload_time']).$file['file_ext'];
                }else if($this->input->get('name') == 'timestamp'){
                    $filename = $file['upload_time'].$file['file_ext'];
                }else if($this->input->get('name') == 'client_name'){
                    $filename = $file['client_name'];
                }else{
                    $filename = $file['raw_name'].$file['file_ext'];
                }
                
                FilesTable::model()->incr($file_id, 'downloads', 1);
                $data = file_get_contents((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
                if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
                    header('Content-Type: "'.$file['file_type'].'"');
                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header("Content-Transfer-Encoding: binary");
                    header('Pragma: public');
                    header("Content-Length: ".strlen($data));
                }else{
                    header('Content-Type: "'.$file['file_type'].'"');
                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                    header("Content-Transfer-Encoding: binary");
                    header('Expires: 0');
                    header('Pragma: no-cache');
                    header("Content-Length: ".strlen($data));
                }
                die($data);
            }else{
                throw new HttpException('文件不存在');
            }
        }else{
            throw new HttpException('参数不正确', 500);
        }
    }
}