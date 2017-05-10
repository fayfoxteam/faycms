<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\PropService;
use fay\common\ListView;
use fay\core\HttpException;
use fay\core\Response;
use cms\models\tables\PropsTable;
use fay\core\Sql;

/**
 * 通用分类属性
 */
class PropController extends AdminController{
    public function isAliasNotExist(){
        if(PropsTable::model()->fetchRow(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->get('id', 'intval', false),
        ))){
            Response::json('', 0, '别名已存在');
        }else{
            Response::json('', 1, '别名不存在');
        }
    }
    
    public function index(){
        $this->layout->subtitle = '添加属性';

        $this->_setListview();
        $this->view->render();
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
            'uri'=>array('cms/admin/props/index', $gets),
            'text'=>'添加属性',
        );

        $prop_id = $this->input->get('id', 'intval');

        $this->form()->setModel(PropsTable::model());
        if($this->input->post() && $this->form()->check()){
            $prop = $this->form()->getFilteredData();
            isset($prop['required']) || $prop['required'] = 0;

            $prop_values = $this->input->post('prop_values', array());
            $ids = $this->input->post('ids', 'intval', array('-1'));

            PropService::service()->update($prop_id, $prop, $prop_values, $ids);

            Response::notify('success', '文章分类属性编辑成功', false);
        }

        $prop = PropService::service()->get($prop_id);
        $this->layout->subtitle = '编辑属性 - ' . $prop['title'];

        if(!$prop){
            throw new HttpException('所选文章分类属性不存在');
        }
        $this->form()->setData($prop);
        $this->view->prop = $prop;


        $this->_setListview();
        $this->view->render();
    }
    
    public function delete(){
        $id = $this->input->get('id', 'intval');
        PropService::service()->delete($id);

        //不能直接回到上一页，因为可能处在编辑状态
        Response::notify('success', '一个文章分类属性被删除', array('cms/admin/prop/index'));
    }
    
    public function undelete(){
        
    }

    /**
     * 设置右侧列表
     */
    private function _setListview(){
        $sql = new Sql();
        $sql->from(array('p'=>'props'))
            ->order('p.id DESC');
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>15,
            'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
        ));
    }
}