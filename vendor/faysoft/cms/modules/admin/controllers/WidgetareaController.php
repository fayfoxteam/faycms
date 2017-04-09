<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\WidgetsTable;
use fay\core\Response;

class WidgetareaController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'site';
    }
    
    public function index(){
        $this->layout->subtitle = '小工具域';
        
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/widget/index'),
            'text'=>'创建小工具',
        );
        
        //页面设置
        $this->settingForm('admin_widgetarea_index', '_setting_index');
        
        //小工具域
        $this->view->widgetareas = $this->config->getFile('widgetareas');
        
        //小工具实例
        $this->view->widgets = WidgetsTable::model()->fetchAll(array(), '*', 'widgetarea, sort, id DESC');
        
        $this->view->render();
    }
    
    /**
     * 记录widget在widgetarea中的位置
     */
    public function setWidgets(){
        $all_widgetareas = $this->config->getFile('widgetareas');
        
        foreach($all_widgetareas as $wa){
            $i = 0;
            $widgets = $this->input->post($wa['alias'], 'intval', array());
            
            WidgetsTable::model()->update(array(
                'widgetarea'=>'',
                'sort'=>255,
            ), array(
                "widgetarea = '{$wa['alias']}'",
                'id NOT IN (?)'=>$widgets ? $widgets : false,
            ));
            foreach($widgets as $w){
                $i++;
                WidgetsTable::model()->update(array(
                    'widgetarea'=>$wa['alias'],
                    'sort'=>$i,    
                ), $w);
            }
        }
        Response::notify('success', '修改成功');
    }
}