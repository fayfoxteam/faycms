<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Vouchers;
use fay\helpers\String;
use fay\models\Category;
use fay\core\Sql;
use fay\common\ListView;

class VoucherController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'voucher';
	}
	
	public function create(){
		$this->layout->subtitle = '添加优惠卷';
		
		$this->form()->setModel(Vouchers::model());
		if($this->input->post()){
			if($this->form()->check()){
				for($i = 0; $i < $this->input->post('num'); $i++){
					$data = Vouchers::model()->setAttributes($this->input->post());
		
					//拼接优惠码
					$data['sn'] = $data['cat_id'] . String::random('numeric', 5);
					$data['create_time'] = $this->current_time;
					Vouchers::model()->insert($data);
				}
		
				$this->flash->set($this->input->post('num').'个优惠码被添加','success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->cats = Category::model()->getNextLevel('_system_voucher');
		
		$this->view->render();
	}
	
	public function index(){
		$this->layout->subtitle = '所有优惠卷';
		$this->layout->sublink = array(
			'uri'=>array('admin/voucher/create'),
			'text'=>'添加优惠卷',
		);
		
		$sql = new Sql();
		$sql->from('vouchers', 'v')
			->where(array('v.deleted = 0'))
			->order('id DESC')
			->joinLeft('categories', 'c', 'c.id = v.cat_id', 'title');
		if($this->input->get('sn')){
			$sql->where(array('v.sn LIKE ?'=>$this->input->get('sn').'%'));
		}
		if($this->input->get('type')){
			$sql->where(array('v.type = ?'=>$this->input->get('type', 'intval')));
		}
		if($this->input->get('cat_id')){
			$sql->where(array('v.cat_id = ?'=>$this->input->get('cat_id', 'intval')));
		}
		
		$this->view->listview = new ListView($sql);

		$this->view->cats = Category::model()->getNextLevel('_system_voucher');
		$this->view->render();
	}
	
	public function delete(){
		
	}
	
	public function remove(){
		
	}
}