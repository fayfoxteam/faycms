<?php
namespace cms\modules\admin\controllers;

use cms\helpers\WidgetHelper;
use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\WidgetAreasWidgetsTable;
use cms\models\tables\WidgetsTable;
use cms\services\widget\WidgetAreaService;
use fay\common\ListView;
use fay\core\HttpException;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\LocalFileHelper;
use fay\helpers\StringHelper;
use Michelf\Markdown;

class WidgetController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'site';
    }
    
    public function index(){
        $this->layout->subtitle = '所有小工具';
        
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/widgetarea/index'),
            'text'=>'小工具域',
        );
        
        $widget_instances = array();
        
        //获取当前application下的widgets
        $app_widgets = LocalFileHelper::getFileList(APPLICATION_PATH . 'widgets');
        foreach($app_widgets as $w){
            $widget_instances[] = \F::widget()->get($w['name'], 'Admin');
        }
        
        //获取系统公用widgets
        $common_widgets = LocalFileHelper::getFileList(FAYSOFT_PATH . 'cms' . DS . 'widgets');
        foreach($common_widgets as $w){
            if($w['name'] == 'admin'){
                //目前来说就一个admin是个后台用的widget，感觉没什么必要再给其他widget分层级了，现在这样特殊判断一下好了
                continue;
            }
            if($instance = \F::widget()->get('cms/'.$w['name'], 'Admin')){
                $widget_instances[] = $instance;
            }
        }
        
        $this->view->widgets = $widget_instances;

        //小工具域列表
        $this->view->widget_areas = WidgetAreaService::service()->getAll();
        
        $this->view->render();
    }
    
    public function edit(){
        //很多小工具包含代码提交，发送此header防止chrome阻止提交
        header('X-XSS-Protection: 0');
        
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/widgetarea/index'),
            'text'=>'小工具域',
        );
        
        $id = $this->input->get('id', 'intval');

        $widget = WidgetsTable::model()->find($id);
        if(!$widget){
            throw new HttpException('指定的小工具ID不存在');
        }
        $widget_obj = \F::widget()->get($widget['widget_name'], 'Admin');
        
        if(file_exists($widget_obj->path . 'README.md')){
            $this->layout->_help_content = '<div class="text">' . Markdown::defaultTransform(file_get_contents($widget_obj->path . 'README.md')) . '</div>';
        }
        
        $this->form('widget')->setRules(array(
            array('f_widget_alias', 'string', array('max'=>255,'format'=>'alias')),
            array('f_widget_alias', 'required'),
            array('f_widget_description', 'string', array('max'=>255)),
            array('f_widget_alias', 'unique', array('table'=>'widgets', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('cms/admin/widget/is-alias-not-exist'))),
            
        ))->setLabels(array(
            'f_widget_alias'=>'别名',
            'f_widget_description'=>'描述',
        ));
        
        $widget_admin = \F::widget()->get($widget['widget_name'], 'Admin');
        $this->form('widget')->setRules($widget_admin->rules())
            ->setLabels($widget_admin->labels())
            ->setFilters($widget_admin->filters());
        
        if($this->input->post() && $this->form('widget')->check()){
            $f_widget_cache = $this->input->post('f_widget_cache');
            $f_widget_cache_expire = $this->input->post('f_widget_cache_expire', 'intval');
            $alias = $this->input->post('f_widget_alias', 'trim');
            WidgetsTable::model()->update(array(
                'alias'=>$alias,
                'description'=>$this->input->post('f_widget_description', 'trim'),
                'enabled'=>$this->input->post('f_widget_enabled') ? 1 : 0,
                'ajax'=>$this->input->post('f_widget_ajax') ? 1 : 0,
                'cache'=>$f_widget_cache && $f_widget_cache_expire >= 0 ? $f_widget_cache_expire : -1,
            ), $id);
            
            $widget_obj->alias = $alias;
            if(method_exists($widget_obj, 'onPost')){
                $widget_obj->onPost();
            }
            $widget = WidgetsTable::model()->find($id);
            \F::cache()->delete($alias);
        }
        
        $this->view->widget = $widget;
        if($widget['config']){
            $this->view->widget_config = json_decode($widget['config'], true);
        }else{
            $this->view->widget_config = array();
        }
        
        $widget_admin->initConfig(json_decode($widget['config'], true));
        $this->form('widget')->setData($widget_admin->config, true);
        $this->view->widget_admin = $widget_admin;
        $this->layout->subtitle = '编辑小工具  - '.$this->view->widget_admin->title;

        $this->view->render();
    }
    
    /**
     * 加载一个widget
     */
    public function render(){
        if($this->input->get('name')){
            $widget_obj = \F::widget()->get($this->input->get('name', 'trim'));
            if($widget_obj == null){
                throw new HttpException('Widget不存在或已被删除');
            }
            $action = StringHelper::hyphen2case($this->input->get('action', 'trim', 'index'), false);
            if(method_exists($widget_obj, $action)){
                $widget_obj->{$action}($this->input->get());
            }else if(method_exists($widget_obj, $action.'Action')){
                $widget_obj->{$action.'Action'}($this->input->get());
            }else{
                throw new HttpException('Widget方法不存在');
            }
        }else{
            throw new HttpException('不完整的请求');
        }
    }
    
    public function createInstance(){
        if($this->input->post()){
            $widget_id = WidgetsTable::model()->insert(array(
                'widget_name'=>$this->input->post('widget_name', ''),
                'alias'=>$this->input->post('alias') ? $this->input->post('alias') : 'w' . uniqid(),
                'description'=>$this->input->post('description', ''),
                'config'=>'',
            ));
            
            if($this->input->post('widget_area')){
                //与小工具域关联
                WidgetAreasWidgetsTable::model()->insert(array(
                    'widget_area_id'=>$this->input->post('widget_area', 'intval'),
                    'widget_id'=>$widget_id,
                    'sort'=>255,
                ));
            }
            $this->actionlog(ActionlogsTable::TYPE_WIDGET, '创建了一个小工具实例', $widget_id);
            
            Response::notify('success', '小工具实例创建成功', array('cms/admin/widget/edit', array(
                'id'=>$widget_id,
            )));
        }else{
            throw new HttpException('不完整的请求');
        }
    }

    /**
     * 已创建widget列表
     */
    public function instances(){
        $this->layout->subtitle = '小工具实例';
        
        //页面设置
        $this->settingForm('admin_widget_instances', '_setting_instance', array(
            'page_size'=>20,
        ));
        
        $sql = new Sql();
        $sql->from('widgets')
            ->order('id DESC');
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size', 20),
            'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
        ));
        
        $this->view->render();
    }

    /**
     * 删除widget实例
     */
    public function removeInstance(){
        $id = $this->input->get('id', 'intval');
        WidgetsTable::model()->delete($id);
        WidgetAreasWidgetsTable::model()->delete(array(
            'widget_id = ?'=>$id,
        ));
        $this->actionlog(ActionlogsTable::TYPE_WIDGET, '删除了一个小工具实例', $id);

        Response::notify('success', array(
            'message'=>'一个小工具实例被删除',
        ));
    }

    /**
     * 判断widget别名是否存在
     */
    public function isAliasNotExist(){
        if(WidgetsTable::model()->fetchRow(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false)
        ))){
            Response::json('', 0, '别名已存在');
        }else{
            Response::json();
        }
    }

    /**
     * 复制widget
     * @throws HttpException
     */
    public function copy(){
        $id = $this->input->get('id', 'intval');
        $widget = WidgetsTable::model()->find($id);
        if(!$widget){
            throw new HttpException('指定小工具ID不存在');
        }
        
        $widget_id = WidgetsTable::model()->insert(array(
            'alias'=>'w' . uniqid(),
            'config'=>$widget['config'],
            'widget_name'=>$widget['widget_name'],
            'description'=>$widget['description'],
            'enabled'=>$widget['enabled'],
            'ajax'=>$widget['ajax'],
            'cache'=>$widget['cache'],
        ));
        
        if($this->input->get('widget_area_id')){
            //若是从小工具域中点击的复制，默认将复制结果关联到对应小工具域
            WidgetAreasWidgetsTable::model()->insert(array(
                'widget_area_id'=>$this->input->get('widget_area_id', 'intval'),
                'widget_id'=>$widget_id,
                'sort'=>0,
            ));
        }
        
        $this->actionlog(ActionlogsTable::TYPE_WIDGET, '复制了小工具实例' . $id, $widget_id);
        
        Response::notify('success', array(
            'message'=>'一个小工具实例被复制',
        ), array('cms/admin/widgetarea/index'));
    }

    /**
     * 获取view文件内容
     * 只允许指定当前app内views下的文件，或widget默认模版
     * @parameter int $widget_id
     * @parameter string $view
     */
    public function getViewFile(){
        $view = $this->form()->getData('view');
        if($view == ''){
            //返回默认模版
            $widget = WidgetsTable::model()->find($this->input->get('id', 'intval'));
            if(!$widget){
                throw new HttpException('指定小工具ID不存在');
            }
            
            $widgetInstance = \F::widget()->get($widget['widget_name']);
            if(!$widgetInstance){
                throw new HttpException('widget不存在或已被删除');
            }
            
            Response::json($widgetInstance->getDefaultTemplate());
        }else{
            //返回view文件内容
            $view_file_content = WidgetHelper::getViewByRouter($view);
            if($view_file_content === false){
                Response::json('', 0, "找不到view文件[{$view}]");
            }else{
                Response::json($view_file_content);
            }
        }
    }
}