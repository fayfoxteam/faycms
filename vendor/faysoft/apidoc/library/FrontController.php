<?php
namespace apidoc\library;

use apidoc\helpers\LinkHelper;
use apidoc\helpers\TrackHelper;
use apidoc\models\tables\ApidocApisTable;
use apidoc\models\tables\ApidocAppsTable;
use apidoc\models\tables\ApidocOutputsTable;
use apidoc\services\ApiCategoryService;
use fay\core\Controller;
use fay\helpers\HtmlHelper;
use fay\helpers\NumberHelper;

class FrontController extends Controller{
    public $layout_template = 'apidoc/frontend/layouts/frontend';
    
    public $_left_menu = array();
    
    public $_top_nav = array();
    
    public $app_id;
    
    public function __construct(){
        parent::__construct();
        
        $this->app_id = $this->getAppID();
        
        $this->layout->assign(array(
            'current_app'=>ApidocAppsTable::model()->find($this->app_id, 'id,name'),
            'apps'=>ApidocAppsTable::model()->fetchAll(array(
                'enabled = ?'=>1,
                'need_login = ?'=>$this->current_user ? false : 0,//是否登录可见
            ), 'id,name', 'sort'),
            'current_directory'=>'',
            'title'=>'',
            'subtitle'=>'',
            'api_id'=>0,
        ));
        
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
        //若在连接中明确指定APP ID，直接返回
        if($this->input->get('app_id')){
            $app = ApidocAppsTable::model()->find($this->input->get('app_id', 'intval'), 'id');
            if($app){
                return $app['id'];
            }
        }
        
        //若指定了API，则返回API对应的APP
        if($this->input->get('api_id')){
            $api = ApidocApisTable::model()->find($this->input->get('api_id', 'intval'), 'app_id');
            if($api){
                return $api['app_id'];
            }
        }

        //若在trackid中包含api_id，则根据此api_id获取app_id
        $api_id = TrackHelper::getApiId();
        if($api_id){
            $api = ApidocApisTable::model()->find($api_id, 'app_id');
            if($api){
                return $api['app_id'];
            }
        }
        
        if(\F::cookie()->get('apidoc_current_app')){
            $app = ApidocAppsTable::model()->find(\F::cookie()->get('apidoc_current_app', 'intval'), 'id');
            if($app){
                return $app['id'];
            }
        }

        //若指定了Model，获取第一个与此model关联的api（若model不是与api直接关联，则无视）
        if($this->input->get('model_id')){
            $output = ApidocOutputsTable::model()->fetchRow(array(
                'model_id = ?'=>$this->input->get('model_id', 'intval')
            ), 'api_id', 'id');
            
            if($output){
                $api = ApidocApisTable::model()->find($output['api_id'], 'app_id');
                if($api){
                    return $api['app_id'];
                }
            }
        }
        
        //默认返回无需登录的第一个APP
        $app = ApidocAppsTable::model()->fetchRow('need_login = 0', 'id', 'sort, id DESC');
        return $app['id'];
    }
}