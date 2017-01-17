<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\common\ListView;
use fay\core\Response;
use fay\core\Sql;
use fay\models\tables\ActionlogsTable;
use fay\models\tables\PaymentsTable;

class PaymentController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'third-part';
	}
	
	public function index(){
		$this->layout->subtitle = '支付方式';
		
		$this->layout->sublink = array(
			'uri'=>array('admin/link/create'),
			'text'=>'添加支付方式',
		);
		
		$sql = new Sql();
		$sql->from(array('p'=>'payments'));
		
		$listview = new ListView($sql, array(
			'empty_text'=>'<tr><td colspan="6" align="center">无相关记录！</td></tr>',
		));
		$this->view->listview = $listview;
		
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '添加支付方式';
		
		$this->form()->setModel(PaymentsTable::model());
		if($this->input->post() && $this->form()->check()){
			$data = $this->form()->getFilteredData();
			
			$data['create_time'] = $this->current_time;
			$data['last_modified_time'] = $this->current_time;
			$link_id = PaymentsTable::model()->insert($data);
			
			$this->actionlog(ActionlogsTable::TYPE_PAYMENT, '添加支付方式', $link_id);
			Response::notify('success', '支付方式添加成功');
		}
		
		$this->view->render();
	}
	
	public function edit(){
		
	}
	
	public function delete(){
		
	}
}