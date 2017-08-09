<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\PostsTagsTable;
use cms\models\tables\TagCounterTable;
use cms\models\tables\TagsTable;
use fay\common\ListView;
use fay\core\HttpException;
use fay\core\Response;
use fay\core\Sql;

class TagController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'post';
    }
    
    public function index(){
        $this->layout->subtitle = '标签';
        
        $this->_setListview();
        
        $this->form()->setModel(TagsTable::model());
        
        return $this->view->render();
    }
    
    public function create(){
        $this->form()->setModel(TagsTable::model());
        if($this->input->post()){
            if($this->form()->check()){
                $data = TagsTable::model()->fillData($this->input->post());
                $data['user_id'] = $this->current_user;
                $data['create_time'] = $this->current_time;
                $tag_id = TagsTable::model()->insert($data);
                TagCounterTable::model()->insert(array(
                    'tag_id'=>$tag_id,
                ));
                $this->actionlog(ActionlogsTable::TYPE_TAG, '创建了标签', $tag_id);
                
                $tag = TagsTable::model()->find($tag_id, 'id,title');
                Response::notify('success', array(
                    'message'=>'标签创建成功',
                    'tag'=>$tag,
                ));
            }else{
                Response::goback();
            }
        }else{
            Response::notify('error', array(
                'message'=>'不完整的请求',
            ));
        }
    }
    
    public function remove(){
        $tag_id = $this->input->get('id', 'intval');
        TagsTable::model()->delete(array('id = ?'=>$tag_id));
        PostsTagsTable::model()->delete(array('tag_id = ?'=>$tag_id));
        $this->actionlog(ActionlogsTable::TYPE_TAG, '删除了标签', $tag_id);
        
        $gets = $this->input->get();
        unset($gets['tag_id']);
        Response::notify('success', array(
            'message'=>'一个标签被永久删除',
        ), array('cms/admin/link/edit', $gets));
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑标签';
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/tag/index', $this->input->get()),
            'text'=>'添加标签',
        );
        $tag_id = $this->input->get('id', 'intval');
        $this->form()->setModel(TagsTable::model());
        if($this->input->post() && $this->form()->check()){
            TagsTable::model()->update($this->form()->getAllData(), $tag_id, true);
            $this->actionlog(ActionlogsTable::TYPE_TAG, '编辑了标签', $tag_id);
            Response::notify('success', '一个标签被编辑', false);
        }
        if($tag = TagsTable::model()->find($tag_id)){
            $this->form()->setData($tag);
            $this->view->tag = $tag;
            
            $this->_setListview();
            
            return $this->view->render();
        }else{
            throw new HttpException('无效的ID');
        }
    }
    
    public function sort(){
        $tag_id = $this->input->get('id', 'intval');
        TagsTable::model()->update(array(
            'sort'=>$this->input->get('sort', 'intval'),
        ), array(
            'id = ?'=>$tag_id,
        ));
        $this->actionlog(ActionlogsTable::TYPE_TAG, '改变了标签排序', $tag_id);
        
        $tag = TagsTable::model()->find($tag_id, 'sort');
        Response::notify('success', array(
            'message'=>'一篇标签的排序值被编辑',
            'data'=>array(
                'sort'=>$tag['sort'],
            ),
        ));
    }
    
    /**
     * 设置右侧列表
     */
    private function _setListview(){
        //搜索条件验证，异常数据直接返回404
        $this->form('search')->setScene('final')->setRules(array(
            array('orderby', 'range', array(
                'range'=>array_merge(
                    TagsTable::model()->getFields(),
                    TagCounterTable::model()->getFields()
                ),
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
        ))->check();
        
        $sql = new Sql();
        $sql->from(array('t'=>'tags'))
            ->joinLeft(array('tc'=>'tag_counter'), 't.id = tc.tag_id', TagCounterTable::model()->getFields(array('tag_id')));
        
        if($this->input->get('orderby')){
            $this->view->orderby = $this->input->get('orderby');
            $this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
            if(in_array($this->view->orderby, TagCounterTable::model()->getFields())){
                $sql->order("tc.{$this->view->orderby} {$this->view->order}");
            }else{
                $sql->order("t.{$this->view->orderby} {$this->view->order}");
            }
        }else{
            $sql->order('t.id DESC');
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size' => 15,
            'empty_text'=>'<tr><td colspan="3" align="center">无相关记录！</td></tr>',
        ));
    }
    
    public function search(){
        $tags = TagsTable::model()->fetchAll(array(
            'title LIKE ?'=>'%'.$this->input->get('key', false).'%'
        ), 'id,title', 'sort', 20);
        
        return Response::json($tags);
    }
}