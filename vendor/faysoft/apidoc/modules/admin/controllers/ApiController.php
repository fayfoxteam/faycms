<?php
namespace apidoc\modules\admin\controllers;

use apidoc\models\tables\ApidocApiErrorCodesTable;
use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use cms\services\SettingService;
use fay\helpers\ArrayHelper;
use cms\services\CategoryService;
use apidoc\models\tables\ApisTable;
use apidoc\models\tables\InputsTable;
use fay\core\Response;
use apidoc\models\tables\OutputsTable;
use apidoc\models\tables\ModelsTable;
use fay\core\ErrorException;

class ApiController extends AdminController{
    /**
     * box列表
     */
    public $boxes = array(
        array('name'=>'router', 'title'=>'路由'),
        array('name'=>'category', 'title'=>'分类'),
        array('name'=>'http_method', 'title'=>'HTTP请求方式'),
        array('name'=>'need_login', 'title'=>'是否需要登录'),
        array('name'=>'since', 'title'=>'自从'),
        array('name'=>'inputs', 'title'=>'请求参数'),
        array('name'=>'outputs', 'title'=>'响应参数'),
        array('name'=>'error_code', 'title'=>'错误码'),
        array('name'=>'sample_response', 'title'=>'响应示例'),
    );
    
    /**
     * 默认box排序
    */
    public $default_box_sort = array(
        'side'=>array(
            'router', 'category', 'http_method', 'need_login', 'since'
        ),
        'normal'=>array(
            'inputs', 'outputs', 'sample_response'
        ),
    );
    
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'apidoc-api';
    }
    
    public function index(){
        $this->layout->subtitle = 'API列表';
        
        $cat_id = $this->input->get('cat_id', 'intval', 0);
        
        if($this->checkPermission('apidoc/admin/api/create')){
            $this->layout->sublink = array(
                'uri'=>array('apidoc/admin/api/create', array(
                    'cat_id'=>$cat_id
                )),
                'text'=>'新增API',
            );
        }
        
        //页面设置
        $_settings = $this->settingForm('admin_api_index', '_setting_index', array(
            'cols'=>array('router', 'status', 'category', 'since', 'create_time'),
            'display_name'=>'nickname',
            'display_time'=>'short',
            'page_size'=>10,
        ));
        
        $this->view->enabled_boxes = $this->getEnabledBoxes('admin_api_boxes');
        
        $sql = new Sql();
        $sql->from(array('a'=>'apidoc_apis'));
        
        if(in_array('category', $_settings['cols'])){
            $sql->joinLeft(array('c'=>'categories'), 'a.cat_id = c.id', 'title AS cat_title');
        }
        
        if(in_array('user', $_settings['cols'])){
            $sql->joinLeft(array('u'=>'users'), 'a.user_id = u.id', 'username,nickname,realname');
        }
        
        //根据分类搜索
        if($cat_id){
            $sql->where('a.cat_id = ?', $cat_id);
        }
        
        //根据状态搜索
        if($this->input->get('status') !== null){
            $sql->where('a.status = ?', $this->input->get('status', 'intval'));
        }
        
        //时间段
        if($this->input->get('start_time')){
            $sql->where("a.{$this->input->get('time_field')} > ?", $this->input->get('start_time', 'strtotime'));
        }
        if($this->input->get('end_time')){
            $sql->where("a.{$this->input->get('time_field')} < ?", $this->input->get('end_time', 'strtotime'));
        }
        
        //关键词搜索
        if($this->input->get('keywords')){
            if(in_array($this->input->get('keywords_field'), array('user_id'))){
                $sql->where("a.{$this->input->get('keywords_field')} = ?", $this->input->get('keywords', 'intval'));
            }else{
                $sql->where("a.{$this->input->get('keywords_field')} LIKE ?", '%'.$this->input->get('keywords', 'trim').'%');
            }
        }
        
        //排序
        if($this->input->get('orderby')){
            $this->view->orderby = $this->input->get('orderby');
            $this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
            $sql->order("{$this->view->orderby} {$this->view->order}");
        }else{
            $sql->order('a.id DESC');
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size', 20),
            'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
        ));
        
        //各状态记录数
        $status_sql = new Sql();
        $status_counts = $status_sql->from(array('a'=>'apidoc_apis'), array('status', 'COUNT(*) AS count'))
            ->group('a.status')
            ->fetchAll();
        $this->view->status_counts = ArrayHelper::column($status_counts, 'count', 'status');
        
        //查找api分类
        $this->view->cats = CategoryService::service()->getTree('_system_api');
        
        $this->view->render();
    }
    
    public function create(){
        $this->layout->subtitle = '新增API';
        if($this->checkPermission('apidoc/admin/api/index')){
            $this->layout->sublink = array(
                'uri'=>array('apidoc/admin/api/index'),
                'text'=>'API列表',
            );
        }

        $this->form()->setModel(ApisTable::model());

        //启用的编辑框
        $_setting_key = 'admin_api_boxes';

        if($this->input->post() && $this->form()->check()){
            $data = ApisTable::model()->fillData($this->input->post(), true, 'insert');
            $data['create_time'] = $this->current_time;
            $data['update_time'] = $this->current_time;
            $data['user_id'] = $this->current_user;
            $api_id = ApisTable::model()->insert($data);

            //输入参数处理
            $inputs = $this->input->post('inputs');
            foreach($inputs as $i){
                $input = InputsTable::model()->fillData($i, true, 'insert');
                $input['api_id'] = $api_id;
                $input['create_time'] = $input['update_time'] = $this->current_time;
                InputsTable::model()->insert($input);
            }

            //输出参数处理
            $outputs = $this->input->post('outputs');
            $j = 0;
            foreach($outputs as $o){
                $j++;
                $model = ModelsTable::model()->fetchRow(array(
                    'name = ?'=>$o['model_name'],
                ), 'id');
                if(!$model){
                    throw new ErrorException('指定数据模型不存在', $o['model_name']);
                }

                $output = InputsTable::model()->fillData($o, true, 'insert');
                $output['api_id'] = $api_id;
                $output['sort'] = $j;
                $output['model_id'] = $model['id'];
                $output['create_time'] = $output['update_time'] = $this->current_time;
                OutputsTable::model()->insert($output);
            }

            //输入错误码
            $error_codes = $this->input->post('error_codes');
            foreach($error_codes as $error_code){
                $error_code = ApidocApiErrorCodesTable::model()->fillData($error_code, true, 'insert');
                $error_code['api_id'] = $api_id;
                $error_code['create_time'] = $error_code['update_time'] = $this->current_time;
                ApidocApiErrorCodesTable::model()->insert($error_code);
            }
            Response::notify('success', 'API添加成功', array('apidoc/admin/api/edit', array(
                'id'=>$api_id,
            )));
        }
        
        //分类树
        $this->view->cats = CategoryService::service()->getTree('_system_api');
        
        //可配置信息
        $_box_sort_settings = SettingService::service()->get('admin_api_box_sort');
        $_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
        $this->view->_box_sort_settings = $_box_sort_settings;
        
        //页面设置
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_edit', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        //输入参数表单规则
        $this->form('input-parameter')->setModel(InputsTable::model());
        
        //输出参数表单规则
        $this->form('output')->setModel(OutputsTable::model())
            ->setRule(array('model_name', 'required'))
            ->setRule(array('model_name', 'exist', array('table'=>'apidoc_models', 'field'=>'name')))
            ->setRule(array('model_name', 'ajax', array('url'=>array('apidoc/admin/model/is-name-exist'))))
            ->setLabels(array(
                'model_name'=>'模型名称',
            ));
        
        $this->view->render();
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑API';
        
        $api_id = $this->input->get('id', 'intval');
        $this->form()->setModel(ApisTable::model());
        
        //启用的编辑框
        $_setting_key = 'admin_api_boxes';
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        
        if($this->input->post() && $this->form()->check()){
            $data = ApisTable::model()->fillData($this->input->post(), true, 'update');
            $data['update_time'] = $this->current_time;
            ApisTable::model()->update($data, $api_id);

            if(in_array('inputs', $enabled_boxes)){
                //输入参数处理
                $inputs = $this->input->post('inputs');
                //获取已存在的输入参数
                $old_input_parameters = ArrayHelper::column(InputsTable::model()->fetchAll(array(
                    'api_id = ?' => $api_id,
                )), null, 'id');
                //删除已被删除的输入参数
                $deleted_inputs = array_diff(
                    ArrayHelper::column($old_input_parameters, 'id'),
                    array_keys($inputs)
                );
                if($deleted_inputs){
                    InputsTable::model()->delete(array(
                        'id IN (?)'=>$deleted_inputs,
                    ));
                }
                foreach($inputs as $input_parameter_id => $input){
                    if(isset($old_input_parameters[$input_parameter_id])){
                        $input = InputsTable::model()->fillData($input, true, 'update');
                        if(!ArrayHelper::equal($input, $old_input_parameters[$input_parameter_id])){
                            $input['update_time'] = $this->current_time;
                            InputsTable::model()->update($input, $input_parameter_id);
                        }
                    }else{
                        $input = InputsTable::model()->fillData($input, true, 'insert');
                        $input['api_id'] = $api_id;
                        $input['create_time'] = $input['update_time'] = $this->current_time;
                        InputsTable::model()->insert($input);
                    }
                }
            }

            if(in_array('outputs', $enabled_boxes)){
                //输出参数处理
                $outputs = $this->input->post('outputs');
                //获取已存在的输出参数
                $old_output_parameters = ArrayHelper::column(OutputsTable::model()->fetchAll(array(
                    'api_id = ?'=>$api_id,
                )), null, 'id');
                //删除已被删除的输出参数
                $deleted_outputs = array_diff(
                    ArrayHelper::column($old_output_parameters, 'id'),
                    array_keys($outputs)
                );
                if($deleted_outputs){
                    OutputsTable::model()->delete(array(
                        'id IN (?)'=>$deleted_outputs,
                    ));
                }
                $i = 0;
                foreach($outputs as $output_parameter_id => $o){
                    $i++;
                    $model = ModelsTable::model()->fetchRow(array(
                        'name = ?'=>$o['model_name'],
                    ), 'id');
                    if(!$model){
                        throw new ErrorException('指定数据模型不存在', $o['model_name']);
                    }
    
                    if(isset($old_output_parameters[$output_parameter_id])){
                        $output = OutputsTable::model()->fillData($o, true, 'update');
                        $output['model_id'] = $model['id'];
                        $output['sort'] = $i;
                        if(!ArrayHelper::equal($output, $old_output_parameters[$output_parameter_id])){
                            $output['update_time'] = $this->current_time;
                            OutputsTable::model()->update($output, $output_parameter_id);
                        }
                    }else{
                        $output = OutputsTable::model()->fillData($o, true, 'insert');
                        $output['api_id'] = $api_id;
                        $output['model_id'] = $model['id'];
                        $output['sort'] = $i;
                        $output['create_time'] = $output['update_time'] = $this->current_time;
                        OutputsTable::model()->insert($output);
                    }
                }
            }

            //处理错误码
            if(in_array('error_code', $enabled_boxes)){
                $error_codes = $this->input->post('error_codes');
                //获取已存在的错误码
                $old_error_codes = ArrayHelper::column(ApidocApiErrorCodesTable::model()->fetchAll(array(
                    'api_id = ?'=>$api_id,
                )), null, 'id');
                //删除已被删除的错误码
                $deleted_error_codes = array_diff(
                    ArrayHelper::column($old_error_codes, 'code'),
                    ArrayHelper::column($error_codes, 'code')
                );
                if($deleted_error_codes){
                    ApidocApiErrorCodesTable::model()->delete(array(
                        'api_id = ?'=>$api_id,
                        'code IN (?)'=>$deleted_error_codes,
                    ));
                }
                foreach($error_codes as $error_code_id => $error_code){
                    if(empty($error_code['code'])){
                        //code为空的跳过
                        continue;
                    }
                    if(isset($old_error_codes[$error_code_id])){
                        $error_code = ApidocApiErrorCodesTable::model()->fillData($error_code, true, 'update');
                        if(!ArrayHelper::equal($error_code, $old_error_codes[$error_code_id])){
                            $error_code['update_time'] = $this->current_time;
                            ApidocApiErrorCodesTable::model()->update($error_code, $error_code_id);
                        }
                    }else{
                        $error_code = ApidocApiErrorCodesTable::model()->fillData($error_code, true, 'insert');
                        $error_code['api_id'] = $api_id;
                        $error_code['create_time'] = $error_code['update_time'] = $this->current_time;
                        ApidocApiErrorCodesTable::model()->insert($error_code);
                    }
                }
            }

            Response::notify('success', 'API编辑成功', false);
        }

        $api = ApisTable::model()->find($api_id);
        $this->form()->setData($api);

        if($this->checkPermission('apidoc/admin/api/create')){
            $this->layout->sublink = array(
                'uri'=>array('apidoc/admin/api/create', array(
                    'cat_id'=>$api['cat_id'],
                )),
                'text'=>'添加API',
            );
        }

        //原输入参数
        $this->view->inputs = InputsTable::model()->fetchAll('api_id = '.$api_id, '*', 'required DESC, name ASC');

        //分类树
        $this->view->cats = CategoryService::service()->getTree('_system_api');

        //可配置信息
        $_box_sort_settings = SettingService::service()->get('admin_api_box_sort');
        $_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
        $this->view->_box_sort_settings = $_box_sort_settings;

        //页面设置
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_edit', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));

        //输入参数表单规则
        $this->form('input-parameter')->setModel(InputsTable::model());

        //输出参数表单规则
        $this->form('output')->setModel(OutputsTable::model())
            ->setRule(array('model_name', 'required'))
            ->setRule(array('model_name', 'exist', array('table'=>'apidoc_models', 'field'=>'name')))
            ->setRule(array('model_name', 'ajax', array('url'=>array('apidoc/admin/model/is-name-exist'))))
            ->setLabels(array(
                'model_name'=>'模型名称',
            ));

        //原属性
        $sql = new Sql();
        $this->view->outputs = $sql->from(array('o'=>OutputsTable::model()->getTableName()))
            ->joinLeft(array('m'=>ModelsTable::model()->getTableName()), 'o.model_id = m.id', 'name AS model_name')
            ->where('o.api_id = ?', $api_id)
            ->order('o.sort')
            ->fetchAll();

        //错误码
        $this->view->error_codes = ApidocApiErrorCodesTable::model()->fetchAll(array(
            'api_id = ?'=>$api_id,
        ));
        $this->view->render();
    }
    
    /**
     * 分类管理
     */
    public function cat(){
        $this->layout->current_directory = 'api';
        
        //页面设置
        $this->settingForm('admin_api_cat', '_setting_cat', array(
            'default_dep'=>2,
        ));
        
        $this->layout->subtitle = 'API分类';
        $this->view->cats = CategoryService::service()->getTree('_system_api');
        $root_node = CategoryService::service()->getByAlias('_system_api', 'id');
        $this->view->root = $root_node['id'];
        
        if($this->checkPermission('apidoc/admin/api/cat-create')){
            $this->layout->sublink = array(
                'uri'=>'#create-cat-dialog',
                'text'=>'添加分类',
                'html_options'=>array(
                    'class'=>'create-cat-link',
                    'data-title'=>'API',
                    'data-id'=>$root_node['id'],
                ),
            );
        }
        
        $this->view->render();
    }

    /**
     * 判断API路由是否可用
     * 可用返回状态为1，不可用返回0，http状态码均为200
     * @parameter string $router 路由
     */
    public function isRouterNotExist(){
        //表单验证
        $this->form()->setRules(array(
            array('router', 'required'),
        ))->setFilters(array(
            'router'=>'trim',
        ))->setLabels(array(
            'router'=>'路由',
        ))->check();

        if(ApisTable::model()->fetchRow(array(
            'router = ?'=>$this->form()->getData('router'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            Response::json('', 0, '接口路由已存在');
        }else{
            Response::json();
        }
    }

    /**
     * 判断指定Api Id是否存在
     * 存在返回状态为1，不可用返回0，http状态码均为200
     * @parameter string $api_id
     */
    public function isApiIdExist(){
        //表单验证
        $this->form()->setRules(array(
            array('api_id', 'required'),
        ))->setFilters(array(
            'api_id'=>'intval',
        ))->setLabels(array(
            'api_id'=>'Api Id',
        ))->check();

        if(ApisTable::model()->fetchRow(array(
            'id = ?'=>$this->form()->getData('api_id'),
        ))){
            Response::json();
        }else{
            Response::json('', 0, 'Api Id不存在');
        }
    }
}