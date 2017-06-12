<?php
namespace cms\services;

use fay\core\ErrorException;
use fay\core\Loader;
use fay\helpers\FieldsHelper;
use fay\models\MultiTreeModel;
use cms\models\tables\MessagesTable;
use fay\core\Exception;
use fay\helpers\ArrayHelper;
use fay\helpers\RequestHelper;
use cms\models\tables\UserCounterTable;
use cms\services\user\UserService;

/**
 * 留言服务
 */
class MessageService extends MultiTreeModel{
    /**
     * 评论创建后事件
     */
    const EVENT_CREATED = 'after_user_message_created';
    
    /**
     * 评论被删除后事件
     */
    const EVENT_DELETED = 'after_user_message_deleted';
    
    /**
     * 评论被还原后事件
     */
    const EVENT_UNDELETE = 'after_user_message_undelete';
    
    /**
     * 评论被永久删除事件
     */
    const EVENT_REMOVING = 'before_user_message_removed';
    
    /**
     * 评论通过审核事件
     */
    const EVENT_APPROVED = 'after_user_message_approved';
    
    /**
     * 评论未通过审核事件
     */
    const EVENT_DISAPPROVED = 'after_user_message_disapproved';
    
    /**
     * @see MultiTreeModel::$model
     */
    protected $model = 'cms\models\tables\MessagesTable';
    
    /**
     * @see MultiTreeModel::$foreign_key
     */
    protected $foreign_key = 'to_user_id';
    
    /**
     * @see MultiTreeModel::$field_key
     */
    protected $field_key = 'message';
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 发表一条用户留言
     * @param int $to_user_id 用户ID
     * @param string $content 评论内容
     * @param int $parent 父ID，若是回复评论的评论，则带上被评论的评论ID，默认为0
     * @param int $status 状态（默认为待审核）
     * @param array $extra 扩展参数，二次开发时可能会用到
     * @param int $user_id 用户ID，若不指定，默认为当前登录用户ID
     * @param int $sockpuppet 马甲信息，若是真实用户，传入0，默认为0
     * @return int 消息ID
     * @throws Exception
     */
    public function create($to_user_id, $content, $parent = 0, $status = MessagesTable::STATUS_PENDING, $extra = array(), $user_id = null, $sockpuppet = 0){
        $user_id === null && $user_id = \F::app()->current_user;
        
        if(!UserService::isUserIdExist($to_user_id)){
            throw new Exception('用户ID不存在', 'to_user_id-not-exist');
        }
        
        if($parent){
            $parent_message = MessagesTable::model()->find($parent, 'to_user_id,delete_time');
            if(!$parent_message || $parent_message['delete_time']){
                throw new Exception('父节点不存在', 'parent-not-exist');
            }
        }
        
        $message_id = $this->_create(array_merge($extra, array(
            'to_user_id'=>$to_user_id,
            'content'=>$content,
            'status'=>$status,
            'user_id'=>$user_id,
            'sockpuppet'=>$sockpuppet,
            'create_time'=>\F::app()->current_time,
            'update_time'=>\F::app()->current_time,
            'ip_int'=>RequestHelper::ip2int(\F::app()->ip),
        )), $parent);
        
        //更新用户留言数
        $this->updateMessages(array(array(
            'to_user_id'=>$to_user_id,
            'status'=>$status,
            'sockpuppet'=>$sockpuppet,
        )), 'create');
        
        //触发事件
        \F::event()->trigger(self::EVENT_CREATED, $message_id);
        
        return $message_id;
    }
    
    /**
     * 软删除一条评论
     * 软删除不会修改parent标识，因为删除的东西随时都有可能会被恢复，而parent如果变了是无法被恢复的。
     * @param int $message_id 评论ID
     * @throws Exception
     */
    public function delete($message_id){
        $message = MessagesTable::model()->find($message_id, 'delete_time,to_user_id,status,sockpuppet');
        if(!$message){
            throw new Exception('指定评论ID不存在', 'message_id-is-not-exist');
        }
        if($message['delete_time']){
            throw new Exception('评论已删除', 'message-already-deleted');
        }
        
        //软删除不需要动树结构，只要把deleted字段标记一下即可
        MessagesTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
            'update_time'=>\F::app()->current_time,
        ), $message_id);
        
        //更新用户留言数
        $this->updateMessages(array($message), 'delete');
        
        //触发事件
        \F::event()->trigger(self::EVENT_DELETED, array($message_id));
    }
    
    /**
     * 批量删除
     * @param array $message_ids 由评论ID构成的一维数组
     * @return int|null
     */
    public function batchDelete($message_ids){
        $messages = MessagesTable::model()->fetchAll(array(
            'id IN (?)'=>$message_ids,
            'delete_time = 0',
        ), 'id,to_user_id,sockpuppet,status');
        if(!$messages){
            //无符合条件的记录
            return 0;
        }
        
        $affected_message_ids = ArrayHelper::column($messages, 'id');
        
        //更新状态
        $affected_rows = MessagesTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
            'update_time'=>\F::app()->current_time,
        ), array(
            'id IN (?)'=>$affected_message_ids,
        ));
        
        //更新用户留言数
        $this->updateMessages($messages, 'delete');
        
        \F::event()->trigger(self::EVENT_DELETED, $affected_message_ids);
        
        return $affected_rows;
    }
    
    /**
     * 从回收站恢复一条评论
     * @param int $message_id 评论ID
     * @throws Exception
     */
    public function undelete($message_id){
        $message = MessagesTable::model()->find($message_id, 'delete_time,to_user_id,status,sockpuppet');
        if(!$message){
            throw new Exception('指定评论ID不存在', 'message_id-is-not-exist');
        }
        if(!$message['delete_time']){
            throw new Exception('指定评论ID不在回收站中', 'message-not-in-recycle-bin');
        }
        
        //还原不需要动树结构，只是把deleted字段标记一下即可
        MessagesTable::model()->update(array(
            'delete_time'=>0,
            'update_time'=>\F::app()->current_time,
        ), $message_id);
        
        //更新用户留言数
        $this->updateMessages(array($message), 'undelete');
        
        //触发事件
        \F::event()->trigger(self::EVENT_UNDELETE, array($message_id));
    }
    
    /**
     * 批量还原
     * @param array $message_ids 由评论ID构成的一维数组
     * @return int|null
     */
    public function batchUnelete($message_ids){
        $messages = MessagesTable::model()->fetchAll(array(
            'id IN (?)'=>$message_ids,
            'delete_time > 0',
        ), 'id,to_user_id,sockpuppet,status');
        if(!$messages){
            //无符合条件的记录
            return 0;
        }
        
        $affected_message_ids = ArrayHelper::column($messages, 'id');
        
        //更新状态
        $affected_rows = MessagesTable::model()->update(array(
            'delete_time'=>0,
            'update_time'=>\F::app()->current_time,
        ), array(
            'id IN (?)'=>$affected_message_ids,
        ));
        
        //更新用户留言数
        $this->updateMessages($messages, 'undelete');
        
        \F::event()->trigger(self::EVENT_UNDELETE, $affected_message_ids);
        
        return $affected_rows;
    }
    
    /**
     * 删除一条评论及所有回复该评论的评论
     * @param int $message_id 评论ID
     * @return array
     * @throws Exception
     */
    public function deleteAll($message_id){
        $message = MessagesTable::model()->find($message_id, 'left_value,right_value,root');
        if(!$message){
            throw new Exception('指定评论ID不存在');
        }
        
        //获取所有待删除节点
        $messages = MessagesTable::model()->fetchAll(array(
            'root = ?'=>$message['root'],
            'left_value >= ' . $message['left_value'],
            'right_value <= ' . $message['right_value'],
            'delete_time = 0',
        ), 'id,to_user_id,status,sockpuppet');
        
        if($messages){
            //如果存在待删除节点，则执行删除
            $message_ids = ArrayHelper::column($messages, 'id');
            MessagesTable::model()->update(array(
                'delete_time'=>\F::app()->current_time,
                'update_time'=>\F::app()->current_time,
            ), array(
                'id IN (?)'=>$message_ids,
            ));
            
            //更新用户留言数
            $this->updateMessages($messages, 'delete');
            
            //触发事件
            \F::event()->trigger(self::EVENT_DELETED, $message_ids);
            
            return $message_ids;
        }else{
            return array();
        }
    }
    
    /**
     * 永久删除一条评论
     * @param int $message_id 评论ID
     * @return bool
     * @throws Exception
     */
    public function remove($message_id){
        $message = MessagesTable::model()->find($message_id, '!content');
        if(!$message){
            throw new Exception('指定评论ID不存在');
        }
        
        //触发事件，这个不能用after，记录都没了就没法找了
        \F::event()->trigger(self::EVENT_REMOVING, array($message_id));
        
        $this->_remove($message);
        
        if(!$message['delete_time']){
            //更新用户留言数
            $this->updateMessages(array($message), 'remove');
        }
        
        return true;
    }
    
    /**
     * 物理删除一条评论及所有回复该评论的评论
     * @param int $message_id 评论ID
     * @return array
     * @throws Exception
     */
    public function removeAll($message_id){
        $message = MessagesTable::model()->find($message_id, '!content');
        if(!$message){
            throw new Exception('指定评论ID不存在');
        }
        
        //获取所有待删除节点
        $messages = MessagesTable::model()->fetchAll(array(
            'root = ?'=>$message['root'],
            'left_value >= ' . $message['left_value'],
            'right_value <= ' . $message['right_value'],
        ), 'id,to_user_id,status,sockpuppet');
        $message_ids = ArrayHelper::column($messages, 'id');
        
        //触发事件
        \F::event()->trigger(self::EVENT_REMOVING, $message_ids);
        
        //获取所有不在回收站内的节点（已删除的显然不需要再更新评论数了）
        $undeleted_messages = array();
        foreach($message as $c){
            if(!$c['delete_time']){
                $undeleted_messages[] = $c;
            }
        }
        //更新用户留言数
        $this->updateMessages($undeleted_messages, 'remove');
        
        //执行删除
        $this->_removeAll($message);
        
        return $message_ids;
    }
    
    /**
     * 通过审核
     * @param int $message_id 评论ID
     * @return bool
     * @throws Exception
     */
    public function approve($message_id){
        $message = MessagesTable::model()->find($message_id, '!content');
        if(!$message){
            throw new Exception('指定评论ID不存在', 'message_id-is-not-exist');
        }
        if($message['delete_time']){
            throw new Exception('评论已删除', 'message-deleted');
        }
        if($message['status'] == MessagesTable::STATUS_APPROVED){
            throw new Exception('已通过审核，请勿重复操作', 'already-approved');
        }
        
        $this->setStatus($message_id, MessagesTable::STATUS_APPROVED);
        
        //更新用户留言数
        $this->updateMessages(array($message), 'approve');
        
        //触发事件
        \F::event()->trigger(self::EVENT_APPROVED, array($message_id));
        return true;
    }
    
    /**
     * 批量通过审核
     * @param array $message_ids 由评论ID构成的一维数组
     * @return int
     */
    public function batchApprove($message_ids){
        $messages = MessagesTable::model()->fetchAll(array(
            'id IN (?)'=>$message_ids,
            'status != ' . MessagesTable::STATUS_APPROVED,
        ), 'id,to_user_id,sockpuppet,status');
        if(!$messages){
            //无符合条件的记录
            return 0;
        }
        
        $affected_message_ids = ArrayHelper::column($messages, 'id');
        
        //更新状态
        $affected_rows = $this->setStatus($affected_message_ids, MessagesTable::STATUS_APPROVED);
        
        //更新用户留言数
        $this->updateMessages($messages, 'approve');
        
        //触发事件
        \F::event()->trigger(self::EVENT_APPROVED, $affected_message_ids);
        
        return $affected_rows;
    }
    
    /**
     * 不通过审核
     * @param int $message_id 评论ID
     * @return bool
     * @throws Exception
     */
    public function disapprove($message_id){
        $message = MessagesTable::model()->find($message_id, '!content');
        if(!$message){
            throw new Exception('指定评论ID不存在', 'message_id-is-not-exist');
        }
        if($message['delete_time']){
            throw new Exception('评论已删除', 'message-is-deleted');
        }
        if($message['status'] == MessagesTable::STATUS_UNAPPROVED){
            throw new Exception('该评论已是“未通过审核”状态，请勿重复操作', 'already-unapproved');
        }
        
        $this->setStatus($message_id, MessagesTable::STATUS_UNAPPROVED);
        
        //更新用户留言数
        $this->updateMessages(array($message), 'disapprove');
        
        //触发事件
        \F::event()->trigger(self::EVENT_DISAPPROVED, array($message_id));
        return true;
    }
    
    /**
     * 批量不通过审核
     * @param array $message_ids 由评论ID构成的一维数组
     * @return int
     */
    public function batchDisapprove($message_ids){
        $messages = MessagesTable::model()->fetchAll(array(
            'id IN (?)'=>$message_ids,
            'status != ' . MessagesTable::STATUS_UNAPPROVED,
        ), 'id,to_user_id,sockpuppet,status');
        if(!$messages){
            //无符合条件的记录
            return 0;
        }
        
        $affected_message_ids = ArrayHelper::column($messages, 'id');
        
        //更新状态
        $affected_rows = $this->setStatus($affected_message_ids, MessagesTable::STATUS_UNAPPROVED);
        
        //更新用户留言数
        $this->updateMessages($messages, 'disapprove');
        
        \F::event()->trigger(self::EVENT_DISAPPROVED, $affected_message_ids);
        
        return $affected_rows;
    }
    
    /**
     * 编辑一条评论（只能编辑评论内容部分）
     * @param int $message_id 评论ID
     * @param string $content 评论内容
     * @return int|null
     */
    public function update($message_id, $content){
        return MessagesTable::model()->update(array(
            'content'=>$content,
        ), $message_id);
    }
    
    /**
     * 获取一条留言
     * @param int $message_id 留言ID
     * @param array|string $fields 返回字段
     *  - message.*系列可指定messages表返回字段，若有一项为'message.*'，则返回所有字段
     *  - user.*系列可指定作者信息，格式参照\cms\services\user\UserService::get()
     *  - to_user.*系列可指定被留言用户信息，格式参照\cms\services\user\UserService::get()
     *  - parent.message.*系列可指定父留言messages表返回字段，若有一项为'message.*'，则返回所有字段
     *  - parent.user.*系列可指定父留言作者信息，格式参照\cms\services\user\UserService::get()
     * @return array|false
     */
    public function get($message_id, $fields = array(
        'message'=>array(
            'id', 'content', 'parent', 'create_time',
        ),
        'user'=>array(
            'id', 'nickname', 'avatar',
        ),
        'to_user'=>array(
            'id', 'nickname',
        ),
        'parent'=>array(
            'message'=>array(
                'id', 'content', 'parent', 'create_time',
            ),
            'user'=>array(
                'id', 'nickname', 'avatar',
            ),
        )
    )){
        $fields = new FieldsHelper($fields, 'message');
        if(!$fields->getFields() || $fields->hasField('*')){
            //若未指定返回字段，初始化
            $fields->setFields(\F::table($this->model)->getFields(array('status', 'delete_time', 'sockpuppet')));
        }
        
        $message_fields = $fields->getFields();
        if($fields->user && !in_array('user_id', $message_fields)){
            //如果要获取作者信息，则必须搜出user_id
            $message_fields[] = 'user_id';
        }
        if($fields->to_user && !in_array('to_user_id', $message_fields)){
            //如果要获取被留言用户信息，则必须搜出to_user_id
            $message_fields[] = 'to_user_id';
        }
        if($fields->parent && !in_array('parent', $message_fields)){
            //如果要获取作者信息，则必须搜出parent
            $message_fields[] = 'parent';
        }
        
        $message = \F::table($this->model)->fetchRow(array(
            'id = ?'=>$message_id,
            'delete_time = 0',
        ), $message_fields);
        
        if(!$message){
            return false;
        }
        
        $return = array(
            'message'=>$message,
        );
        
        //作者信息
        if($fields->user){
            $return['user'] = UserService::service()->get($message['user_id'], $fields->user);
        }
        
        //被回复用户信息
        if($fields->to_user){
            $return['to_user'] = UserService::service()->get($message['to_user_id'], $fields->to_user);
        }
        
        //父节点
        if($fields->parent){
            $parent_message_fields = $fields->parent->message ? $fields->parent->message->getFields() : array();
            if($fields->parent->user && !in_array('user_id', $parent_message_fields)){
                //如果要获取作者信息，则必须搜出user_id
                $parent_message_fields[] = 'user_id';
            }
            
            $parent_message = \F::table($this->model)->fetchRow(array(
                'id = ?'=>$message['parent'],
                'delete_time = 0',
            ), $parent_message_fields);
            
            if($parent_message){
                //有父节点
                $return['parent']['message'] = $parent_message;
                if($fields->parent->user){
                    $return['parent']['user'] = UserService::service()->get($parent_message['user_id'], $fields->parent->user);
                }
                if(!$fields->parent->message->hasField('user_id') && in_array('user_id', $parent_message_fields)){
                    unset($return['parent']['message']['user_id']);
                }
            }else{
                //没有父节点，但是要求返回相关父节点字段，则返回空数组
                $return['parent']['message'] = array();
                
                if($fields->parent->user){
                    $return['parent']['user'] = array();
                }
            }
        }
        
        //过滤掉那些未指定返回，但出于某些原因先搜出来的字段
        foreach(array('user_id', 'parent', 'to_user_id') as $f){
            if($fields->hasField($f, true) && in_array($f, $message_fields)){
                unset($return['message'][$f]);
            }
        }
        
        return $return;
    }
    
    /**
     * 判断用户是否对该留言有删除权限
     * @param int $message 留言
     *  - 若是数组，视为留言表行记录，必须包含user_id
     *  - 若是数字，视为留言ID，会根据ID搜索数据库
     * @param string $action 操作
     * @param int $user_id 用户ID，若为空，则默认为当前登录用户
     * @return bool
     * @throws ErrorException
     */
    public function checkPermission($message, $action = 'delete', $user_id = null){
        if(!is_array($message)){
            $message = MessagesTable::model()->find($message, 'user_id');
        }
        $user_id || $user_id = \F::app()->current_user;
        
        if(empty($message['user_id'])){
            throw new ErrorException('指定用户留言不存在');
        }
        
        if($message['user_id'] == $user_id){
            //自己的留言总是有权限操作的
            return true;
        }
        
        if(UserService::service()->isAdmin($user_id) &&
            UserService::service()->checkPermission('cms/admin/message/' . $action, $user_id)){
            //是管理员，判断权限
            return true;
        }
        
        return false;
    }
    
    /**
     * 更新留言状态
     * @param int|array $message_id 留言ID或由留言ID构成的一维数组
     * @param int $status 状态码
     * @return int
     */
    public function setStatus($message_id, $status){
        if(is_array($message_id)){
            return MessagesTable::model()->update(array(
                'status'=>$status,
                'update_time'=>\F::app()->current_time,
            ), array('id IN (?)'=>$message_id));
        }else{
            return MessagesTable::model()->update(array(
                'status'=>$status,
                'update_time'=>\F::app()->current_time,
            ), $message_id);
        }
    }
    
    /**
     * 判断一条用户的改变是否需要改变用户留言数
     * @param array $message 单条留言，必须包含status,sockpuppet字段
     * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
     * @return bool
     */
    private function needChangeMessages($message, $action){
        $user_message_verify = OptionService::get('system:user_message_verify');
        if(in_array($action, array('delete', 'remove', 'undelete', 'create'))){
            if($message['status'] == MessagesTable::STATUS_APPROVED || !$user_message_verify){
                return true;
            }
        }else if($action == 'approve'){
            //只要开启了留言审核，则必然在通过审核的时候用户留言数+1
            if($user_message_verify){
                return true;
            }
        }else if($action == 'disapprove'){
            //如果留言原本是通过审核状态，且系统开启了用户留言审核，则当留言未通过审核时，相应用户留言数-1
            if($message['status'] == MessagesTable::STATUS_APPROVED && $user_message_verify){
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 更user_counter表messages和real_messages字段。
     * @param array $messages 相关留言（二维数组，每项必须包含to_user_id,status,sockpuppet字段）
     * @param string $action 操作（可选：delete/undelete/remove/create/approve/disapprove）
     */
    public function updateMessages($messages, $action){
        $users = array();
        foreach($messages as $m){
            if($this->needChangeMessages($m, $action)){
                //更新留言数
                if(isset($users[$m['to_user_id']]['messages'])){
                    $users[$m['to_user_id']]['messages']++;
                }else{
                    $users[$m['to_user_id']]['messages'] = 1;
                }
                if(!$m['sockpuppet']){
                    //如果不是马甲，更新真实留言数
                    if(isset($users[$m['to_user_id']]['real_messages'])){
                        $users[$m['to_user_id']]['real_messages']++;
                    }else{
                        $users[$m['to_user_id']]['real_messages'] = 1;
                    }
                }
            }
        }
        
        foreach($users as $to_user_id => $message_count){
            $messages = isset($message_count['messages']) ? $message_count['messages'] : 0;
            $real_messages = isset($message_count['real_messages']) ? $message_count['real_messages'] : 0;
            if(in_array($action, array('delete', 'remove', 'disapprove'))){
                //如果是删除相关的操作，取反
                $messages = - $messages;
                $real_messages = - $real_messages;
            }
            
            if($messages && $messages == $real_messages){
                //如果全部留言都是真实留言，则一起更新real_messages和messages
                UserCounterTable::model()->incr($to_user_id, array('messages', 'real_messages'), $messages);
            }else{
                if($messages){
                    UserCounterTable::model()->incr($to_user_id, array('messages'), $messages);
                }
                if($real_messages){
                    UserCounterTable::model()->incr($to_user_id, array('real_messages'), $real_messages);
                }
            }
        }
    }
    
    /**
     * 根据用户ID，以树的形式（体现层级结构）返回留言
     * @param int $to_user_id 用户ID
     * @param int $page_size 分页大小
     * @param int $page 页码
     * @param string $fields 字段
     * @return array
     */
    public function getTree($to_user_id, $page_size = 10, $page = 1, $fields = 'id,content,parent,create_time,user.id,user.nickname,user.avatar'){
        $conditions = array(
            'delete_time = 0',
        );
        if(OptionService::get('system:user_message_verify')){
            //开启了留言审核
            $conditions[] = 'status = '.MessagesTable::STATUS_APPROVED;
        }
        
        return $this->_getTree($to_user_id,
            $page_size,
            $page,
            $fields,
            $conditions
        );
    }
    
    /**
     * 根据用户ID，以列表的形式（俗称“盖楼”）返回留言
     * @param int $to_user_id 用户ID
     * @param int $page_size 分页大小
     * @param int $page 页码
     * @param string|array $fields 字段
     * @return array
     */
    public function getList($to_user_id, $page_size = 10, $page = 1, $fields = array(
        'message'=>array(
            'id', 'content', 'parent', 'create_time',
        ),
        'user'=>array(
            'id', 'nickname', 'avatar',
        ),
        'parent'=>array(
            'user'=>array(
                'nickname',
            ),
        ),
    )){
        $conditions = array(
            't.delete_time = 0',
        );
        $join_conditions = array(
            't2.delete_time = 0',
        );
        if(OptionService::get('system:user_message_verify')){
            //开启了留言审核
            $conditions[] = 't.status = '.MessagesTable::STATUS_APPROVED;
            $join_conditions[] = 't2.status = '.MessagesTable::STATUS_APPROVED;
        }
        
        $result = $this->_getList($to_user_id,
            $page_size,
            $page,
            $fields,
            $conditions,
            $join_conditions
        );
        
        return array(
            'messages'=>$result['data'],
            'pager'=>$result['pager'],
        );
    }
    
    /**
     * 根据用户ID，以列表的形式（俗称“盖楼”）返回留言
     * @param $parent_id
     * @param int $page_size 分页大小
     * @param int $page 页码
     * @param array|string $fields 字段
     * @param string $order
     * @return array
     * @throws ErrorException
     */
    public function getChildrenList($parent_id, $page_size = 10, $page = 1, $fields = array(
        'message'=>array(
            'id', 'content', 'parent', 'create_time',
        ),
        'user'=>array(
            'id', 'nickname', 'avatar',
        ),
        'parent'=>array(
            'user'=>array(
                'nickname',
            ),
        ),
    ), $order = 'ASC'){
        $conditions = array(
            't.delete_time = 0',
        );
        $join_conditions = array(
            't2.delete_time = 0',
        );
        if(OptionService::get('system:user_message_verify')){
            //开启了留言审核
            $conditions[] = 't.status = '.MessagesTable::STATUS_APPROVED;
            $join_conditions[] = 't2.status = '.MessagesTable::STATUS_APPROVED;
        }
        
        $result = $this->_getChildrenList($parent_id,
            $page_size,
            $page,
            $fields,
            $conditions,
            $join_conditions,
            $order
        );
        
        return array(
            'messages'=>$result['data'],
            'pager'=>$result['pager'],
        );
    }
    
    /**
     * 根据用户ID，以二级树的形式（所有对留言的回复不再体现层级结构）返回留言
     * @param int $user_id 用户ID
     * @param int $page_size 分页大小
     * @param int $page 页码
     * @param string|array $fields 字段
     * @return array
     */
    public function getChats($user_id, $page_size = 10, $page = 1, $fields = array(
        'message'=>array(
            'id', 'content', 'parent', 'create_time',
        ),
        'user'=>array(
            'id', 'nickname', 'avatar',
        ),
    )){
        $conditions = array(
            'delete_time = 0',
        );
        if(OptionService::get('system:user_message_verify')){
            //开启了评论审核
            $conditions[] = 'status = '.MessagesTable::STATUS_APPROVED;
        }
        
        $result = $this->_getChats($user_id,
            $page_size,
            $page,
            $fields,
            $conditions
        );
        
        return array(
            'messages'=>$result['data'],
            'pager'=>$result['pager'],
        );
    }
    
    /**
     * 获取回复数（不包含回收站里的）
     * @param int $id
     */
    public function getReplyCount($id){
        $message = MessagesTable::model()->find($id, 'root,left_value,right_value');
        
        $count = MessagesTable::model()->fetchRow(array(
            'root = ' . $message['root'],
            'left_value > ' . $message['left_value'],
            'right_value < ' . $message['right_value'],
        ), 'COUNT(*) AS count');
        return $count['count'];
    }
}