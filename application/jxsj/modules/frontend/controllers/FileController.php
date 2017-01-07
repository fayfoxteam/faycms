<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fay\models\tables\FilesTable;

class FileController extends FrontController{
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