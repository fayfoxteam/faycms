<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\ContactsTable;
use cms\services\SettingService;
use fay\common\ListView;
use fay\exceptions\NotFoundHttpException;
use fay\core\Loader;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

class ContactController extends AdminController{
    /**
     * box列表
     */
    public $boxes = array(
        array('name'=>'content', 'title'=>'留言内容'),
        array('name'=>'ip', 'title'=>'IP'),
        array('name'=>'user_info', 'title'=>'用户信息'),
        array('name'=>'publish_time', 'title'=>'发布时间'),
        array('name'=>'reply', 'title'=>'回复'),
    );
    
    /**
     * 默认box排序
     */
    public $default_box_sort = array(
        'side'=>array(
            'publish_time', 'ip', 'user_info',
        ),
        'normal'=>array(
            'content', 'reply',
        ),
    );
    
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'message';
    }
    
    public function index(){
        $this->layout->subtitle = '联系我们';
        
        //页面设置
        $_settings = $this->settingForm('admin_contact_index', '_setting_index', array(
            'cols'=>array('realname', 'email', 'phone', 'create_time', 'area'),
            'display_time'=>'short',
            'page_size'=>10,
        ));
        
        if(in_array('ip', $_settings['cols'])){
            //引入IP地址库
            Loader::vendor('IpLocation/IpLocation.class');
            $this->view->iplocation = new \IpLocation();
        }
        
        $sql = new Sql();
        $sql->from('contacts')
            ->order('id DESC');
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size', 10),
        ));
        
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        return $this->view->render();
    }
    
    public function setRead(){
        $id = $this->input->get('id', 'intval');
        ContactsTable::model()->update(array(
            'is_read'=>1,
        ), $id);
        
        $this->actionlog(ActionlogsTable::TYPE_CONTACT, '一条留言被标记为已读', $id);
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一条留言被标记为已读 - '.HtmlHelper::link('撤销', array('cms/admin/contact/set-unread', array(
                'id'=>$id,
            ))),
        ));
    }
    
    public function setUnread(){
        $id = $this->input->get('id', 'intval');
        ContactsTable::model()->update(array(
            'is_read'=>0,
        ), $id);
        
        $this->actionlog(ActionlogsTable::TYPE_CONTACT, '一条留言被标记为未读', $id);
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一条留言被标记为未读 - '.HtmlHelper::link('撤销', array('cms/admin/contact/set-read', array(
                'id'=>$id,
            ))),
        ));
    }
    
    public function remove(){
        $id = $this->input->get('id', 'intval');
        ContactsTable::model()->delete($id);
        
        $this->actionlog(ActionlogsTable::TYPE_CONTACT, '一条留言被永久删除', $id);
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一条留言被永久删除',
        ));
    }
    
    public function reply(){
        $id = $this->input->request('id', 'intval');
        $reply = $this->input->request('reply', 'trim');
        ContactsTable::model()->update(array(
            'reply'=>$reply,
            'is_read'=>1,
        ), $id);
        
        $contact = ContactsTable::model()->find($id, 'id,reply');
        $this->actionlog(ActionlogsTable::TYPE_CONTACT, '回复了一条留言', $id);
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'回复成功',
            'data'=>array(
                'id'=>$contact['id'],
                'reply'=>$contact['reply'],
            )
        ));
    }
    
    public function batch(){
        $action = $this->input->post('action');
        $ids = $this->input->post('ids', 'intval');
        
        if($action && $ids){
            if($action == 'read'){
                ContactsTable::model()->update(array(
                    'is_read'=>1,
                ), array(
                    'id IN (?)'=>$ids,
                ));
            }else if($action == 'unread'){
                ContactsTable::model()->update(array(
                    'is_read'=>0,
                ), array(
                    'id IN (?)'=>$ids,
                ));
            }else if($action == 'remove'){
                ContactsTable::model()->delete(array(
                    'id IN (?)'=>$ids,
                ));
            }
        }
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'',
        ));
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑留言';//页面标题
        
        //box排序
        $_box_sort_settings = SettingService::service()->get('admin_contact_box_sort');
        $_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
        $this->view->_box_sort_settings = $_box_sort_settings;
        
        //页面设置
        $_setting_key = 'admin_contact_boxes';
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_edit', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        $id = $this->input->get('id', 'intval');
        $this->form()->setModel(ContactsTable::model());
        if($this->input->post() && $this->form()->check()){
            $data = ContactsTable::model()->fillData($this->input->post());
            if(in_array('publish_time', $enabled_boxes)){
                if(empty($data['publish_time'])){
                    $data['publish_time'] = $this->current_time;
                }else{
                    $data['publish_time'] = strtotime($data['publish_time']);
                }
            }
            ContactsTable::model()->update($data, $id);
            $this->actionlog(ActionlogsTable::TYPE_CONTACT, '编辑了留言', $id);
            Response::notify(Response::NOTIFY_SUCCESS, '编辑成功', false);
        }
        
        //获取留言
        $contact = ContactsTable::model()->find($id);
        if(!$contact){
            throw new NotFoundHttpException('指定留言ID不存在');
        }
        
        //格式化数据
        $contact['publish_time'] = $contact['publish_time'] ? date('Y-m-d H:i:s', $contact['publish_time']) : '';
        $contact['create_time'] = DateHelper::format($contact['create_time']);
        $contact['ip_int'] = long2ip($contact['ip_int']);
        $contact['show_ip_int'] = long2ip($contact['show_ip_int']);
        $this->form()->setData($contact);
        
        return $this->view->render();
    }
}