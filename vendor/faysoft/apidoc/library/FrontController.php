<?php
namespace apidoc\library;

use apidoc\helpers\LinkHelper;
use apidoc\helpers\TrackHelper;
use apidoc\models\tables\ApidocApisTable;
use apidoc\services\ApiCategoryService;
use fay\core\Controller;
use fay\helpers\HtmlHelper;

class FrontController extends Controller{
    public $layout_template = 'apidoc/frontend/layouts/frontend';
    
    public $_left_menu = array();
    
    public $_top_nav = array();
    
    public $app_id;
    
    public function __construct(){
        parent::__construct();
        
        $this->layout->assign(array(
            'current_directory'=>'',
            'title'=>'',
            'subtitle'=>'',
            'api_id'=>0,
        ));
        
        $this->app_id = $this->getAppID();
        
        $this->_left_menu = $this->getLeftMenu();
    }
    
    private function getLeftMenu(){
        $api_cats = ApiCategoryService::service()->getTree($this->app_id, 0, array('id', 'alias', 'title', 'description'));
        $apis = ApidocApisTable::model()->fetchAll(array(
            'app_id = ' . $this->app_id,
        ), 'id,title,router,cat_id', 'cat_id');
        $menus = array();
        foreach($api_cats as $c){
            $menu = array(
                'id'=>0,
                'alias'=>$c['alias'],
                'title'=>$c['title'],
                'css_class'=>$c['description'],
                'link'=>'javascript:',
                'target'=>'',
                'children'=>array(),
            );
            
            $start = false;
            foreach($apis as $k => $a){
                if($a['cat_id'] == $c['id']){
                    $start = true;
                    $menu['children'][] = array(
                        'id'=>$a['id'],
                        'alias'=>'',
                        'title'=>"<span class='router' title='{$a['router']}'>{$a['router']}</span>" . HtmlHelper::encode($a['title']),
                        'css_class'=>'',
                        'link'=>LinkHelper::generateApiLink($a['id']),
                        'target'=>'',
                        'children'=>array(),
                    );
                    unset($apis[$k]);
                }else if($start){
                    break;
                }
            }
            
            $menus[] = $menu;
        }
        
        return $menus;
    }
    
    private function getAppID(){
        if($this->input->get('api_id')){
            //若指定了API，则返回API对应的APP
            $api = ApidocApisTable::model()->find($this->input->get('api_id', 'intval'), 'app_id');
            if($api){
                return $api['app_id'];
            }
        }
        
        $api_id = TrackHelper::getApiId();
        if($api_id){
            //若在trackid中包含api_id，则根据此api_id获取app_id
            $api = ApidocApisTable::model()->find($api_id, 'app_id');
            if($api){
                return $api['app_id'];
            }
        }
        
        if($this->input->get('model_id')){
            //@todo 若指定了Model，返回Model第一个关联的APP
            
        }
        
        //默认返回无需登录的第一个APP
        $api = ApidocApisTable::model()->fetchRow('need_login = 0', 'id', 'sort, id DESC');
        return $api['app_id'];
    }
}