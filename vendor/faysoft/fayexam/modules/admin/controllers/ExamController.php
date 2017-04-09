<?php
namespace fayexam\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fayexam\models\tables\ExamExamsTable;
use cms\models\tables\UsersTable;
use fayexam\models\tables\ExamPapersTable;
use fayexam\models\tables\ExamExamsQuestionsTable;
use fay\core\Response;
use cms\models\tables\ActionlogsTable;
use cms\services\ExamService;

class ExamController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'exam-paper';
    }
    
    public function index(){
        $this->layout->subtitle = '用户答卷';
        
        //页面设置
        $this->settingForm('admin_exam_exam_index', '_setting_index', array(
            'display_name'=>'username',
            'page_size'=>20,
        ));
        
        $sql = new Sql();
        $sql->from(array('e'=>'exam_exams'))
            ->joinLeft(array('p'=>'exam_papers'), 'e.paper_id = p.id', 'title AS paper_title')
            ->joinLeft(array('u'=>'users'), 'e.user_id = u.id', 'username,nickname,realname')
            ->order('id DESC')
        ;
        
        if($this->input->get('keywords')){
            $sql->where(array(
                "u.{$this->input->get('user', 'addslashes')} = ?"=>$this->input->get('keywords'),
            ));
        }
        
        if($this->input->get('start_time')){
            $sql->where(array(
                "e.{$this->input->get('field', 'addslashes')} >= ?"=>$this->input->get('start_time', 'strtotime'),
            ));
        }
        
        if($this->input->get('end_time')){
            $sql->where(array(
                "e.{$this->input->get('field', 'addslashes')} <= ?"=>$this->input->get('end_time', 'strtotime'),
            ));
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size'),
            'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
        ));
        
        $this->view->render();
    }
    
    public function item(){
        $this->layout->subtitle = '阅卷';
        
        $id = $this->input->get('id', 'intval');
        
        $exam = ExamExamsTable::model()->find($id);
        $this->view->exam = $exam;
        
        $this->view->paper = ExamPapersTable::model()->find($exam['paper_id']);
        
        $sql = new Sql();
        $this->view->exam_questions = $sql->from(array('ea'=>'exam_exams_questions'))
            ->joinLeft(array('q'=>'exam_questions'), 'ea.question_id = q.id', 'question,type')
            ->where(array(
                'ea.exam_id = ?'=>$id,
            ))
            ->fetchAll()
        ;
        $this->view->user = UsersTable::model()->find($exam['user_id'], 'username,nickname');
        
        $this->view->render();
    }
    
    public function setScore(){
        $id = $this->input->get('id', 'intval');
        $score = $this->input->get('score', 'floatval');

        //获取考试ID
        $exam_question = ExamExamsQuestionsTable::model()->find($id, 'id,exam_id,total_score');
        
        if($score > $exam_question['total_score']){
            Response::notify('error', array(
                'message'=>'所设得分不能高于总分',
            ));
        }
        
        ExamExamsQuestionsTable::model()->update(array(
            'score'=>$score,
        ), $id);
        //计算总分
        $exam_score = ExamExamsQuestionsTable::model()->fetchRow('exam_id = '.$exam_question['exam_id'], 'SUM(score) AS score');
        //更新总分
        ExamExamsTable::model()->update(array(
            'score'=>$exam_score['score'],
        ), $exam_question['exam_id']);

        $this->actionlog(ActionlogsTable::TYPE_EXAM, '编辑了一个答题的得分', $id);
        
        Response::notify('success', array(
            'message'=>'分数设置成功',
            'score'=>$score,
            'id'=>$id,
            'exam_total_score'=>$exam_score['score'],
        ));
    }
    
    public function remove(){
        $id = $this->input->get('id', 'intval');
        
        ExamService::service()->remove($id);
        
        $this->actionlog(ActionlogsTable::TYPE_EXAM, '将用户答卷永久删除', $id);
        
        Response::notify('success', array(
            'message'=>'一份答卷被永久删除',
            'id'=>$id,
        ));
    }
}