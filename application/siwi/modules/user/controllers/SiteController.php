<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\models\tables\PostsTable;
use fay\models\tables\FilesTable;
use fay\models\tables\PostsFilesTable;
use fay\models\Tag;
use fay\services\CategoryService;
use fay\core\Sql;
use fay\core\HttpException;
use fay\services\FlashService;

class SiteController extends UserController{
    private $rules = array(
        array(array('title', 'abstract'), 'string', array('max'=>500)),
        array(array('title', 'cat_id'), 'require'),
        array(array('cat_id', 'file', 'thumbnail'), 'int'),
        array(array('cat_id'), 'exist', array('table'=>'categories', 'field'=>'id')),
    );
    
    public function __construct(){
        parent::__construct();
    
        $this->layout->current_directory = 'site';
    }
    
    public function create(){
        $this->layout->title = '收集网站';
        
        $this->form()->setRules($this->rules);
        if($this->input->post()){
            if($this->form()->check()){
                $abstract = $this->input->post('abstract');
                $content = $this->input->post('content');
                $abstract || $abstract = mb_substr(strip_tags($content), 0, 100);
                $post_id = PostsTable::model()->insert(array(
                    'title'=>$this->input->post('title'),
                    'cat_id'=>$this->input->post('cat_id', 'intval'),
                    'thumbnail'=>$this->input->post('thumbnail', 'intval', 0),
                    'abstract'=>$abstract,
                    'create_time'=>$this->current_time,
                    'user_id'=>$this->current_user,
                    'publish_time'=>$this->current_time,
                    'status'=>PostsTable::STATUS_PUBLISHED,
                ));
    
                if($f = $this->input->post('file', 'intval', 0)){
                    $file = FilesTable::model()->find($f, 'client_name,is_image');
                    if($file){
                        PostsFilesTable::model()->insert(array(
                            'file_id'=>$f,
                            'post_id'=>$post_id,
                            'desc'=>$file['client_name'],
                            'is_image'=>$file['is_image'],
                            'sort'=>1,
                        ));
                    }
                }
                
                //多张预览图
                $files = $this->input->post('files', 'intval', array());
                $i = 1;
                foreach($files as $f){
                    $i++;
                    $file = FilesTable::model()->find($f, 'is_image,client_name');
                    if(!$file['is_image'])continue;
                    PostsFilesTable::model()->insert(array(
                        'file_id'=>$f,
                        'post_id'=>$post_id,
                        'desc'=>$file['client_name'],
                        'is_image'=>1,
                        'sort'=>$i,
                    ));
                }
                
                
                TagTable::model()->set($this->input->post('tags'), $post_id);
    
                Response::notify('success', '网站发布成功', array('user/site/edit', array(
                    'id'=>$post_id,
                )));
            }else{
                FlashService::set('参数异常');
            }
        }
        $this->view->cats = CategoryService::service()->getNextLevel('_site');
        
        $this->view->render();
    }
    
    public function edit(){
        $this->layout->title = '编辑网站';
        
        $id = $this->input->get('id', 'intval');
        if(!$id){
            throw new HttpException('不完整的请求');
        }
        
        $post = PostsTable::model()->find($id);
        if(!$post){
            throw new HttpException('作品编号不存在');
        }
        if($post['user_id'] != $this->current_user){
            throw new HttpException('您无权限编辑此作品');
        }
        
        $this->form()->setRules($this->rules);
        if($this->input->post()){
            if($this->form()->check()){
                $abstract = $this->input->post('abstract');
                $content = $this->input->post('content');
                $abstract || $abstract = mb_substr(strip_tags($content), 0, 100);
                PostsTable::model()->update(array(
                    'title'=>$this->input->post('title'),
                    'cat_id'=>$this->input->post('cat_id', 'intval'),
                    'thumbnail'=>$this->input->post('thumbnail', 'intval', 0),
                    'abstract'=>$abstract,
                    'create_time'=>$this->current_time,
                    'user_id'=>$this->current_user,
                    'publish_time'=>$this->current_time,
                    'status'=>PostsTable::STATUS_PUBLISHED,
                ), $id);
                
                $f = $this->input->post('file', 'intval', 0);
                if($f){
                    $file = PostsFilesTable::model()->fetchRow(array(
                        'post_id = '.$post['id'],
                        'is_image = 0',
                    ), 'file_id');
                    if($f != $file['file_id']){
                        PostsFilesTable::model()->delete(array(
                            'post_id = '.$post['id'],
                            'is_image = 0',
                        ));
                        $file = FilesTable::model()->find($f, 'client_name,is_image');
                        if($file){
                            PostsFilesTable::model()->insert(array(
                                'file_id'=>$f,
                                'post_id'=>$id,
                                'desc'=>$file['client_name'],
                                'is_image'=>$file['is_image'],
                                'sort'=>1,
                            ));
                        }
                    }
                }else{
                    PostsFilesTable::model()->delete(array(
                        'post_id = '.$post['id'],
                        'is_image = 0',
                    ));
                }
                
                $files = $this->input->post('files', 'intval', array());
                //删除已被删除的图片
                if($files){
                    PostsFilesTable::model()->delete(array(
                        'post_id = ?'=>$post['id'],
                        'file_id NOT IN ('.implode(',', $files).')',
                        'is_image = 1',
                    ));
                }else{
                    PostsFilesTable::model()->delete(array(
                        'post_id = ?'=>$post['id'],
                        'is_image = 1',
                    ));
                }
                //获取已存在的图片
                $old_files_ids = PostsFilesTable::model()->fetchCol('file_id', array(
                    'post_id = ?'=>$post['id'],
                    'is_image = 1',
                ));
                $i = 1;
                foreach($files as $f){
                    $i++;
                    if(in_array($f, $old_files_ids)){
                        PostsFilesTable::model()->update(array(
                            'sort'=>$i,
                        ), array(
                            'post_id = ?'=>$post['id'],
                            'file_id = ?'=>$f,
                        ));
                    }else{
                        $file = FilesTable::model()->find($f, 'is_image,client_name');
                        if(!$file['is_image'])continue;
                        PostsFilesTable::model()->insert(array(
                            'file_id'=>$f,
                            'post_id'=>$post['id'],
                            'desc'=>$file['client_name'],
                            'sort'=>$i,
                            'is_image'=>1,
                        ));
                    }
                }
    
                TagTable::model()->set($this->input->post('tags'), $post['id']);
                
                FlashService::set('作品编辑成功', 'success');
                
                $post = PostsTable::model()->find($id);
            }else{
                FlashService::set('参数异常');
            }
        }
        
        $this->form()->setData($post);
        
        //parent cat
        $cat = CategoryService::service()->get($post['cat_id'], 'parent');
        $this->form()->setData(array('parent_cat'=>$cat['parent']));
        
        //tags
        $sql = new Sql();
        $tags = $sql->from(array('pt'=>'posts_tags'), '')
            ->joinLeft(array('t'=>'tags'), 'pt.tag_id = t.id', 'title')
            ->where('pt.post_id = '.$post['id'])
            ->fetchAll();
        $tag_titles = array();
        foreach($tags as $t){
            $tag_titles[] = $t['title'];
        }
        $this->form()->setData(array('tags'=>implode(',', $tag_titles)));
        
        //file
        $file = PostsFilesTable::model()->fetchRow(array(
            'post_id = '.$post['id'],
            'is_image = 0',
        ), 'file_id,desc');
        $this->view->file = $file;
        $this->form()->setData(array('file'=>isset($file['file_id']) ? $file['file_id'] : ''));
        
        //files
        $files = PostsFilesTable::model()->fetchAll(array(
            'post_id = '.$post['id'],
            'is_image = 1',
        ), 'file_id,desc', 'sort');
        $this->view->files = $files;
        
        $this->view->cats = CategoryService::service()->getNextLevel('_site');
        $this->view->render();
    }
}