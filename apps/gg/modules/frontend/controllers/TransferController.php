<?php
namespace gg\modules\frontend\controllers;

use cms\library\Db;
use fay\helpers\IPHelper;
use fay\helpers\StringHelper;
use gg\library\FrontController;

class TransferController extends FrontController{
    /**
     * @var Db
     */
    private $remote_db;

    /**
     * @var Db
     */
    private $to_db;
    
    public function __construct(){
        parent::__construct();
        
//        $this->remote_db = new Db(array(
//            'host'=>'192.168.1.252',
//            'user'=>'fayfox',
//            'password'=>'24631633',
//            'dbname'=>'guangongNew',
//            'charset'=>'utf8',
//            'table_prefix'=>'gg_',
//            'debug'=>false,
//        ));
        $this->remote_db = new Db(array(
            'host'=>'localhost',
            'user'=>'root',
            'password'=>'',
            'dbname'=>'gw_co_ltd_online',
            'charset'=>'utf8',
            'table_prefix'=>'gg_',
            'debug'=>false,
        ));

        $this->to_db = new Db(array(
            'host'=>'localhost',
            'user'=>'root',
            'password'=>'',
            'dbname'=>'gw_co_ltd',
            'charset'=>'utf8mb4',
            'table_prefix'=>'gg_',
            'debug'=>false,
        ));
    }
    
    public function index(){
        $tables = $this->to_db->fetchAll('SHOW TABLES');
        $gg_tables = array();
        foreach($tables as $table){
            $table = array_shift($table);
            if(strpos($table, 'gg_') === 0){
                $table_name = substr($table, 3);
                if(!file_exists(APPLICATION_PATH . 'models\tables\Gg'.StringHelper::underscore2case($table_name).'Table.php')){
                    continue;
                }
                $fields = \F::table('gg\models\tables\Gg'.StringHelper::underscore2case($table_name).'Table')->getFields();
                
                $gg_tables[] = array(
                    'table'=>$table_name,
                    'pri'=>in_array('id', $fields) ? 'id' : array_shift($fields),
                );
            }
        }

        $this->view->assign(array(
            'tables'=>$gg_tables,
        ))->render();
    }
    
    public function doAction(){
        $remote_table = $table = $this->input->get('table', 'trim');
        $limit = $this->input->get('limit', 'intval', 100);
        $pri = $this->input->get('pri', 'trim', 'id');

        if(substr($remote_table, -3) == 'cat'){
            $remote_table = substr($remote_table, 0, -3) . 'cart';
        }
        if(substr($remote_table, -4) == 'cats'){
            $remote_table = substr($remote_table, 0, -4) . 'carts';
        }
        
        $rows = $this->remote_db->fetchAll("SELECT * 
            FROM {$this->remote_db->{$remote_table}} 
            WHERE {$pri} > ".$this->input->get('id', 'intval', 0)."
            ORDER BY {$pri} LIMIT {$limit}");

        if(!$rows){
            echo '没有数据';
            die;
        }
        
        $data = array();
        $fields = \F::table('gg\models\tables\Gg'.StringHelper::underscore2case($table).'Table')->getFields();
        foreach($rows as $row){
            $new_row = \F::table('gg\models\tables\Gg'.StringHelper::underscore2case($table).'Table')->fillData($row);
            if(in_array('updated_ip', $fields)){
                $new_row['updated_ip'] = IPHelper::ip2int($row['updated_ip']);
                $new_row['updated_ip'] || $new_row['updated_ip'] = 0;
            }
            if(in_array('created_ip', $fields)){
                $new_row['created_ip'] = IPHelper::ip2int($row['created_ip']);
                $new_row['created_ip'] || $new_row['created_ip'] = 0;
            }
            if(in_array('login_ip', $fields) && array_key_exists('logined_ip', $row)){
                $new_row['login_ip'] = IPHelper::ip2int($row['logined_ip']);
                $new_row['login_ip'] || $new_row['login_ip'] = 0;
            }
            if(in_array('login_ip', $fields) && array_key_exists('login_ip', $row)){
                $new_row['login_ip'] = IPHelper::ip2int($row['login_ip']);
                $new_row['login_ip'] || $new_row['login_ip'] = 0;
            }
            if(in_array('read_ip', $fields) && array_key_exists('readed_ip', $row)){
                $new_row['read_ip'] = IPHelper::ip2int($row['readed_ip']);
                $new_row['read_ip'] || $new_row['read_ip'] = 0;
            }

            if(in_array('login_at', $fields) && array_key_exists('logined_at', $row)){
                $new_row['login_at'] = $row['logined_at'];
            }

            if(in_array('deleted_at', $fields) && array_key_exists('is_delete', $row)){
                $new_row['deleted_at'] = $row['is_delete'] ? date('Y-m-d H:i:s') : null;
            }

            if(in_array('source_url', $fields) && array_key_exists('orginal_url', $row)){
                $new_row['source_url'] = $row['orginal_url'];
            }

            if(in_array('cat_id', $fields) && array_key_exists('cart_id', $row)){
                $new_row['cat_id'] = $row['cart_id'];
            }

            if(in_array('sort', $fields) && array_key_exists('sorting', $row)){
                $new_row['sort'] = $row['sorting'];
            }

            if(in_array('sort', $fields) && array_key_exists('position', $row)){
                $new_row['sort'] = $row['position'];
            }

            if(in_array('status', $fields) && array_key_exists('start', $row)){
                $new_row['status'] = $row['start'];
            }

            if(in_array('cat_bid', $fields) && array_key_exists('cart_bid', $row)){
                $new_row['cat_bid'] = $row['cart_bid'];
            }

            if(in_array('cat_sid', $fields) && array_key_exists('cart_sid', $row)){
                $new_row['cat_sid'] = $row['cart_sid'];
            }

            if(in_array('thumbnail', $fields) && array_key_exists('img', $row)){
                $new_row['thumbnail'] = $this->getFileId($row['img']);
            }

            if(in_array('thumbnail', $fields) && array_key_exists('imgage', $row)){
                $new_row['thumbnail'] = $this->getFileId($row['imgage']);
            }

            if(in_array('thumbnail', $fields) && array_key_exists('picture', $row)){
                $new_row['thumbnail'] = $this->getFileId($row['picture']);
            }

            if(in_array('thumbnail', $fields) && array_key_exists('thumbnail', $row)){
                $new_row['thumbnail'] = $this->getFileId($row['thumbnail']);
            }

            if(in_array('mobile_thumbnail', $fields) && array_key_exists('mobile_img', $row)){
                $new_row['mobile_thumbnail'] = $this->getFileId($row['mobile_img']);
            }
            
            if(in_array('device_type', $fields) && array_key_exists('device_type', $row)){
                $new_row['device_type'] = $row['device_type'] == 'mobile' ? 1 : 2;
            }

            if(in_array('avatar', $fields) && array_key_exists('header', $row)){
                $new_row['avatar'] = $this->getFileId($row['header']);
            }
            if(in_array('avatar', $fields) && array_key_exists('avatar', $row)){
                $new_row['avatar'] = $this->getFileId($row['avatar']);
            }
            
            if(in_array('direction', $fields)){
                $new_row['direction'] = $row['direction'] == 'asc' ? 1 : 2;
            }
            
            if($table == 'page'){
                if($row['type'] == 'page'){
                    $new_row['type'] = 1;
                }else if($row['type'] == 'article'){
                    $new_row['type'] = 2;
                }else if($row['type'] == 'info'){
                    $new_row['type'] = 3;
                }else if($row['type'] == 'article_detail'){
                    $new_row['type'] = 4;
                }else if($row['type'] == 'goods_detail'){
                    $new_row['type'] = 5;
                }
            }

            if($table == 'merchant_oauth'){
                $new_row['type'] = 1;
            }

            if($table == 'template_cats'){
                $new_row['name'] = $row['title'];
                $new_row['parent_id'] = $row['pid'];
            }

            if($table == 'tag'){
                $new_row['name'] = $row['tag_name'];
            }
            
            if($table == 'website'){
                //这张表被拆表了
                $info_row = \F::table('gg\models\tables\GgWebsiteInfoTable')->fillData($row);
                $info_row['website_id'] = $row['id'];
                
                foreach($info_row as &$r){
                    if(!$r){
                        $r = '';
                    }
                }
                $info_row['company_logo'] = $this->getFileId($row['company_logo']);
                
                $this->to_db->insert('website_info', $info_row);
                
                $contact_row = \F::table('gg\models\tables\GgWebsiteContactTable')->fillData($row);
                $contact_row['website_id'] = $row['id'];

                foreach($contact_row as &$c){
                    if(!$c){
                        $c = '';
                    }
                }
                $contact_row['qrcode'] = $this->getFileId($row['qrcode']);
                
                $this->to_db->insert('website_contact', $contact_row);
            }
            
            $data[] = $new_row;
        }
        //dd($data);

        if($data){
            $this->to_db->bulkInsert($table, $data);

            if(count($rows) == $limit){
                //100条一次，多了可能卡死
                echo '<script>window.location.href = "' . $this->view->url('transfer/do', array(
                        'table'=>$table,
                        'pri'=>$pri,
                        'limit'=>$limit,
                        'id'=>$row[$pri],
                    )) . '"</script>';
            }
        }
    }
    
    private function getFileId($file){
        if(!$file){
            return 0;
        }
        
        //存了完整url，替换前面的域名部分
        $file = preg_replace('/^http:\/\/\d+\.guanwang\.co\.ltd/', '', $file);
        $file = preg_replace('/^http:\/\/\d+\.gw\.co\.ltd/', '', $file);
        
        //不知道为什么有的完整链接后面还有缩略图标识
        $file = preg_replace('/\.thumb_360\.png$/', '', $file);
        
        $row = $this->remote_db->fetchRow('SELECT id FROM ' . $this->remote_db->getFullTableName('attachment') . ' WHERE filepath = ?', array($file));
        return $row ? $row['id'] : 0;
    }
}