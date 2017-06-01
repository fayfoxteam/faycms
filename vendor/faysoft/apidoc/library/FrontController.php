<?php
namespace apidoc\library;

use fay\core\Controller;
use fay\helpers\RequestHelper;
use cms\models\tables\SpiderLogsTable;
use cms\services\CategoryService;
use apidoc\models\tables\ApisTable;
use fay\helpers\HtmlHelper;

class FrontController extends Controller{
    public $layout_template = 'apidoc/frontend/layouts/frontend';
    
    public $_left_menu = array();
    
    public $_top_nav = array();
    
    public function __construct(){
        parent::__construct();
        
        $this->layout->assign(array(
            'current_directory'=>'',
            'title'=>'',
            'subtitle'=>'',
            'api_id'=>0,
        ));
        
        $this->_left_menu = $this->getLeftMenu();
    }
    
    private function getLeftMenu(){
        $api_cats = CategoryService::service()->getNextLevel('_system_api', array('id', 'alias', 'title', 'description'));
        $apis = ApisTable::model()->fetchAll(array(), 'id,title,router,cat_id', 'cat_id');
        $menus = array();
        foreach($api_cats as $c){
            $menu = array(
                'id'=>0,
                'alias'=>$c['alias'],
                'title'=>$c['title'],
                'css_class'=>$c['description'],
                'link'=>'javascript:;',
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
                        'link'=>'api/' . $a['id'],
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
}