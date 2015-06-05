<?php
namespace jxsj\modules\user\controllers;

use jxsj\library\UserController;
use fay\models\tables\ExamExams;
use fay\models\tables\ExamPapers;
use fay\core\Sql;
use fay\common\ListView;

class ExamController extends UserController{
	public function index(){
		$sql = new Sql();
		$sql->from('exam_exams', 'e')
			->joinLeft('exam_papers', 'p', 'e.paper_id = p.id', 'title AS paper_title')
			->order('id DESC')
			->where('e.user_id = '.$this->current_user)
		;
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>15,
			'reload'=>$this->view->url('user/exam'),
		));
		
		$this->view->render();
	}
	
	public function item(){
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
		
		$this->view->render();
	}
}