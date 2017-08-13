<?php
namespace siwi\modules\user\controllers;

use fay\exceptions\ValidationException;
use siwi\library\UserController;
use cms\models\tables\FilesTable;
use cms\services\file\FileService;
use cms\services\CategoryService;

class FileController extends UserController{
    public function __construct(){
        parent::__construct();
    }
    
    public function upload(){
        set_time_limit(0);
        
        $target = $this->input->get('t');
        $cat_id = 0;
        //传入非指定target的话，清空这个值
        if($target == 'posts'){
            $cat_id = CategoryService::service()->getIdByAlias($target);
        }else if($target == 'avatar'){
            $cat_id = CategoryService::service()->getIdByAlias($target);
        }else{
            throw new ValidationException('参数异常', 500);
        }
        
        $private = !!$this->input->get('p');
        $result = FileService::service()->upload($target, $cat_id, $private);
        if($this->input->get('CKEditorFuncNum')){
            echo "<script>window.parent.CKEDITOR.tools.callFunction({$this->input->get('CKEditorFuncNum')}, '{$result['url']}', '');</script>";
        }else{
            echo json_encode($result);
        }
    }
    
    public function download(){
        if($file_id = $this->input->get('id', 'intval')){
            if($file = FilesTable::model()->find($file_id)){
                FilesTable::model()->incr($file_id, 'downloads', 1);
                $data = file_get_contents($file['file_path'].$file['raw_name'].$file['file_ext']);
                if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
                    header('Content-Type: "'.$file['file_type'].'"');
                    header('Content-Disposition: attachment; filename="'.$file['raw_name'].$file['file_ext'].'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header("Content-Transfer-Encoding: binary");
                    header('Pragma: public');
                    header("Content-Length: ".strlen($data));
                }else{
                    header('Content-Type: "'.$file['file_type'].'"');
                    header('Content-Disposition: attachment; filename="'.$file['raw_name'].$file['file_ext'].'"');
                    header("Content-Transfer-Encoding: binary");
                    header('Expires: 0');
                    header('Pragma: no-cache');
                    header("Content-Length: ".strlen($data));
                }
                die($data);
            }else{
                die('文件不存在');
            }
        }else{
            die('参数不正确');
        }
    }
}