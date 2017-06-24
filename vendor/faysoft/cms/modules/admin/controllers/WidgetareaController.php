<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\WidgetAreasTable;
use cms\models\tables\WidgetAreasWidgetsTable;
use cms\models\tables\WidgetsTable;
use cms\services\widget\WidgetAreaService;
use fay\core\Response;
use fay\helpers\ArrayHelper;

class WidgetareaController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'site';
    }
    
    public function index(){
        $this->layout->subtitle = '小工具域';
        
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/widget/customize'),
            'text'=>'使用实时预览管理',
            'html_options'=>array(
                'target'=>'_blank',
            )
        );
        
        //页面设置
        $this->settingForm('admin_widgetarea_index', '_setting_index');
        
        //已经关联到小工具域的小工具记录
        $relate_widgets = WidgetAreasWidgetsTable::model()->fetchAll(
            array(),
            array('widget_area_id', 'widget_id'),
            array('widget_area_id', 'sort')
        );
        $relate_widget_map = array();
        $relate_widget_ids = array();
        foreach($relate_widgets as $rw){
            $relate_widget_map[$rw['widget_area_id']][] = $rw['widget_id'];
            $relate_widget_ids[] = $rw['widget_id'];
        }
        
        $this->view->assign(array(
            //小工具域
            'widget_areas'=>WidgetAreaService::service()->getAll(),
            //所有小工具数据
            'widget_map'=>ArrayHelper::column(WidgetsTable::model()->fetchAll(array(), '*', 'id DESC'), null, 'id'),
            'relate_widget_map'=>$relate_widget_map,
            'relate_widget_ids'=>$relate_widget_ids,
        ))->render();
    }
    
    /**
     * 记录widget在widgetarea中的位置
     */
    public function setWidgets(){
        $widget_areas = $this->input->post('widget_areas', 'intval');
        
        foreach($widget_areas as $widget_area_id => $widget_ids){
            WidgetAreaService::service()->relateWidget($widget_area_id, $widget_ids);
        }
        
        Response::notify('success', '修改成功');
    }

    /**
     * 小工具域排序（此功能只是为了方便后台编辑）
     */
    public function setSort(){
        $widget_areas = $this->input->post('widget_areas', 'intval', array());
        $i = 1;
        foreach($widget_areas as $widget_area){
            WidgetAreasTable::model()->update(array(
                'sort'=>$i,
            ), $widget_area);
            $i++;
        }
    }
}