<?php
namespace faypay\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use fay\common\ListView;
use fay\core\exceptions\NotFoundHttpException;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\HtmlHelper;
use faypay\models\tables\PaymentsTable;

class MethodController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'third-part';
    }
    
    /**
     * 支付方式列表
     */
    public function index(){
        $this->layout->subtitle = '支付方式';
        
        $this->layout->sublink = array(
            'uri'=>array('faypay/admin/method/create'),
            'text'=>'添加支付方式',
        );
        
        $sql = new Sql();
        $sql->from(array('p'=>'payments'))
            ->where('delete_time = 0')
        ;
        
        $listview = new ListView($sql, array(
            'empty_text'=>'<tr><td colspan="6" align="center">无相关记录！</td></tr>',
        ));
        $this->view->listview = $listview;
        
        return $this->view->render();
    }
    
    /**
     * 添加支付方式
     */
    public function create(){
        $this->layout->subtitle = '添加支付方式';
        
        $this->layout->sublink = array(
            'uri'=>array('faypay/admin/method/index'),
            'text'=>'支付方式列表',
        );
        
        $this->form()->setModel(PaymentsTable::model())
            ->setScene('create')
        ;
        if($this->input->post() && $this->form()->check()){
            $data = $this->form()->getFilteredData();
            
            $data['create_time'] = $this->current_time;
            $data['update_time'] = $this->current_time;
            $data['config'] = json_encode($data['config']);
            $payment_method_id = PaymentsTable::model()->insert($data);
            
            $this->actionlog(ActionlogsTable::TYPE_PAYMENT, '添加支付方式', $payment_method_id);
            Response::notify('success', '支付方式添加成功', array(
                'faypay/admin/method/edit', array('id'=>$payment_method_id)
            ));
        }
        
        return $this->view->render();
    }
    
    /**
     * 编辑支付方式
     */
    public function edit(){
        $this->layout->subtitle = '编辑支付方式';
        
        $this->layout->sublink = array(
            'uri'=>array('faypay/admin/method/create'),
            'text'=>'添加支付方式',
        );
        
        $this->form()->setModel(PaymentsTable::model())
            ->setScene('edit')
        ;
        $id = $this->input->get('id', 'intval');
        
        if($this->input->post()){
            if($this->form()->check()){
                $data = $this->form()->getFilteredData();
                
                $data['update_time'] = $this->current_time;
                $data['config'] = json_encode($data['config']);
                PaymentsTable::model()->update($data, $id);
                
                $this->actionlog(ActionlogsTable::TYPE_LINK, '编辑支付方式', $id);
                Response::notify('success', '一个支付方式被编辑', false);
            }
        }
        $payment = PaymentsTable::model()->find($id);
        
        if(!$payment){
            throw new NotFoundHttpException('无效的支付方式ID');
        }
        
        $this->view->payment = $payment;
        $this->form()->setData($payment);
        
        //设置支付方式配置参数（name带config前缀）
        $payment_config = \json_decode($payment['config'], true);
        foreach($payment_config as $k => $c){
            $this->form('payment')->setData(array(
                "config[{$k}]"=>$c
            ));
        }
        
        return $this->view->render();
    }
    
    /**
     * 删除支付方式（软删除）
     */
    public function delete(){
        $payment = PaymentsTable::model()->find($this->input->get('id', 'intval'), 'id');
        
        if(!$payment){
            throw new NotFoundHttpException('无效的支付方式ID');
        }
        
        PaymentsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $payment['id']);
        
        Response::notify('success', array(
            'message'=>'一个支付方式被移入回收站 - '.HtmlHelper::link('撤销', array('faypay/admin/method/undelete', array(
                    'id'=>$payment['id'],
                ))),
            'id'=>$payment['id'],
        ));
    }
    
    /**
     * 还原支付方式
     */
    public function undelete(){
        $payment = PaymentsTable::model()->find($this->input->get('id', 'intval'), 'id');
        
        if(!$payment){
            throw new NotFoundHttpException('无效的支付方式ID');
        }
        
        PaymentsTable::model()->update(array(
            'delete_time'=>0,
        ), $payment['id']);
        
        Response::notify('success', '一个支付方式被还原');
    }
    
    /**
     * 根据传过来的支付方式，返回支付方式配置界面
     * @parameter string $code 支付方式:支付类型 的格式，例如：weixin:jsapi
     */
    public function getSettingPanel(){
        $code = $this->input->get('code');
        
        list($channel, $type) = explode(':', $code);
        
        $setting_file = FAYSOFT_PATH . "faypay/services/methods/{$channel}/views/_setting_{$type}.php";
        if(file_exists($setting_file)){
            include $setting_file;
        }else{
            throw new \ErrorException('支付方式配置面板文件丢失');
        }
    }
}