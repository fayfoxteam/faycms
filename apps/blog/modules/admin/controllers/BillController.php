<?php
namespace blog\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\UsersTable;
use cms\services\CategoryService;
use fay\exceptions\ValidationException;
use fay\core\Sql;
use fay\common\ListView;
use blog\models\tables\Bills;
use fay\helpers\HtmlHelper;
use fay\core\Response;

class BillController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'bill';
    }
    
    public function index(){
        $this->layout->subtitle = '记账';
        
        //成员列表
        $this->view->users = UsersTable::model()->fetchAll(array(
            'role = '.UsersTable::ROLE_BILL,
        ), 'id,realname');
        
        $this->view->cats = CategoryService::service()->getTree('bill_out');
        
        $sql = new Sql();
        $sql->from(array('b'=>'blog_bills'))
            ->joinLeft(array('u'=>'users'), 'b.user_id = u.id', 'realname')
            ->joinLeft(array('c'=>'categories'), 'b.cat_id = c.id', 'title AS cat_title')
            ->order('id DESC');
        
        if($this->input->get('user_id')){
            $sql->where(array('user_id = ?'=>$this->input->get('user_id', 'intval')));
        }
        
        if($this->input->get('type')){
            $sql->where(array('type = ?'=>$this->input->get('type', 'intval')));
        }
        
        $listview = new ListView($sql);
        $listview->page_size = 15;
        $this->view->listview = $listview;
        
        $this->form()->setModel(BillsTable::model());
        
        return $this->view->render();
    }
    
    public function create(){
        if($this->input->post()){
            if($this->form()->setModel(BillsTable::model())->check()){
                $user_id = $this->input->post('user_id', 'intval');
                $amount = $this->input->post('amount', 'floatval');
                $type = $this->input->post('type', 'intval');
                
                if($user = BillsTable::model()->fetchRow(array(
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
                
                BillsTable::model()->insert(array(
                    'user_id'=>$user_id,
                    'amount'=>$amount,
                    'balance'=>$balance,
                    'cat_id'=>$this->input->post('cat_id', 'intval'),
                    'type'=>$type,
                    'description'=>$this->input->post('description'),
                    'note'=>$this->input->post('note', null, ''),
                    'create_time'=>$this->current_time,
                ));
                return Response::notify(Response::NOTIFY_SUCCESS, '账单记录添加成功');
            }else{
                return Response::notify(Response::NOTIFY_FAIL, $this->showDataCheckError($this->form()->getErrors(), true));
            }
            
        }else{
            throw new ValidationException('参数异常');
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
        
        $cats = CategoryService::service()->getTree($cat_alias);
        echo json_encode(array(
            'status'=>1,
            'data'=>HtmlHelper::getSelectOptions($cats, 'id', 'title')
        ));
    }
    
    public function cat(){
        $this->layout->current_directory = 'bill';

        $this->layout->subtitle = '记账分类';
        $this->view->cats = CategoryService::service()->getTree('_system_bill');
        $root_node = CategoryService::service()->get('_system_bill', 'id');
        $this->view->root = $root_node['id'];
        
        return $this->view->render();
    }
}