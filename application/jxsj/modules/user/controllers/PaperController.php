<?php
namespace jxsj\modules\user\controllers;

use jxsj\library\UserController;
use fay\models\Exam;
use fay\core\Response;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\ExamPapers;
use fay\helpers\String;
use fay\models\tables\ExamExams;
use fay\core\HttpException;

class PaperController extends UserController{
	public function index(){
		$sql = new Sql();
		$sql->from('exam_papers')
			->where(array(
				'start_time < '.$this->current_time,
				'deleted = 0',
				'status = '.ExamPapers::STATUS_ENABLED,
			))
			->orWhere(array(
				'end_time = 0',
				'end_time > '.$this->current_time,
			))
		;
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>10,
			'reload'=>$this->view->url('user/paper'),
		));
		
		$this->view->render();
	}
	
	public function item(){
		$id = $this->input->get('id', 'intval');
		if(!$id){
			throw new HttpException('页面不存在');
		}
		
		$this->view->paper = Exam::model()->getPaper($id);
		
		if(!$this->view->paper['repeatedly']){
			//不允许重复参考
			$exam = ExamExams::model()->fetchRow('paper_id = '.$this->view->paper['id']);
			if($exam){
				Response::output('error', array(
					'message'=>'该试卷只允许参考一次',
				), array('user/exam/item', array(
					'id'=>$exam['id'],
				)));
			}
		}
		
		$this->view->hash = String::random();
		$this->session->set('exam', array(
			$id=>array(
				'start_time'=>$this->current_time,
				'hash'=>$this->view->hash,
			),
		));
		
		$this->view->render();
	}
	
	public function create(){
		$paper_id = $this->input->post('paper_id', 'intval');
		$answers = $this->input->post('answers');
		
		$paper = ExamPapers::model()->find($paper_id);
		if(!$paper){
			Response::output('error', '试卷不存在');
		}else if($paper['start_time'] > $this->current_time){
			Response::output('error', '不在考试时间段');
		}
		
		$exam_session = $this->session->get('exam');
		if(empty($exam_session[$paper_id]['hash']) || $exam_session[$paper_id]['hash'] != $this->input->post('hash')){
			Response::output('error', '异常的请求');
		}
		
		$exam_id = Exam::model()->record($paper, $exam_session[$paper_id]['start_time'], $answers);
		
		$this->session->set('exam', array(
			$paper_id=>false,
		));
		
		Response::redirect('user/exam/item', array(
			'id'=>$exam_id,
		));
	}
	
}