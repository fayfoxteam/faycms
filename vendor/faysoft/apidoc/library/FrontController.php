<?php
namespace apidoc\library;

use apidoc\helpers\LinkHelper;
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
        return '1';
    }
}