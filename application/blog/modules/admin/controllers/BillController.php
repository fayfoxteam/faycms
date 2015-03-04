<?php
namespace blog\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\models\Category;
use fay\core\Sql;
use fay\common\ListView;
use blog\models\tables\Bills;
use fay\helpers\Html;
use fay\core\Response;
use fay\core\HttpException;

class BillController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'bill';
	}
	
	public function index(){
		$this->layout->subtitle = '记账';
		
		//成员列表
		$this->view->users = Users::model()->fetchAll(array(
			'role = '.Users::ROLE_BILL,
		), 'id,realname');
		
		$this->view->cats = Category::model()->getTree('bill_out');
		
		$sql = new Sql();
		$sql->from('blog_bills', 'b')
			->joinLeft('users', 'u', 'b.user_id = u.id', 'realname')
			->joinLeft('categories', 'c', 'b.cat_id = c.id', 'title AS cat_title')
			->order('id DESC');
		
		if($this->input->get('user_id')){
			$sql->where(array('user_id = ?'=>$this->input->get('user_id', 'intval')));
		}
		
		if($this->input->get('type')){
			$sql->where(array('type = ?'=>$this->input->get('type', 'intval')));
		}
		
		$listview = new ListView($sql);
		$listview->pageSize = 15;
		$this->view->listview = $listview;
		
		$this->form()->setModel(Bills::model());
		
		$this->view->render();
	}
	
	public function create(){
		if($this->input->post()){
			if($this->form()->setModel(Bills::model())->check()){
				$user_id = $this->input->post('user_id', 'intval');
				$amount = $this->input->post('amount', 'floatval');
				$type = $this->input->post('type', 'intval');
				
				if($user = Bills::model()->fetchRow(array(
					'user_id = ?'=>$user_id,
				), 'balance', 'create_time DESC')){
					//获取用户最后一条记录余额
					$balance = $user['balance'];
				}else{
					//若无记录，则余额为0
					$balance = 0;
				}
				
				if($type == Bills::TYPE_IN){
					$balance += $amount;
				}else if($type == Bills::TYPE_OUT){
					$balance -= $amount;
				}
				
				Bills::model()->insert(array(
					'user_id'=>$user_id,
					'amount'=>$amount,
					'balance'=>$balance,
					'cat_id'=>$this->input->post('cat_id', 'intval'),
					'type'=>$type,
					'description'=>$this->input->post('description'),
					'note'=>$this->input->post('note', null, ''),
					'create_time'=>$this->current_time,
				));
				Response::output('success', '账单记录添加成功');
			}else{
				Response::output('error', $this->showDataCheckError($this->form()->getErrors(), true));
			}
			
		}else{
			throw new HttpException('参数异常');
		}
	}
	
	public function getCats(){
		$type = $this->input->get('type', 'intval');
		if($type == Bills::TYPE_IN){
			$cat_alias = 'bill_in';
		}else if($type == Bills::TYPE_OUT){
			$cat_alias = 'bill_out';
		}else{
			echo json_encode(array(
				'status'=>0,
				'message'=>'参数异常',
			));
			die;
		}
		
		$cats = Category::model()->getTree($cat_alias);
		echo json_encode(array(
			'status'=>1,
			'data'=>Html::getSelectOptions($cats, 'id', 'title')
		));
	}
	
	public function cat(){
		$this->layout->current_directory = 'bill';

		$this->layout->subtitle = '记账分类';
		$this->view->cats = Category::model()->getTree('_system_bill');
		$root_node = Category::model()->getByAlias('_system_bill', 'id');
		$this->view->root = $root_node['id'];
		
		$this->view->render();
	}
}