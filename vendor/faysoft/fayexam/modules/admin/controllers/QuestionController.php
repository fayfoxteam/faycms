<?php
namespace fayexam\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fayexam\models\tables\ExamQuestionsTable;
use cms\models\tables\ActionlogsTable;
use fay\helpers\HtmlHelper;
use cms\services\CategoryService;
use fayexam\models\tables\ExamAnswersTable;
use fay\core\Response;
use fayexam\models\tables\ExamExamsQuestionsTable;
use cms\services\FlashService;
use fay\helpers\StringHelper;

class QuestionController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'exam-question';
    }
    
    public function index(){
        $this->layout->subtitle = '试题库';
        
        $this->layout->sublink = array(
            'uri'=>array('fayexam/admin/question/create'),
            'text'=>'添加试题',
        );
        
        $sql = new Sql();
        $sql->from(array('q'=>'exam_questions'))
            ->joinLeft(array('c'=>'categories'), 'q.cat_id = c.id', 'title AS cat_title')
            ->where('delete_time = 0')
            ->order('id DESC');
        
        if($this->input->get('keywords')){
            $sql->where(array(
                'question LIKE ?'=>'%'.$this->input->get('keywords', 'addslashes').'%',
            ));
        }
        
        if($this->input->get('cat_id')){
            $sql->where(array(
                'cat_id = ?'=>$this->input->get('cat_id', 'intval'),
            ));
        }
        
        if($this->input->get('type')){
            $sql->where(array(
                'type = ?'=>$this->input->get('type', 'intval'),
            ));
        }
        
        if($this->input->get('start_time')){
            $sql->where(array(
                'q.create_time > ?'=>$this->input->get('start_time', 'strtotime'),
            ));
        }
        if($this->input->get('end_time')){
            $sql->where(array(
                'q.create_time < ?'=>$this->input->get('end_time', 'strtotime'),
            ));
        }
        
        $this->view->listview = new ListView($sql, array(
            'empty_text'=>'<tr><td colspan="7" align="center">无相关记录！</td></tr>',
        ));

        //分类树
        $this->view->cats = CategoryService::service()->getTree('_system_exam_question');
        $this->view->render();
    }
    
    public function create(){
        $this->layout->subtitle = '添加试题';
        
        $this->form()->setModel(ExamQuestionsTable::model());
        
        if($this->input->post() && $this->form()->check()){
            $question_id = ExamQuestionsTable::model()->insert(array(
                'question'=>$this->input->post('question'),
                'cat_id'=>$this->input->post('cat_id', 'intval', 0),
                'score'=>$this->input->post('score', 'floatval'),
                'type'=>$this->input->post('type', 'intval'),
                'sort'=>$this->input->post('sort', 'intval', 100),
                'status'=>$this->input->post('status', 'intval'),
                'rand'=>$this->input->post('rand', 'intval', 0),
                'create_time'=>$this->current_time,
            ));

            switch($this->input->post('type', 'intval')){
                case ExamQuestionsTable::TYPE_SINGLE_ANSWER:
                case ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS:
                    //选择题
                    $selector_answers = $this->input->post('selector_answers');
                    $selector_right_answers = $this->input->post('selector_right_answers');
                    $i = 0;
                    foreach($selector_answers as $k=>$a){
                        ExamAnswersTable::model()->insert(array(
                            'question_id'=>$question_id,
                            'answer'=>$a,
                            'is_right_answer'=>in_array($k, $selector_right_answers) ? 1 : 0,
                            'sort'=>++$i,
                        ));
                    }
                break;
                case ExamQuestionsTable::TYPE_INPUT:
                    //填空题
                    ExamAnswersTable::model()->insert(array(
                        'question_id'=>$question_id,
                        'answer'=>$this->input->post('input_answer'),
                        'is_right_answer'=>1,
                        'sort'=>1,
                    ));
                break;
                case ExamQuestionsTable::TYPE_TRUE_OR_FALSE:
                    //判断题
                    $true_or_false_answer = $this->input->post('true_or_false_answer');
                    ExamAnswersTable::model()->insert(array(
                        'question_id'=>$question_id,
                        'answer'=>'正确',
                        'is_right_answer'=>$true_or_false_answer ? 1 : 0,
                        'sort'=>1,
                    ));
                    ExamAnswersTable::model()->insert(array(
                        'question_id'=>$question_id,
                        'answer'=>'错误',
                        'is_right_answer'=>$true_or_false_answer ? 0 : 1,
                        'sort'=>2,
                    ));
                break;
            }
            $this->actionlog(ActionlogsTable::TYPE_EXAM, '创建了一个试题', $question_id);
            
            Response::notify('success', '一个试题被添加', array(
                'fayexam/admin/question/edit', array(
                    'id'=>$question_id,
                )
            ));
        }
        
        //分类树
        $this->view->cats = CategoryService::service()->getTree('_system_exam_question');
        
        $this->view->render();
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑试题';
        $this->layout->_help = '_help_panel';
        
        $id = $this->input->get('id', 'intval');
        
        $this->form()->setModel(ExamQuestionsTable::model());
        
        if($this->input->post() && $this->form()->check()){
            $old_question = ExamQuestionsTable::model()->find($id, 'type');
            $new_question_type = $this->input->post('type', 'intval', $old_question['type']);
            ExamQuestionsTable::model()->update(array(
                'question'=>$this->input->post('question'),
                'cat_id'=>$this->input->post('cat_id', 'intval'),
                'score'=>$this->input->post('score', 'floatval'),
                'type'=>$new_question_type,
                'sort'=>$this->input->post('sort', 'intval', 100),
                'status'=>$this->input->post('status', 'intval'),
                'rand'=>$this->input->post('rand', 'intval', 0),
            ), $id);
            
            switch($new_question_type){
                //选择题
                case ExamQuestionsTable::TYPE_SINGLE_ANSWER:
                case ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS:
                    $selector_answers = $this->input->post('selector_answers');
                    $selector_right_answers = $this->input->post('selector_right_answers', 'intval', array());
                    
                    if($old_question['type'] != ExamQuestionsTable::TYPE_SINGLE_ANSWER &&
                        $old_question['type'] != ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS){
                        //原先不是选择题，直接清空原有答案
                        ExamAnswersTable::model()->delete(array(
                            'question_id = ?'=>$id,
                        ));
                        $selector_answers = $this->input->post('selector_answers');
                        $i = 0;
                        foreach($selector_answers as $k=>$a){
                            ExamAnswersTable::model()->insert(array(
                                'question_id'=>$id,
                                'answer'=>$a,
                                'is_right_answer'=>in_array($k, $selector_right_answers),
                                'sort'=>++$i,
                            ));
                        }
                    }else{
                        //本来就是选择题，对原有数据进行更新
                        $answer_ids = array();
                        $i = 0;
                        foreach($selector_answers as $k=>$a){
                            if(StringHelper::isInt($k)){
                                //老记录，更新
                                $answer_ids[] = $k;
                                //更新记录
                                ExamAnswersTable::model()->update(array(
                                    'answer'=>$a,
                                    'sort'=>++$i,
                                    'is_right_answer'=>in_array($k, $selector_right_answers) ? 1 : 0,
                                ), $k);
                            }else{
                                //新纪录，插入
                                $answer_ids[] = ExamAnswersTable::model()->insert(array(
                                    'question_id'=>$id,
                                    'answer'=>$a,
                                    'is_right_answer'=>in_array($k, $selector_right_answers) ? 1 : 0,
                                    'sort'=>++$i,
                                ));
                            }
                        }
                        //删除被删除了的答案
                        if($answer_ids){
                            //虽然没答案并不合常理，但这个不做强制检测
                            ExamAnswersTable::model()->delete(array(
                                'question_id = ?'=>$id,
                                'id NOT IN (?)'=>$answer_ids,
                            ));
                        }else{
                            ExamAnswersTable::model()->delete(array(
                                'question_id = ?'=>$id,
                            ));
                        }
                    }
                    break;
                case ExamQuestionsTable::TYPE_INPUT:
                    //填空题
                    if($old_question['type'] != ExamQuestionsTable::TYPE_INPUT){
                        //原先不是填空题，直接清空原有答案
                        ExamAnswersTable::model()->delete(array(
                            'question_id = ?'=>$id,
                        ));
                        ExamAnswersTable::model()->insert(array(
                            'question_id'=>$id,
                            'answer'=>$this->input->post('input_answer'),
                            'is_right_answer'=>1,
                            'sort'=>1,
                        ));
                    }else{
                        //更新答案
                        ExamAnswersTable::model()->update(array(
                            'answer'=>$this->input->post('input_answer'),
                            'is_right_answer'=>1,
                            'sort'=>1,
                        ), array(
                            'question_id = ?'=>$id,
                        ));
                    }
                    break;
                case ExamQuestionsTable::TYPE_TRUE_OR_FALSE:
                    //判断题
                    $true_or_false_answer = $this->input->post('true_or_false_answer');
                    if($old_question['type'] != ExamQuestionsTable::TYPE_TRUE_OR_FALSE){
                        //原先不是判断题，直接清空原有答案
                        ExamAnswersTable::model()->delete(array(
                            'question_id = ?'=>$id,
                        ));
                        ExamAnswersTable::model()->insert(array(
                            'question_id'=>$id,
                            'answer'=>'正确',
                            'is_right_answer'=>$true_or_false_answer ? 1 : 0,
                            'sort'=>1,
                        ));
                        ExamAnswersTable::model()->insert(array(
                            'question_id'=>$id,
                            'answer'=>'错误',
                            'is_right_answer'=>$true_or_false_answer ? 0 : 1,
                            'sort'=>2,
                        ));
                    }else{
                        //更新答案
                        ExamAnswersTable::model()->update(array(
                            'is_right_answer'=>$true_or_false_answer ? 1 : 0,
                        ), array(
                            'question_id = ?'=>$id,
                            'sort = 1',
                        ));
                        ExamAnswersTable::model()->update(array(
                            'is_right_answer'=>$true_or_false_answer ? 0 : 1,
                        ), array(
                            'question_id = ?'=>$id,
                            'sort = 2',
                        ));
                    }
                    break;
            }
            
            $this->actionlog(ActionlogsTable::TYPE_EXAM, '编辑了一个试题', $id);
            Response::notify('success', '编辑成功', false);
        }
        
        $question = ExamQuestionsTable::model()->find($id);
        $this->form()->setData($question);
        $this->view->question = $question;
        $this->view->answers = ExamAnswersTable::model()->fetchAll(array(
            'question_id = ?'=>$id,
        ), '*', 'sort');
        
        //分类树
        $this->view->cats = CategoryService::service()->getTree('_system_exam_question');
        
        //是否参与过考试
        $this->view->is_examed = !!ExamExamsQuestionsTable::model()->fetchRow('question_id = '.$id);
        if($this->view->is_examed){
            FlashService::set('已参与考试的试题不能改变试题类型且不可删除已被用户选过的选项', 'warning');
        }
        
        $this->view->render();
    }
    
    public function delete(){
        $id = $this->input->get('id', 'intval');
        ExamQuestionsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $id);
        $this->actionlog(ActionlogsTable::TYPE_EXAM, '一个试题被删除', $id);
        
        Response::notify('success', '一个试题被删除 - '.HtmlHelper::link('撤销', array('fayexam/admin/question/undelete', array(
            'id'=>$id,
        ))));
    }
    
    public function undelete(){
        $id = $this->input->get('id', 'intval');
        ExamQuestionsTable::model()->update(array(
            'delete_time'=>0,
        ), $id);
        $this->actionlog(ActionlogsTable::TYPE_EXAM, '一个试题被还原', $id);
        
        Response::notify('success', '一个试题被还原');
    }
    
    public function cat(){
        $this->layout->subtitle = '试题分类';
        $this->view->cats = CategoryService::service()->getTree('_system_exam_question');
        $root_node = CategoryService::service()->getByAlias('_system_exam_question', 'id');
        $this->view->root = $root_node['id'];
        
        if($this->checkPermission('fayexam/admin/question/cat-create')){
            $this->layout->sublink = array(
                'uri'=>'#create-cat-dialog',
                'text'=>'添加试题分类',
                'html_options'=>array(
                    'class'=>'create-cat-link',
                    'data-title'=>'试题分类',
                    'data-id'=>$root_node['id'],
                ),
            );
        }
        
        $this->view->render();
    }
    
    public function get(){
        $id = $this->input->get('id');
        
        if(StringHelper::isInt($id)){
            $question = ExamQuestionsTable::model()->find($id);
            
            Response::json(array(\F::filter('strip_tags', $question, 'question')));
        }else{
            $questions = ExamQuestionsTable::model()->fetchAll(array(
                'id IN (?)'=>$this->input->get('id', 'intval'),
            ), '*', 'sort, id DESC');
            
            foreach($questions as &$q){
                $q['question'] = strip_tags($q['question'], '<u>');
            }
            
            Response::json($questions);
        }
    }
    
    public function getAll(){
        $sql = new Sql();
        
        $sql->from(array('q'=>'exam_questions'))
            ->joinLeft(array('c'=>'categories'), 'q.cat_id = c.id', 'title AS cat_title')
            ->where(array(
                'q.status = '.ExamQuestionsTable::STATUS_ENABLED,
                'q.delete_time = 0',
            ))
            ->order('q.sort, q.id DESC')
        ;
            
        if($this->input->get('keywords')){
            $sql->where(array(
                'question LIKE ?'=>'%'.$this->input->get('keywords', 'addslashes').'%',
            ));
        }
        
        if($this->input->get('cat_id')){
            $sql->where(array(
                'cat_id = ?'=>$this->input->get('cat_id', 'intval'),
            ));
        }
        
        if($this->input->get('type')){
            $sql->where(array(
                'type = ?'=>$this->input->get('type', 'intval'),
            ));
        }
        
        if($this->input->get('start_time')){
            $sql->where(array(
                'q.create_time > ?'=>$this->input->get('start_time', 'strtotime'),
            ));
        }
        if($this->input->get('end_time')){
            $sql->where(array(
                'q.create_time < ?'=>$this->input->get('end_time', 'strtotime'),
            ));
        }
        
        if($this->input->get('selected')){
            $sql->where(array(
                'q.id NOT IN (?)'=>$this->input->get('selected', 'intval'),
            ));
        }
        
        $listview = new ListView($sql, array(
            'page_size'=>10,
        ));
        
        $data = $listview->getData();
        foreach($data as &$d){
            $d['question'] = strip_tags($d['question'], '<u>');
        }
        
        echo Response::json(array(
            'questions'=>$data,
            'pager'=>$listview->getPager(),
        ));
    }
    
    public function batch(){
        $ids = $this->input->post('ids', 'intval');
        $action = $this->input->post('batch_action');
        
        switch($action){
            case 'set-enabled':
                if(!$this->checkPermission('fayexam/admin/question/edit')){
                    Response::notify('error', array(
                        'message'=>'权限不允许',
                        'error_code'=>'permission-denied',
                    ));
                }
                
                $affected_rows = ExamQuestionsTable::model()->update(array(
                    'status'=>ExamQuestionsTable::STATUS_ENABLED,
                ), array(
                    'id IN (?)'=>$ids,
                ));
                Response::notify('success', $affected_rows.'条记录被启用');
            break;
            case 'set-disabled':
                if(!$this->checkPermission('fayexam/admin/question/edit')){
                    Response::notify('error', array(
                        'message'=>'权限不允许',
                        'error_code'=>'permission-denied',
                    ));
                }
                    
                $affected_rows = ExamQuestionsTable::model()->update(array(
                    'status'=>ExamQuestionsTable::STATUS_DISABLED,
                ), array(
                    'id IN (?)'=>$ids,
                ));
                Response::notify('success', $affected_rows.'条记录被禁用');
            break;
            case 'delete':
                if(!$this->checkPermission('fayexam/admin/question/edit')){
                    Response::notify('error', array(
                        'message'=>'权限不允许',
                        'error_code'=>'permission-denied',
                    ));
                }
                    
                $affected_rows = ExamQuestionsTable::model()->update(array(
                    'delete_time'=>\F::app()->current_time,
                ), array(
                    'id IN (?)'=>$ids,
                ));
                Response::notify('success', $affected_rows.'条记录被删除');
            break;
        }
    }
}