<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\VouchersTable;
use cms\services\CategoryService;
use cms\services\FlashService;
use fay\common\ListView;
use fay\core\Sql;
use fay\helpers\StringHelper;

class VoucherController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'voucher';
    }
    
    public function create(){
        $this->layout->subtitle = '添加优惠卷';
        
        $this->form()->setModel(VouchersTable::model());
        if($this->input->post() && $this->form()->check()){
            for($i = 0; $i < $this->input->post('num'); $i++){
                $data = VouchersTable::model()->fillData($this->input->post());
                
                //拼接优惠码
                $data['sn'] = $data['cat_id'] . StringHelper::random('numeric', 5);
                $data['create_time'] = $this->current_time;
                VouchersTable::model()->insert($data);
            }
            
            FlashService::set($this->input->post('num').'个优惠码被添加','success');
        }
        
        $this->view->cats = CategoryService::service()->getNextLevel('_system_voucher');
        
        $this->view->render();
    }
    
    public function index(){
        $this->layout->subtitle = '所有优惠卷';
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/voucher/create'),
            'text'=>'添加优惠卷',
        );
        
        $sql = new Sql();
        $sql->from(array('v'=>'vouchers'))
            ->where(array('v.delete_time = 0'))
            ->order('id DESC')
            ->joinLeft(array('c'=>'categories'), 'c.id = v.cat_id', 'title');
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

        $this->view->cats = CategoryService::service()->getNextLevel('_system_voucher');
        $this->view->render();
    }
    
    public function delete(){
        
    }
    
    public function remove(){
        
    }
}