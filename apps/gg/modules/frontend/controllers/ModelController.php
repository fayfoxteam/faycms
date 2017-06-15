<?php
namespace gg\modules\frontend\controllers;

use fay\core\Db;
use fay\helpers\StringHelper;
use gg\library\FrontController;

class ModelController extends FrontController{
    public function index(){
        $tables = Db::getInstance()->fetchAll('SHOW TABLES');
        $gg_tables = array();
        foreach($tables as $table){
            $table = array_shift($table);
            if(strpos($table, 'faycms_gg_') === 0){
                $gg_tables[] = $table;
            }
        }
        
        $this->view->assign(array(
            'tables'=>$gg_tables,
        ))->render();
    }
    
    public function download(){
        $table = $this->input->get('table', 'trim');

        $sql = "SHOW FULL FIELDS FROM {$table}";
        $fields = Db::getInstance()->fetchAll($sql);
        
        $table_without_prefix = str_replace($this->config->get('db.table_prefix') . 'gg_', '', $table);
        
        $soft_delete = false;
        $has_timestamps = false;
        $fillable = array();
        foreach($fields as $field){
            if($field['Field'] == 'deleted_at'){
                $soft_delete = true;
            }
            
            if($field['Field'] == 'created_at' || $field['Field'] == 'updated_at'){
                $has_timestamps = true;
            }
            
            if(!in_array($field['Field'], array(
                'id',
                'created_at', 'updated_at', 'deleted_at',
            ))){
                $fillable[] = $field['Field'];
            }
        }
        
        $class_name = StringHelper::underscore2case($table_without_prefix).'Model';
        $content = $this->view->renderPartial(null, array(
            'class_name'=>$class_name,
            'soft_delete'=>$soft_delete,
            'table'=>$table_without_prefix,
            'has_timestamps'=>$has_timestamps,
            'fillable'=>$fillable,
        ), -1, true);

        $filename = $class_name . '.php';
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
            header('Content-Type: "application/x-httpd-php"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: ".strlen($content));
        }else{
            header('Content-Type: "application/x-httpd-php"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: ".strlen($content));
        }
        echo $content;
    }
}