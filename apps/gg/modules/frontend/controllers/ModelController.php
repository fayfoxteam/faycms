<?php
namespace gg\modules\frontend\controllers;

use fay\core\Db;
use fay\helpers\HtmlHelper;
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
        
        return $this->view->assign(array(
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
        $guarded = array();
        $fillable = array();
        foreach($fields as $field){
            if($field['Field'] == 'deleted_at'){
                $soft_delete = true;
            }
            
            if($field['Field'] == 'created_at' || $field['Field'] == 'updated_at'){
                $has_timestamps = true;
            }
            
            if(in_array($field['Field'], array(
                'id',
                'created_at', 'updated_at', 'deleted_at',
            ))){
                $guarded[] = $field['Field'];
            }else{
                $fillable[] = $field['Field'];
            }
        }

        //获取表注释
        $create_table = Db::getInstance()->fetchRow("SHOW CREATE TABLE {$table}");
        $create_table = explode('\n', $create_table['Create Table']);
        $create_table_last_line = array_pop($create_table);
        preg_match('/COMMENT=\'(.*)\'/', $create_table_last_line, $table_comment);
        $table_comment = isset($table_comment[1]) ? $table_comment[1] : '';
        
        $class_name = StringHelper::underscore2case($table_without_prefix);
        $content = $this->view->renderPartial(null, array(
            'class_name'=>$class_name,
            'soft_delete'=>$soft_delete,
            'table'=>$table_without_prefix,
            'has_timestamps'=>$has_timestamps,
            'guarded'=>$guarded,
            'fillable'=>$fillable,
            'fields'=>$fields,
            'table_comment'=>$table_comment,
        ));

//        $filename = $class_name . '.php';
//        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
//            header('Content-Type: "application/x-httpd-php"');
//            header('Content-Disposition: attachment; filename="'.$filename.'"');
//            header('Expires: 0');
//            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//            header("Content-Transfer-Encoding: binary");
//            header('Pragma: public');
//            header("Content-Length: ".strlen($content));
//        }else{
//            header('Content-Type: "application/x-httpd-php"');
//            header('Content-Disposition: attachment; filename="'.$filename.'"');
//            header("Content-Transfer-Encoding: binary");
//            header('Expires: 0');
//            header('Pragma: no-cache');
//            header("Content-Length: ".strlen($content));
//        }
        
        echo '<pre>', HtmlHelper::encode($content);
    }
}