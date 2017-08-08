<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\PropsTable;
use cms\services\prop\ItemPropService;
use cms\services\prop\PropService;
use fay\common\ListView;
use fay\core\HttpException;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\HtmlHelper;

/**
 * 自定义属性
 */
class PropController extends AdminController{
    public function isAliasNotExist(){
        if(PropsTable::model()->has(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            Response::json('', 0, '别名已存在');
        }else{
            Response::json('', 1, '别名不存在');
        }
    }
    
    public function index(){
        $this->layout->subtitle = '添加自定义属性';

        //表单验证
        $this->form()->setModel(PropsTable::model());

        $this->_setListview();
        return $this->view->render();
    }
    
    public function create(){
        if(!$this->input->post()){
            throw new HttpException('无数据提交');
        }

        if($this->form()->setModel(PropsTable::model())->check()){
            $prop = $this->form()->getFilteredData();
            $values = $this->input->post('prop_values', array());
            
            $prop_id = PropService::service()->create($prop, $values);

            Response::notify('success', array(
                'message'=>'自定义属性添加成功',
                'id'=>$prop_id,
            ));
        }else{
            Response::goback();
        }
    }
    
    public function edit(){
        $gets = $this->input->get();
        unset($gets['id']);
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/prop/index', $gets),
            'text'=>'添加自定义属性',
        );

        $prop_id = $this->input->get('id', 'intval');

        $this->form()->setModel(PropsTable::model());
        if($this->input->post() && $this->form()->check()){
            $prop = $this->form()->getFilteredData();
            isset($prop['required']) || $prop['required'] = 0;

            $prop_values = $this->input->post('prop_values', 'trim', array());

            PropService::service()->update($prop_id, $prop, $prop_values);

            Response::notify('success', '自定义属性编辑成功', false);
        }

        $prop = PropService::service()->get($prop_id);
        $this->layout->subtitle = '编辑自定义属性 - ' . $prop['title'];

        if(!$prop){
            throw new HttpException('所选自定义属性不存在');
        }
        $this->form()->setData($prop);
        $this->view->prop = $prop;


        $this->_setListview();
        return $this->view->render();
    }

    /**
     * 删除属性
     * @parameter int $id
     */
    public function delete(){
        $id = $this->input->get('id', 'intval');
        PropService::service()->delete($id);

        //不能直接回到上一页，因为可能处在编辑状态
        Response::notify(
            'success',
            '一个自定义属性被删除' . HtmlHelper::link('还原', array('cms/admin/prop/undelete', array(
                'id'=>$id,
            )), array('class'=>'ml5'), true),
            array('cms/admin/prop/index'));
    }

    /**
     * 还原属性
     * @parameter int $id
     */
    public function undelete(){
        $id = $this->input->get('id', 'intval');
        PropService::service()->undelete($id);

        Response::notify('success', '一个自定义属性被还原');
    }

    /**
     * json返回可用属性列表
     */
    public function listAction(){
        //搜索条件验证，异常数据直接返回404
        $this->form()->setScene('final')->setRules(array(
            array('usage_type', 'required'),
            array('page_size', 'int', array('min'=>1)),
        ))->setFilters(array(
            'usage_type'=>'intval',
        ))->check();
        
        $sql = new Sql();
        $sql->from(array('p'=>'props'))
            ->where('usage_type = ?', $this->form()->getData('usage_type'))
            ->where('delete_time = 0')
        ;

        $listview = new ListView($sql, array(
            'page_size'=>$this->form()->getData('page_size', 10),
        ));
        
        $data = $listview->getData();
        foreach($data as $key => $prop){
            $element = ItemPropService::$elementMap[$prop['element']];
            $data[$key]['element_name'] = $element::getName();
        }

        Response::json(array(
            'props'=>$data,
            'pager'=>$listview->getPager(),
        ));
    }

    /**
     * 获取单个属性信息
     */
    public function item(){
        //搜索条件验证，异常数据直接返回404
        $this->form()->setScene('final')->setRules(array(
            array('id', 'required'),
            array('id', 'int', array('min'=>1)),
        ))->setFilters(array(
            'id'=>'intval',
        ))->check();

        $prop = PropsTable::model()->fetchRow(array(
            'id = ?'=>$this->form()->getData('id'),
            'delete_time = 0',
        ), '!delete_time');
        if(!$prop){
            throw new HttpException("指定属性ID[{$this->form()->getData('id')}]不存在或已删除");
        }

        $element = ItemPropService::$elementMap[$prop['element']];
        $prop['element_name'] = $element::getName();
        
        Response::json(array(
            'prop'=>$prop,
        ));
    }

    /**
     * 设置右侧列表
     */
    private function _setListview(){
        $sql = new Sql();
        $sql->from(array('p'=>'props'))
            ->where('delete_time = 0')
            ->order('p.id DESC');
        
        if($this->input->get('search_usage_type')){
            $sql->where('p.usage_type = ?', $this->input->get('search_usage_type', 'intval'));
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>15,
            'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
        ));
    }
}