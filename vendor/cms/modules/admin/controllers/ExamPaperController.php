<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Actionlogs;
use fay\helpers\Html;
use fay\models\Category;
use fay\core\Response;
use fay\models\tables\ExamPapers;
use fay\models\tables\ExamPaperQuestions;
use fay\core\Sql;
use fay\common\ListView;

class ExamPaperController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'exam-paper';
	}
	
	public function index(){
		$this->layout->subtitle = '试卷列表';
		
		$this->layout->sublink = array(
			'uri'=>array('admin/exam-paper/create'),
			'text'=>'组卷',
		);
		
		$sql = new Sql();
		$sql->from(array('p'=>'exam_papers'))
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'deleted = 0',
			))
			->order('id DESC')
		;
		
		if($this->input->get('keywords')){
			$sql->where(array(
				'p.title LIKE ?'=>"%{$this->input->get('keywords')}%",
			));
		}
		
		if($this->input->get('cat_id')){
			$sql->where(array(
				'p.cat_id = ?'=>"%{$this->input->get('cat_id', 'intval')}%",
			));
		}
		
		if($this->input->get('start_time')){
			$sql->where(array(
				"p.create_time >= ?"=>$this->input->get('start_time', 'strtotime'),
			));
		}
		
		if($this->input->get('end_time')){
			$sql->where(array(
				"p.create_time <= ?"=>$this->input->get('end_time', 'strtotime'),
			));
		}
		
		$this->view->listview = new ListView($sql, array(
			'empty_text'=>'<tr><td colspan="6" align="center">无相关记录！</td></tr>',
		));

		//分类树
		$this->view->cats = Category::model()->getTree('_system_exam_paper');
		
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '组卷';
		
		$this->form()->setModel(ExamPapers::model())
			->setModel(ExamPaperQuestions::model());
		
		if($this->input->post()){
			if($this->form()->check()){
				$questions = $this->input->post('questions', 'intval');
				$score = $this->input->post('score', 'floatval', array(0));
				
				$paper_id = ExamPapers::model()->insert(array(
					'title'=>$this->input->post('title'),
					'description'=>$this->input->post('description'),
					'cat_id'=>$this->input->post('cat_id', 'intval', 0),
					'rand'=>$this->input->post('rand', 'intval', 0),
					'status'=>$this->input->post('status', 'intval', 1),
					'start_time'=>$this->input->post('start_time', 'strtotime', 0),
					'end_time'=>$this->input->post('end_time', 'strtotime', 0),
					'repeatedly'=>$this->input->post('repeatedly', 'intval', 0),
					'score'=>array_sum($score),
					'create_time'=>$this->current_time,
				));
				
				$i = 0;
				foreach($questions as $k => $q){
					$i++;
					ExamPaperQuestions::model()->insert(array(
						'paper_id'=>$paper_id,
						'question_id'=>$q,
						'score'=>$score[$k],
						'sort'=>$i,
					));
				}
				$this->actionlog(Actionlogs::TYPE_EXAM, '添加了一份试卷', $paper_id);
				
				Response::notify('success', '试卷发布成功', array(
					'admin/exam-paper/edit', array(
						'id'=>$paper_id,
					)
				));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}

		//分类树
		$this->view->cats = Category::model()->getTree('_system_exam_paper');
		$this->view->question_cats = Category::model()->getTree('_system_exam_question');
		
		$this->view->render('edit');
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑试卷';
		$this->layout->_help_panel = '_help';
		
		$id = $this->input->get('id', 'intval');
		
		$this->form()->setModel(ExamPapers::model())
			->setModel(ExamPaperQuestions::model());
		
		if($this->input->post()){
			if($this->form()->check()){
				$questions = $this->input->post('questions', 'intval');
				$score = $this->input->post('score', 'floatval', array(0));
				
				ExamPapers::model()->update(array(
					'title'=>$this->input->post('title'),
					'description'=>$this->input->post('description'),
					'cat_id'=>$this->input->post('cat_id', 'intval', 0),
					'rand'=>$this->input->post('rand', 'intval', 0),
					'status'=>$this->input->post('status', 'intval', 1),
					'start_time'=>$this->input->post('start_time', 'strtotime', 0),
					'end_time'=>$this->input->post('end_time', 'strtotime', 0),
					'repeatedly'=>$this->input->post('repeatedly', 'intval', 0),
					'score'=>array_sum($score),
					'last_modified_time'=>$this->current_time,
				), $id);
				
				//删除被删除的题目
				if($questions){
					ExamPaperQuestions::model()->delete(array(
						'paper_id = ?'=>$id,
						'question_id NOT IN (?)'=>$questions,
					));
				}else{
					ExamPaperQuestions::model()->delete(array(
						'paper_id = ?'=>$id,
					));
				}
	
				$i = 0;
				foreach($questions as $k => $q){
					$i++;
					if(ExamPaperQuestions::model()->find(array(
						'paper_id'=>$id,
						'question_id'=>$q,
					))){
						ExamPaperQuestions::model()->update(array(
							'score'=>$score[$k],
							'sort'=>$i,
						), array(
							'paper_id = ?'=>$id,
							'question_id = ?'=>$q,
						));
					}else{
						ExamPaperQuestions::model()->insert(array(
							'paper_id'=>$id,
							'question_id'=>$q,
							'score'=>$score[$k],
							'sort'=>$i,
						));
					}
				}
				
				$this->actionlog(Actionlogs::TYPE_EXAM, '编辑了一份试卷', $id);
				Response::notify('success', '编辑成功');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$paper = ExamPapers::model()->find($id);
		$paper['start_time'] ? $paper['start_time'] = date('Y-m-d H:i:s', $paper['start_time']) : $paper['start_time'] = '';
		$paper['end_time'] ? $paper['end_time'] = date('Y-m-d H:i:s', $paper['end_time']) : $paper['end_time'] = '';
		$this->form()->setData($paper);
		
		$sql = new Sql();
		$this->view->questions = $sql->from(array('pq'=>'exam_paper_questions'))
			->joinLeft(array('q'=>'exam_questions'), 'pq.question_id = q.id', 'question,type')
			->where('pq.paper_id = '.$paper['id'])
			->order('sort')
			->fetchAll()
		;
		
		//分类树
		$this->view->cats = Category::model()->getTree('_system_exam_paper');
		$this->view->question_cats = Category::model()->getTree('_system_exam_question');
		
		$this->view->render();
	}
	
	public function delete(){
		$id = $this->input->get('id', 'intval');
		ExamPapers::model()->update(array(
			'deleted'=>1,
		), $id);
		$this->actionlog(Actionlogs::TYPE_EXAM, '一份试卷被删除', $id);
		
		Response::notify('success', '一份试卷被删除 - '.Html::link('撤销', array('admin/exam-paper/undelete', array(
			'id'=>$id,
		))));
	}
	
	public function undelete(){
		$id = $this->input->get('id', 'intval');
		ExamPapers::model()->update(array(
			'deleted'=>0,
		), $id);
		$this->actionlog(Actionlogs::TYPE_EXAM, '一份试卷被还原', $id);
		
		Response::notify('success', '一份试卷被还原');
	}
	
	public function cat(){
		$this->layout->subtitle = '试卷分类';
		$this->view->cats = Category::model()->getTree('_system_exam_paper');
		$root_node = Category::model()->getByAlias('_system_exam_paper', 'id');
		$this->view->root = $root_node['id'];
		
		if($this->checkPermission('admin/exam-paper/cat-create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加试卷分类',
				'html_options'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'试卷分类',
					'data-id'=>$root_node['id'],
				),
			);
		}
		
		$this->view->render();
	}
	
}