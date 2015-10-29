<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\ExamExams;
use fay\models\tables\Users;
use fay\models\tables\ExamPapers;
use fay\models\Setting;
use fay\models\tables\ExamExamsQuestions;
use fay\core\Response;
use fay\models\tables\Actionlogs;
use fay\models\Exam;

class ExamExamController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'exam-paper';
	}
	
	public function index(){
		$this->layout->subtitle = '用户答卷';
		
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_exam_exam_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'display_name'=>'username',
			'page_size'=>20,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from('exam_exams', 'e')
			->joinLeft('exam_papers', 'p', 'e.paper_id = p.id', 'title AS paper_title')
			->joinLeft('users', 'u', 'e.user_id = u.id', 'username,nickname,realname')
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
		
		$exam = ExamExams::model()->find($id);
		$this->view->exam = $exam;
		
		$this->view->paper = ExamPapers::model()->find($exam['paper_id']);
		
		$sql = new Sql();
		$this->view->exam_questions = $sql->from('exam_exams_questions', 'ea')
			->joinLeft('exam_questions', 'q', 'ea.question_id = q.id', 'question,type')
			->where(array(
				'ea.exam_id = ?'=>$id,
			))
			->fetchAll()
		;
		$this->view->user = Users::model()->find($exam['user_id'], 'username,nickname');
		
		$this->view->render();
	}
	
	public function setScore(){
		$id = $this->input->get('id', 'intval');
		$score = $this->input->get('score', 'floatval');

		//获取考试ID
		$exam_question = ExamExamsQuestions::model()->find($id, 'id,exam_id,total_score');
		
		if($score > $exam_question['total_score']){
			Response::notify('error', array(
				'message'=>'所设得分不能高于总分',
			));
		}
		
		ExamExamsQuestions::model()->update(array(
			'score'=>$score,
		), $id);
		//计算总分
		$exam_score = ExamExamsQuestions::model()->fetchRow('exam_id = '.$exam_question['exam_id'], 'SUM(score) AS score');
		//更新总分
		ExamExams::model()->update(array(
			'score'=>$exam_score['score'],
		), $exam_question['exam_id']);

		$this->actionlog(Actionlogs::TYPE_EXAM, '编辑了一个答题的得分', $id);
		
		Response::notify('success', array(
			'message'=>'分数设置成功',
			'score'=>$score,
			'id'=>$id,
			'exam_total_score'=>$exam_score['score'],
		));
	}
	
	public function remove(){
		$id = $this->input->get('id', 'intval');
		
		Exam::model()->remove($id);
		
		$this->actionlog(Actionlogs::TYPE_EXAM, '将用户答卷永久删除', $id);
		
		Response::notify('success', array(
			'message'=>'一份答卷被永久删除',
			'id'=>$id,
		));
	}
}