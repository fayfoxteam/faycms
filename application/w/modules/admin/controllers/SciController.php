<?php
namespace w\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use w\models\tables\Sci;
use fay\core\HttpException;
use fay\core\Validator;

class SciController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'sci';
	}
	
	public function index(){
		$this->layout->subtitle = 'sci信息';

        $sql = new Sql();
        $sql->from(array('s'=>'sci'));

//        $conditions = array();
//        if($this->input->get('name')){
//            $conditions = array('name like ?'=> '%'.$this->input->get('name').'%');
//        }
//
//        if($this->input->get('short_name')){
//            $conditions = array('short_name like ?'=> '%'.$this->input->get('short_name').'%');
//        }
//
//        if($this->input->get('research_dir')){
//            $conditions = array('research_dir like ?'=> '%'.$this->input->get('research_dir').'%');
//        }
//
//        if($conditions){
//            $sql->orWhere($conditions);
//        }


        if($this->input->get('name')){
            $sql->Where(array('name like ?'=> '%'.$this->input->get('name').'%'));
        }

        if($this->input->get('short_name')){
            $sql->Where(array('short_name like ?'=> '%'.$this->input->get('short_name').'%'));
        }

        if($this->input->get('research_dir')){
            $sql->Where(array('research_dir like ?'=> '%'.$this->input->get('research_dir').'%'));
        }

		$listview = new ListView($sql);
		$listview->page_size = 15;
		$this->view->listview = $listview;
		
		$this->form()->setModel(Sci::model());
		
		$this->view->render();
	}


    public function item(){
        $validator = new Validator();
        $check = $validator->check(array(
            array(array('id'), 'required'),
        ));

        if($check === true){
            $data = Sci::model()->fetchRow(array(
                'id = ?'=>$this->input->get('id'),
            ));
            if($data){
                $this->view->data = $data;
                $this->view->render();
            }else{
                throw new HttpException('id不存在');
            }
        }else{
            throw new HttpException('参数异常', 500);
        }
    }
	
//	public function create(){
//		if($this->input->post()){
//			if($this->form()->setModel(Bills::model())->check()){
//				$user_id = $this->input->post('user_id', 'intval');
//				$amount = $this->input->post('amount', 'floatval');
//				$type = $this->input->post('type', 'intval');
//
//				if($user = Bills::model()->fetchRow(array(
//					'user_id = ?'=>$user_id,
//				), 'balance', 'create_time DESC')){
//					//获取用户最后一条记录余额
//					$balance = $user['balance'];
//				}else{
//					//若无记录，则余额为0
//					$balance = 0;
//				}
//
//				if($type == Bills::TYPE_IN){
//					$balance += $amount;
//				}else if($type == Bills::TYPE_OUT){
//					$balance -= $amount;
//				}
//
//				Bills::model()->insert(array(
//					'user_id'=>$user_id,
//					'amount'=>$amount,
//					'balance'=>$balance,
//					'cat_id'=>$this->input->post('cat_id', 'intval'),
//					'type'=>$type,
//					'description'=>$this->input->post('description'),
//					'note'=>$this->input->post('note', null, ''),
//					'create_time'=>$this->current_time,
//				));
//				Response::notify('success', '账单记录添加成功');
//			}else{
//				Response::notify('error', $this->showDataCheckError($this->form()->getErrors(), true));
//			}
//
//		}else{
//			throw new HttpException('参数异常');
//		}
//	}
//
//	public function getCats(){
//		$type = $this->input->get('type', 'intval');
//		if($type == Bills::TYPE_IN){
//			$cat_alias = 'bill_in';
//		}else if($type == Bills::TYPE_OUT){
//			$cat_alias = 'bill_out';
//		}else{
//			echo json_encode(array(
//				'status'=>0,
//				'message'=>'参数异常',
//			));
//			die;
//		}
//
//		$cats = Category::model()->getTree($cat_alias);
//		echo json_encode(array(
//			'status'=>1,
//			'data'=>Html::getSelectOptions($cats, 'id', 'title')
//		));
//	}
//
//	public function cat(){
//		$this->layout->current_directory = 'bill';
//
//		$this->layout->subtitle = '记账分类';
//		$this->view->cats = Category::model()->getTree('_system_bill');
//		$root_node = Category::model()->getByAlias('_system_bill', 'id');
//		$this->view->root = $root_node['id'];
//
//		$this->view->render();
//	}
}