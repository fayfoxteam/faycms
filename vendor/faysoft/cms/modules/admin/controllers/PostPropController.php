<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\CategoriesTable;
use fay\helpers\HtmlHelper;
use cms\models\tables\PropsTable;
use cms\services\post\PostPropService;
use cms\models\tables\ActionlogsTable;
use cms\services\CategoryService;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\core\HttpException;

class PostPropController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'post';
    }
    
    public function index(){
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/post/cat'),
            'text'=>'返回文章分类',
        );
        
        $cat_id = $this->input->get('cat_id', 'intval');
        
        $cat = CategoriesTable::model()->fetchRow(array(
            'id = ?'=>$cat_id,
        ), 'title');
        if(!$cat){
            throw new HttpException('指定分类不存在');
        }
        
        $this->form()->setModel(PropsTable::model())
            ->setData(array(
                'refer'=>$cat_id,
            ));
        
        $this->layout->subtitle = '文章分类属性 - 分类: '.HtmlHelper::encode($cat['title']);
        
        $this->_setListview($cat_id);
        $this->view->render();
    }
    
    public function create(){
        if(!$this->input->post()){
            throw new HttpException('无数据提交', 500);
        }
        
        if($this->form()->setModel(PropsTable::model())->check()){
            $refer = $this->input->post('refer', 'intval');
            $prop = $this->form()->getFilteredData();
            $values = $this->input->post('prop_values', array());
            $prop_id = PostPropService::service()->create($refer, $prop, $values);
            
            $this->actionlog(ActionlogsTable::TYPE_POST_CAT, '添加了一个文章分类属性', $prop_id);
            
            Response::notify('success', array(
                'message'=>'文章分类属性添加成功',
                'id'=>$prop_id,
            ));
        }else{
            Response::goback();
        }
    }
    
    public function edit(){
        $prop_id = $this->input->get('id', 'intval');
        
        $this->form()->setModel(PropsTable::model());
        if($this->input->post() && $this->form()->check()){
            $refer = $this->input->post('refer', 'intval');
            $prop = $this->form()->getFilteredData();
            isset($prop['required']) || $prop['required'] = 0;
            
            $prop_values = $this->input->post('prop_values', array());
            $ids = $this->input->post('ids', 'intval', array('-1'));
            
            PostPropService::service()->update($refer, $prop_id, $prop, $prop_values, $ids);
            
            $this->actionlog(ActionlogsTable::TYPE_POST_CAT, '编辑了文章分类属性信息', $prop_id);
            
            Response::notify('success', '文章分类属性编辑成功', false);
        }
        
        $prop = PostPropService::service()->get($prop_id);

        if(!$prop){
            throw new HttpException('所选文章分类属性不存在');
        }
        $this->form()->setData($prop);
        $this->view->prop = $prop;
        
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/post-prop/index', array('cat_id'=>$prop['refer'])),
            'text'=>'添加文章分类属性',
        );
        $cat = CategoriesTable::model()->find($prop['refer'], 'title');
        $this->layout->subtitle = '编辑文章分类属性 - '.HtmlHelper::encode($cat['title']).' - '.HtmlHelper::encode($prop['title']);
        
        $this->_setListview($prop['refer']);
        $this->view->refer = $prop['refer'];
        
        $this->view->render();
    }
    
    public function delete(){
        $id = $this->input->get('id', 'intval');
        $prop = PropsTable::model()->find($id, 'refer');
        PostPropService::service()->delete($id);
        $this->actionlog(ActionlogsTable::TYPE_POST_CAT, '删除了一个文章分类属性', $id);
        
        //不能直接回到上一页，因为可能处在编辑状态
        Response::notify('success', '一个文章分类属性被删除', array('cms/admin/post-prop/index', array(
            'cat_id'=>$prop['refer'],
        )));
    }

    public function sort(){
        $id = $this->input->get('id', 'intval');
        PropsTable::model()->update(array(
            'sort'=>$this->input->get('sort', 'intval'),
        ), array(
            'id = ?'=>$id,
        ));
        $this->actionlog(ActionlogsTable::TYPE_POST_CAT, '改变了文章分类属性排序', $id);
        
        $data = PropsTable::model()->find($id, 'sort');
        Response::notify('success', array(
            'message'=>'一个文章分类属性排序值被编辑',
            'data'=>array(
                'sort'=>$data['sort'],
            ),
        ));
    }
    
    /**
     * 设置右侧项目列表
     * @param int $cat_id
     */
    private function _setListview($cat_id){
        $cat = CategoryService::service()->get($cat_id, 'left_value,right_value');
        $cat_parents = CategoriesTable::model()->fetchCol('id', array(
            'left_value <= '.$cat['left_value'],
            'right_value >= '.$cat['right_value'],
        ));
        $sql = new Sql();
        $sql->from('props')
            ->where(array(
                'delete_time = 0',
                'type = '.PropsTable::TYPE_POST_CAT,
                'refer IN ('.implode(',', $cat_parents).')',
            ))
            ->order('sort, id DESC');
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>15,
            'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
        ));
    }
}