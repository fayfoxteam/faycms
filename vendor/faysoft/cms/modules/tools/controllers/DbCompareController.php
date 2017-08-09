<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\Db;
use fay\core\Response;
use fay\core\Uri;
use fay\helpers\SqlHelper;

class DbCompareController extends ToolsController{
    /**
     * @var Db
     */
    public $db;
    
    /**
     * 右侧数据库配置项
     */
    public $db_config;
    
    /**
     * 左侧数据库实例
     */
    public $left_db;
    
    /**
     * 右侧数据库实例
     */
    public $right_db;
    
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'database';

        //登陆检查，仅超级管理员可访问本模块
        $this->isLogin();
        
        $this->db = Db::getInstance();
        
        if(Uri::getInstance()->router != 'cms/tools/db-compare/index'){
            if(!$this->db_config = \F::session()->get('dbcompare')){
                Response::notify('error', 'Please set the database info!', array('cms/tools/db-compare/index'));
            }
            
            $this->view->db_config = $this->db_config;
            
            $this->left_db = new \cms\library\Db(array(
                'host'=>$this->db_config['left']['host'],
                'user'=>$this->db_config['left']['user'],
                'password'=>$this->db_config['left']['password'],
                'port'=>$this->db_config['left']['port'],
                'dbname'=>$this->db_config['left']['dbname'],
                'table_prefix'=>$this->db_config['left']['prefix'],
            ));
            
            $this->right_db = new \cms\library\Db(array(
                'host'=>$this->db_config['right']['host'],
                'user'=>$this->db_config['right']['user'],
                'password'=>$this->db_config['right']['password'],
                'port'=>$this->db_config['right']['port'],
                'dbname'=>$this->db_config['right']['dbname'],
                'table_prefix'=>$this->db_config['right']['prefix'],
            ));
        }
        
        set_time_limit(0);//sql较多，可能会比较慢
    }
    
    /**
     * 填写其他数据库的参数
     */
    public function index(){
        $this->layout->subtitle = 'Other DB';
        
        if($this->input->post()){
            $left_config = $this->input->post('left');
            if($left_config['from'] == 'local'){
                $left_config = array(
                    'host'=>$this->config->get('db.host'),
                    'user'=>$this->config->get('db.user'),
                    'password'=>$this->config->get('db.password'),
                    'port'=>$this->config->get('db.port'),
                    'dbname'=>$this->config->get('db.dbname'),
                    'prefix'=>$this->config->get('db.table_prefix'),
                );
            }else{
                $left_config = array(
                    'host'=>$left_config['host'],
                    'user'=>$left_config['user'],
                    'password'=>$left_config['password'],
                    'port'=>$left_config['port'],
                    'dbname'=>$left_config['dbname'],
                    'prefix'=>$left_config['prefix'] ? $left_config['prefix'] : '',
                );
            }
            
            $right_config = $this->input->post('right');
            if($right_config['from'] == 'local'){
                $right_config = array(
                    'host'=>$this->config->get('db.host'),
                    'user'=>$this->config->get('db.user'),
                    'password'=>$this->config->get('db.password'),
                    'port'=>$this->config->get('db.port'),
                    'dbname'=>$this->config->get('db.dbname'),
                    'prefix'=>$this->config->get('db.table_prefix'),
                );
            }else{
                $right_config = array(
                    'host'=>$right_config['host'],
                    'user'=>$right_config['user'],
                    'password'=>$right_config['password'],
                    'port'=>$right_config['port'],
                    'dbname'=>$right_config['dbname'],
                    'prefix'=>$right_config['prefix'] ? $right_config['prefix'] : '',
                );
            }
            
            //尝试连接，连不上的话会直接报错的
            $this->left_db = new \cms\library\Db(array(
                'host'=>$left_config['host'],
                'user'=>$left_config['user'],
                'password'=>$left_config['password'],
                'port'=>$left_config['port'],
                'dbname'=>$left_config['dbname'],
                'prefix'=>$left_config['prefix'],
            ));
            
            $this->right_db = new \cms\library\Db(array(
                'host'=>$right_config['host'],
                'user'=>$right_config['user'],
                'password'=>$right_config['password'],
                'port'=>$right_config['port'],
                'dbname'=>$right_config['dbname'],
                'prefix'=>$right_config['prefix'],
            ));
            
            \F::session()->set('dbcompare', array(
                'left'=>$left_config,
                'right'=>$right_config,
            ));
        }
        
        if(\F::session()->get('dbcompare')){
            $this->response->redirect('cms/tools/db-compare/tables');
        }
        
        return $this->view->render();
    }
    
    /**
     * 清除数据库参数信息
     */
    public function clear(){
        \F::session()->remove('dbcompare');
        $this->response->redirect('cms/tools/db-compare/index');
    }
    
    /**
     * 数据库表列表
     */
    public function tables(){
        $this->layout->subtitle = 'Table List Compare';
        
        $this->layout->sublink = array(
            'uri'=>array('cms/tools/db-compare/clear'),
            'text'=>'Clear DB Config',
        );
        
        $right_tables = $this->right_db->fetchAll('SHOW TABLES');
        $this->view->right_tables = SqlHelper::removePrefix($this->db_config['right']['prefix'], $right_tables);
        
        $left_tables = $this->left_db->fetchAll('SHOW TABLES');
        $this->view->left_tables = SqlHelper::removePrefix($this->db_config['left']['prefix'], $left_tables);
        
        //两边都有的表
        $intersect_tables = array_intersect($this->view->left_tables, $this->view->right_tables);
        //两边都有，但表结构不同的表
        $diff_tables = array();
        foreach($intersect_tables as $table){
            $left_ddl = $this->left_db->fetchRow("SHOW CREATE TABLE {$this->db_config['left']['prefix']}{$table}");
            $right_ddl = $this->right_db->fetchRow("SHOW CREATE TABLE {$this->db_config['right']['prefix']}{$table}");
            
            //无视自增字段
            $left_ddl = preg_replace('/AUTO_INCREMENT=\d+ /', '', $left_ddl['Create Table']);
            $right_ddl = preg_replace('/AUTO_INCREMENT=\d+ /', '', $right_ddl['Create Table']);
            
            //删除表前缀
            $left_ddl = preg_replace("/^CREATE TABLE `{$this->db_config['left']['prefix']}(.*)`/", 'CREATE TABLE `$1`', $left_ddl);
            $right_ddl = preg_replace("/^CREATE TABLE `{$this->db_config['right']['prefix']}(.*)`/", 'CREATE TABLE `$1`', $right_ddl);
            
            
            //无视注释
            $left_ddl = preg_replace("/ COMMENT '.+'/", '', $left_ddl);
            $right_ddl = preg_replace("/ COMMENT '.+'/", '', $right_ddl);
            
            //由于默认是BTREE索引，此处将 USING BTREE也删除
            $left_ddl = preg_replace('/ USING BTREE/', '', $left_ddl);
            $right_ddl = preg_replace('/ USING BTREE/', '', $right_ddl);
            
            if($left_ddl != $right_ddl){
                $diff_tables[] = $table;
            }
        }
        $this->view->diff_tables = $diff_tables;
        
        //右侧数据库和左侧数据库表的“合集”
        $this->view->all_tables = $this->merge($this->view->right_tables, $this->view->left_tables);
        
        return $this->view->render();
    }
    
    /**
     * 单表比较
     */
    public function table(){
        $this->layout->sublink = array(
            'uri'=>array('cms/tools/db-compare/tables'),
            'text'=>'Table List',
        );
        
        $name = $this->input->get('name');
        $this->layout->subtitle = 'Table Compare - '.$name;
        
        $left_fields = $this->left_db->fetchAll("SHOW FULL FIELDS FROM {$this->db_config['left']['prefix']}{$name}");
        $left_fields_simple = array();
        foreach($left_fields as $f){
            $left_fields_simple[] = $f['Field'];
        }
        $this->view->left_fields = $left_fields;
        $this->view->left_fields_simple = $left_fields_simple;
        $left_ddl = $this->left_db->fetchRow("SHOW CREATE TABLE {$this->db_config['left']['prefix']}{$name}");
        $this->view->left_ddl = preg_replace('/ AUTO_INCREMENT=\d+/', '', $left_ddl['Create Table']);
        
        $right_fields = $this->right_db->fetchAll("SHOW FULL FIELDS FROM {$this->db_config['right']['prefix']}{$name}");
        $right_fields_simple = array();
        foreach($right_fields as $f){
            $right_fields_simple[] = $f['Field'];
        }
        $this->view->right_fields = $right_fields;
        $this->view->right_fields_simple = $right_fields_simple;
        $right_ddl = $this->right_db->fetchRow("SHOW CREATE TABLE {$this->db_config['right']['prefix']}{$name}");
        $this->view->right_ddl = preg_replace('/ AUTO_INCREMENT=\d+/', '', $right_ddl['Create Table']);
        
        $this->view->all_fields = $this->merge($left_fields_simple, $right_fields_simple);
        
        return $this->view->render();
    }
    
    /**
     * 快速查看表结构
     */
    public function ddl(){
        $db = $this->input->get('db');
        $name = $this->input->get('name');
        
        if($db != 'left'){
            $db = 'right';
            $db_obj = $this->right_db;
        }else{
            $db_obj = $this->left_db;
        }
        
        $ddl = $db_obj->fetchRow("SHOW CREATE TABLE {$this->db_config[$db]['prefix']}{$name}");
        
        return Response::json(array(
            'fields'=>$db_obj->fetchAll("SHOW FULL FIELDS FROM {$this->db_config[$db]['prefix']}{$name}"),
            'ddl'=>$ddl['Create Table'],
        ));
    }
    
    /**
     * 返回两侧数据库表字段（公共字段，自由字段）
     */
    public function getFields(){
        $name = $this->input->get('name');
        
        $left_fields = $this->left_db->fetchAll("SHOW FULL FIELDS FROM {$this->db_config['left']['prefix']}{$name}");
        $left_fields_simple = array();
        foreach($left_fields as $f){
            $left_fields_simple[] = $f['Field'];
        }
        $this->view->left_fields = $left_fields;
        $this->view->left_fields_simple = $left_fields_simple;
        
        $right_fields = $this->right_db->fetchAll("SHOW FULL FIELDS FROM {$this->db_config['right']['prefix']}{$name}");
        $right_fields_simple = array();
        foreach($right_fields as $f){
            $right_fields_simple[] = $f['Field'];
        }
        
        return Response::json(array(
            'common'=>array_values(array_intersect($left_fields_simple, $right_fields_simple)),
            'left'=>array_values(array_diff($left_fields_simple, $right_fields_simple)),
            'right'=>array_values(array_diff($right_fields_simple, $left_fields_simple)),
        ));
    }
    
    /**
     * 数据传输
     */
    public function transfer(){
        if($this->input->get('from') == 'left'){
            $from_obj = $this->left_db;
            $to_obj = $this->right_db;
            $from_config = $this->db_config['left'];
            $to_config = $this->db_config['right'];
        }else{
            $from_obj = $this->right_db;
            $to_obj = $this->left_db;
            $from_config = $this->db_config['right'];
            $to_config = $this->db_config['left'];
        }
        
        $fields = $this->input->get('fields');
        $fields = '`'.implode('`,`', $fields).'`';
        
        $name = $this->input->get('name');
        
        if($this->input->get('truncate')){
            $to_obj->fetchAll("TRUNCATE {$to_config['prefix']}{$name}");
        }
        
        $data = $from_obj->fetchAll("SELECT {$fields} FROM {$from_config['prefix']}{$name}");
        foreach($data as $d){
            $to_obj->insert($name, $d);
        }
        
        Response::notify('success', '数据导入成功');
    }
    
    /**
     * 检查是否完全一致
     */
    public function check(){
        $names = $this->input->get('names');
        
        if(!is_array($names)){
            $names = explode(',', $names);
        }
        
        $result = array();
        
        foreach($names as $name){
            $left_ddl = $this->left_db->fetchRow("SHOW CREATE TABLE {$this->db_config['left']['prefix']}{$name}");
            $right_ddl = $this->right_db->fetchRow("SHOW CREATE TABLE {$this->db_config['right']['prefix']}{$name}");
            
            $left_ddl = preg_replace('/AUTO_INCREMENT=\d+ /', '', $left_ddl['Create Table']);
            $right_ddl = preg_replace('/AUTO_INCREMENT=\d+ /', '', $right_ddl['Create Table']);
            
            $result[] = array(
                'name'=>$name,
                'result'=>$left_ddl == $right_ddl ? 1 : 0,
            );
        }
        
        Response::notify('success', array(
            'result'=>$result,
        ));
    }
    
    /**
     * 保持数组顺序进行合并（可能存在重复项）
     * 例如：
     * $arr1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G');
     * $arr2 = array('A', 'D', 'F');
     * 合并为：
     * array('A', 'B', 'C', 'D', 'E', 'F', 'G')
     */
    private function merge($arr1, $arr2){
        $len1 = count($arr1);
        $len2 = count($arr2);
        $i1 = 0;
        $i2 = 0;
        $return = array();
        
        while($i1 < $len1 || $i2 < $len2){
            if($i1 < $len1 && $i2 < $len2){
                $key = array_search($arr1[$i1], $arr2);
                if($key == $i2){
                    //在数组2的同位置存在
                    $i2++;
                }else if($key === false || $key < $i2){
                    //在数组2中不存在或在前面的位置存在
                }else if($key > $i2){
                    //在数组2的后面位置存在
                    //将这段内容插入return数组
                    $return = array_merge($return, array_slice($arr2, $i2, $key - $i2));
                    $i2 = $key + 1;
                }
                $return[] = $arr1[$i1];
                $i1++;
            }else if($i1 < $len1 && $i2 >= $len2){
                //数组1还有剩余，数组2到头了
                $return = array_merge($return, array_slice($arr1, $i1));
                $i1 = $len1;
            }else if($i1 >= $len1 && $i2 < $len2){
                //数组1到头了，数组2还有剩余
                $return = array_merge($return, array_slice($arr2, $i2));
                $i2 = $len2;
            }
        }
        return $return;
    }
}