<?php
namespace fay\models;

use fay\core\Model;
use fay\core\db\Expr;
use fay\core\ErrorException;
use fay\helpers\FieldHelper;
use fay\helpers\ArrayHelper;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\PostCommentsTable;
use fay\services\user\UserService;

/**
 * 基于左右值的多树操作
 * 该模型针对一张表对应多棵树的数据结构，适用于无限极回复、评论等需求。
 * 该模型不支持手工排序，后插入的记录永远在先插入记录后面出现。
 * 该模型正序或者倒序关系并不明确，因为评论是正序或者倒序都是合理的需求，左右值的作用只是用于获取子节点、判断节点是否为叶子节点。
 * 因为左右值并不用于保证节点直接的顺序，所以构造树的时候用parent节点来构造。
 * 该模型不做数据正确性验证
 * **关键字段**
 *  - id 节点ID
 *  - left_value 左值
 *  - right_value 右值
 *  - parent 父节点ID
 *  - root 根节点。多树模型，需要有个根节点来标识树之间的关系
 */
abstract class MultiTreeModel extends Model{
    /**
     * 表结构模型（子类中必须指定）
     * @var string
     */
    protected $model;
    
    /**
     * 外主键（例如文章评论，则对应外主键是文章ID：post_id）
     * @var string
     */
    protected $foreign_key;
    
    /**
     * 用于输入输出时指定$fields默认的key
     * @var string
     */
    protected $field_key;
    
    public function __construct(){
        if(!$this->model){
            throw new ErrorException(__CLASS__ . '::$model属性未指定');
        }
        if(!$this->foreign_key){
            throw new ErrorException(__CLASS__ . '::$foreign_key属性未指定');
        }
        if(!$this->field_key){
            throw new ErrorException(__CLASS__ . '::$field_key属性未指定');
        }
    }
    
    /**
     * 创建一个节点
     * @param array $data 数据
     * @param int $parent 父节点
     * @return int
     */
    protected function _create($data, $parent = 0){
        if($parent == 0){
            //插入根节点
            $node_id = \F::table($this->model)->insert(array_merge($data, array(
                'parent'=>$parent,
                'left_value'=>1,
                'right_value'=>2,
            )));
            //根节点是自己
            \F::table($this->model)->update(array(
                'root'=>$node_id,
            ), $node_id);
        }else{
            //插入叶子节点
            $parent_node = \F::table($this->model)->find($parent, 'id,root,left_value,right_value');
            if($parent_node['right_value'] - $parent_node['left_value'] == 1){
                //父节点是叶子节点
                \F::table($this->model)->incr(array(
                    'root = ' . $parent_node['root'],
                    'left_value > ' . $parent_node['left_value'],
                ), 'left_value', 2);
                \F::table($this->model)->incr(array(
                    'root = ' . $parent_node['root'],
                    'right_value > ' . $parent_node['left_value'],
                ), 'right_value', 2);
                $node_id = \F::table($this->model)->insert(array_merge($data, array(
                    'parent'=>$parent,
                    'left_value'=>$parent_node['left_value'] + 1,
                    'right_value'=>$parent_node['left_value'] + 2,
                    'root'=>$parent_node['root'],
                )));
            }else{
                //父节点非叶子节点，插入到最右侧（因为留言系统一般回复都是按时间正序排列的）
                $left_node = \F::table($this->model)->fetchRow(array(
                    'parent = ' . $parent,
                ), 'left_value,right_value', 'left_value DESC');
                \F::table($this->model)->incr(array(
                    'root = ' . $parent_node['root'],
                    'left_value > ' . $left_node['right_value'],
                ), 'left_value', 2);
                \F::table($this->model)->incr(array(
                    'root = ' . $parent_node['root'],
                    'right_value > ' . $left_node['right_value'],
                ), 'right_value', 2);
                $node_id = \F::table($this->model)->insert(array_merge($data, array(
                    'parent'=>$parent,
                    'root'=>$parent_node['root'],
                    'left_value'=>$left_node['right_value'] + 1,
                    'right_value'=>$left_node['right_value'] + 2,
                )));
            }
        }
        return $node_id;
    }
    
    /**
     * 删除一个节点
     * 物理删除，其子节点会挂到其父节点上
     * @param int|array $node 节点ID或包含id,left_value,right_value,parent,root节点信息的数组
     * @return bool
     * @throws ErrorException
     */
    protected function _remove($node){
        //获取被删除节点
        if(!is_array($node)){
            $node = \F::table($this->model)->find($node, 'id,left_value,right_value,parent,root');
        }
        if(!$node){
            throw new ErrorException('节点不存在', 'node-not-exist');
        }
        
        if($node['right_value'] - $node['left_value'] == 1){
            //被删除节点是叶子节点
            if($node['right_value'] != 2){
                //不是根节点（是根节点且是叶子节点的话，无需操作）
                //所有后续节点左右值-2
                \F::table($this->model)->incr(array(
                    'root = ' . $node['root'],
                    'right_value > ' . $node['right_value'],
                    'left_value > ' . $node['right_value'],
                ), array('left_value', 'right_value'), -2);
                //所有父节点右值-2
                \F::table($this->model)->incr(array(
                    'root = ' . $node['root'],
                    'right_value > ' . $node['right_value'],
                    'left_value < ' . $node['left_value'],
                ), 'right_value', -2);
            }
            
            //删除当前节点
            \F::table($this->model)->delete($node['id']);
            
            return true;
        }else{
            //所有子节点左右值-1
            \F::table($this->model)->update(array(
                'left_value'=>new Expr('left_value - 1'),
                'right_value'=>new Expr('right_value - 1'),
            ), array(
                'root = ' . $node['root'],
                'left_value > ' . $node['left_value'],
                'right_value < ' . $node['right_value'],
            ));
            //所有后续节点左右值-2
            \F::table($this->model)->update(array(
                'left_value'=>new Expr('left_value - 2'),
                'right_value'=>new Expr('right_value - 2'),
            ), array(
                'root = ' . $node['root'],
                'left_value > ' . $node['right_value'],
                'right_value > ' . $node['right_value'],
            ));
            \F::table($this->model)->incr(array(
                'root = ' . $node['root'],
                'left_value < ' . $node['left_value'],
                'right_value > ' . $node['right_value'],
            ), array(
                'left_value', 'right_value'
            ), -2);
            //所有父节点
            \F::table($this->model)->incr(array(
                'root = ' . $node['root'],
                'left_value < ' . $node['left_value'],
                'right_value > ' . $node['right_value'],
            ), 'right_value', -2);
            
            //将所有父节点为该节点的parent字段指向其parent
            \F::table($this->model)->update(array(
                'parent'=>$node['parent'],
            ), 'parent = ' . $node['id']);
            
            //删除当前节点
            \F::table($this->model)->delete($node['id']);
            
            return true;
        }
    }
    
    /**
     * 删除一个节点，及其所有子节点
     * @param int|array $node 节点ID或包含id,left_value,right_value,root节点信息的数组
     * @return bool
     * @throws ErrorException
     */
    protected function _removeAll($node){
        //获取被删除节点
        if(!is_array($node)){
            $node = \F::table($this->model)->find($node, 'id,left_value,right_value,root');
        }
        if(!$node){
            throw new ErrorException('节点不存在', 'node-not-exist');
        }
        
        //删除所有树枝节点
        \F::table($this->model)->delete(array(
            'root = ' . $node['root'],
            'left_value >= ' . $node['left_value'],
            'right_value <= ' . $node['right_value'],
        ));
        
        //差值
        $diff = $node['right_value'] - $node['left_value'] + 1;
        //所有后续节点减去差值
        \F::table($this->model)->update(array(
            'left_value'=>new Expr('left_value - ' . $diff),
            'right_value'=>new Expr('right_value - ' . $diff),
        ), array(
            'root = ' . $node['root'],
            'left_value > ' . $node['left_value'],
            'right_value > ' . $node['right_value'],
        ));
        //所有父节点的右节点减去差值
        \F::table($this->model)->update(array(
            'right_value'=>new Expr('right_value - ' . $diff),
        ), array(
            'root = ' . $node['root'],
            'left_value < ' . $node['left_value'],
            'right_value > ' . $node['right_value'],
        ));
        return true;
    }
    
    /**
     * 根据root重建一棵树的索引
     * @param int $root
     * @param int $parent
     * @param int $start_num
     * @param array $nodes 递归的时候，直接传入子节点，而不需要再搜一次
     * @return int
     */
    public function buildIndex($root, $parent = 0, $start_num = 0, $nodes = null){
        $nodes || $nodes = \F::table($this->model)->fetchAll(array(
            'root = ?'=>$root,
            'parent = ?'=>$parent,
        ), 'id', 'id DESC');
        foreach($nodes as $node){
            $children = \F::table($this->model)->fetchAll(array(
                'root = ?'=>$root,
                'parent = ?'=>$node['id'],
            ), 'id', 'id DESC');
            if($children){
                //有孩子，先记录左节点，右节点待定
                $left = ++$start_num;
                $start_num = $this->buildIndex($root, $node['id'], $start_num, $children);
                \F::table($this->model)->update(array(
                    'left_value'=>$left,
                    'right_value'=>++$start_num,
                ), $node['id']);
            }else{
                //已经是叶子节点，直接记录左右节点
                \F::table($this->model)->update(array(
                    'left_value'=>++$start_num,
                    'right_value'=>++$start_num,
                ), $node['id']);
            }
        }
        return $start_num;
    }
    
    /**
     * 以树的方式返回
     * @param int $value 关联ID，例如：文章ID
     * @param int $count 返回根记录数（回复有多少返回多少，不分页）
     * @param int $page 页码
     * @param string $fields 可指定返回字段（虽然把user等字段放这里会让model看起来不纯，但是性能上会好很多）
     *  - 无前缀系列可指定$model表返回字段，若未指定，默认为*
     *  - user.*系列可指定作者信息，格式参照\fay\services\user\UserService::get()
     * @param array $conditions 附加条件（例如审核状态等与树结构本身无关的条件）
     * @param string $order 排序条件
     * @return array
     */
    protected function _getTree($value, $count = 10, $page = 1, $fields = '*', $conditions = array(), $order = 'root DESC, left_value ASC'){
        //解析$fields
        $fields = FieldHelper::parse($fields, $this->field_key);
        if(empty($fields[$this->field_key]) || in_array('*', $fields[$this->field_key])){
            $fields[$this->field_key] = \F::table($this->model)->getFields();
        }
        $node_fields = $fields[$this->field_key];
        //一些需要用到，但未指定返回的字段特殊处理下
        foreach(array('root', 'left_value', 'right_value', 'parent') as $key){
            if(!in_array($key, $node_fields)){
                $node_fields[] = $key;
            }
        }
        if(!empty($fields['user']) && !in_array('user_id', $node_fields)){
            $node_fields[] = 'user_id';
        }
        
        //得到根节点
        $sql = new Sql();
        $sql->from(\F::table($this->model)->getTableName(), 'root')
            ->where(array(
                "{$this->foreign_key} = ?"=>$value,
                'left_value = 1',
            ))
            ->where($conditions);
        $listview = new ListView($sql, array(
            'current_page'=>$page,
            'page_size'=>$count,
        ));
        
        $root_nodes = ArrayHelper::column($listview->getData(), 'root');
        if($root_nodes){
            //搜索所有节点
            $nodes = \F::table($this->model)->fetchAll(array_merge(array(
                'root IN (?)'=>$root_nodes,
            ), $conditions), $node_fields, $order);
            
            //像user这种附加信息，可以一次性获取以提升性能
            $extra = array();
            if(!empty($fields['user'])){
                $extra['users'] = UserService::service()->mget(
                    array_unique(ArrayHelper::column($nodes, 'user_id')),
                    $fields['user'],
                    isset($fields['_extra']) ? $fields['_extra'] : array()
                );
            }
            
            //一棵一棵渲染
            $sub_tree = array();
            $last_root = $nodes[0]['root'];
            $tree = array();
            foreach($nodes as $n){
                if($last_root != $n['root'] && $sub_tree){
                    $tree = array_merge($tree, $this->renderTree($sub_tree, $fields, 0, $extra));
                    $sub_tree = array();
                    $last_root = $n['root'];
                }
                
                $sub_tree[] = $n;
            }
            return array(
                'data'=>array_merge($tree, $this->renderTree($sub_tree, $fields, 0, $extra)),
                'pager'=>$listview->getPager(),
            );
        }else{
            return array(
                'data'=>array(),
                'pager'=>$listview->getPager(),
            );
        }
    }
    
    /**
     * 根据parent字段渲染出一个多维数组
     * （因为$nodes不会包含软删除数据，所以利用left_value和right_value是构造不出tree的，不连续）
     * @param array $nodes
     * @param array $fields
     * @param int $parent
     * @param array $extra
     * @return array
     */
    public function renderTree($nodes, $fields, $parent = 0, $extra = array()){
        $tree = array();
        if(empty($nodes)) return $tree;
        foreach($nodes as $k => $n){
            if($n['parent'] == $parent){
                unset($nodes[$k]);
                $node = array();
                //只返回需要返回的字段
                foreach($n as $key => $val){
                    if(in_array($key, $fields[$this->field_key])){
                        $node[$this->field_key][$key] = $val;
                    }
                }
                
                if(!empty($extra['users'])){
                    //获取user信息
                    $node['user'] = $extra['users'][$n['user_id']];
                }
                
                if($n['right_value'] - $n['left_value'] != 1){
                    //非叶子，获取子树
                    $node['children'] = $this->renderTree($nodes, $fields, $n['id'], $extra);
                }else{
                    $node['children'] = array();
                }
                
                $tree[] = $node;
            }
        }
        return $tree;
    }
    
    /**
     * 根据文章ID，以列表的形式（俗称“盖楼”）返回评论
     * @param int $value 关联ID，例如：文章ID
     * @param int $count
     * @param int $page
     * @param string $fields
     * @param array $conditions 附加条件（例如审核状态等与树结构本身无关的条件）
     * @param array $join_conditions 若fields指定父节点信息，则需要自连接，该条件用于自连接时的附加条件
     * @return array
     */
    protected function _getList($value, $count = 10, $page = 1, $fields = '*', $conditions = array(), $join_conditions = array()){
        //解析$fields
        $fields = FieldHelper::parse($fields, $this->field_key);
        if(empty($fields[$this->field_key]) || in_array('*', $fields[$this->field_key])){
            $fields[$this->field_key] = PostCommentsTable::model()->getFields();
        }
        
        $comment_fields = $fields[$this->field_key];
        if(!empty($fields['user']) && !in_array('user_id', $comment_fields)){
            //若需要获取用户信息，但评论字段未指定user_id，则插入user_id
            $comment_fields[] = 'user_id';
        }
        
        if(!empty($fields['parent'])){
            if(!empty($fields['parent'][$this->field_key]) && in_array('*', $fields['parent'][$this->field_key])){
                $fields['parent'][$this->field_key] = PostCommentsTable::model()->getFields();
            }
            $parent_comment_fields = empty($fields['parent'][$this->field_key]) ? array() : $fields['parent'][$this->field_key];
            if(!empty($fields['parent']['user']) && !in_array('user_id', $parent_comment_fields)){
                //若需要获取父节点用户信息，但父节点评论字段未指定user_id，则插入user_id
                $parent_comment_fields[] = 'user_id';
            }
        }
        
        $sql = new Sql();
        $sql->from(array('t'=>\F::table($this->model)->getTableName()), $comment_fields)
            ->where("t.{$this->foreign_key} = ?", $value)
            ->order('t.id DESC')
        ;
        
        if($conditions){
            $sql->where($conditions);
        }
        
        if(!empty($parent_comment_fields)){
            //表自连接，字段名都是一样的，需要设置别名
            foreach($parent_comment_fields as $key => $f){
                $parent_comment_fields[$key] = $f . ' AS parent_' . $f;
            }
            
            $_join_conditions = array(
                't.parent = t2.id',
            );
            if($join_conditions){
                //开启审核，仅返回通过审核的评论
                $_join_conditions = array_merge($_join_conditions, $join_conditions);
            }
            $sql->joinLeft(array('t2'=>\F::table($this->model)->getTableName()), $_join_conditions, $parent_comment_fields);
        }
        
        $listview = new ListView($sql, array(
            'current_page'=>$page,
            'page_size'=>$count,
        ));
        
        $data = $listview->getData();
        
        if(!empty($fields['user'])){
            //获取评论用户信息集合
            $users = UserService::service()->mget(
                ArrayHelper::column($data, 'user_id'),
                $fields['user'],
                isset($fields['_extra']) ? $fields['_extra'] : array()
            );
        }
        if(!empty($fields['parent']['user'])){
            //获取父节点评论用户信息集合
            $parent_users = UserService::service()->mget(
                ArrayHelper::column($data, 'parent_user_id'),
                $fields['parent']['user'],
                isset($fields['parent']['_extra']) ? $fields['parent']['_extra'] : array()
            );
        }
        $comments = array();
        
        foreach($data as $k => $d){
            $comment = array();
            //评论字段
            foreach($fields[$this->field_key] as $cf){
                $comment[$this->field_key][$cf] = $d[$cf];
            }
            
            //作者字段
            if(!empty($fields['user'])){
                $comment['user'] = $users[$d['user_id']];
            }
            
            //父评论字段
            if(!empty($fields['parent'][$this->field_key])){
                if($d['parent_' . $fields['parent'][$this->field_key][0]] === null){
                    //为null的话意味着父节点不存在或已删除（数据库字段一律非null）
                    $comment['parent'][$this->field_key] = array();
                }else{
                    foreach($fields['parent'][$this->field_key] as $pcf){
                        $comment['parent'][$this->field_key][$pcf] = $d['parent_' . $pcf];
                    }
                }
            }
            
            //父评论作者字段
            if(!empty($fields['parent']['user'])){
                $comment['parent']['user'] = $parent_users[$d['parent_user_id']];
            }
            
            $comments[] = $comment;
        }
        
        return array(
            'data'=>$comments,
            'pager'=>$listview->getPager(),
        );
    }
    
    /**
     * 以2级树的形式返回
     * @param int $value 关联ID，例如：文章ID
     * @param int $count
     * @param int $page
     * @param string $fields
     * @param array $conditions
     * @param string $order 程序内部已经按照root DESC排序了，因为同一个会话必须在一起，否则无法渲染，可以附加其他排序条件
     * @return array
     */
    protected function _getChats($value, $count = 10, $page = 1, $fields = '*', $conditions = array(), $order = 'id DESC'){
        //解析$fields
        $fields = FieldHelper::parse($fields, $this->field_key);
        if(empty($fields[$this->field_key]) || in_array('*', $fields[$this->field_key])){
            $fields[$this->field_key] = \F::table($this->model)->getFields();
        }
        $node_fields = $fields[$this->field_key];
        //一些需要用到，但未指定返回的字段特殊处理下
        foreach(array('root', 'left_value', 'right_value', 'parent') as $key){
            if(!in_array($key, $node_fields)){
                $node_fields[] = $key;
            }
        }
        if(!empty($fields['user']) && !in_array('user_id', $node_fields)){
            $node_fields[] = 'user_id';
        }
        
        //得到根节点
        $sql = new Sql();
        $sql->from(\F::table($this->model)->getTableName(), 'root')
            ->where(array(
                "{$this->foreign_key} = ?"=>$value,
                'left_value = 1',
            ))
            ->where($conditions);
        $listview = new ListView($sql, array(
            'current_page'=>$page,
            'page_size'=>$count,
        ));
        
        $root_nodes = ArrayHelper::column($listview->getData(), 'root');
        if($root_nodes){
            //搜索所有节点
            $nodes = \F::table($this->model)->fetchAll(array_merge(array(
                'root IN (?)'=>$root_nodes,
            ), $conditions), $node_fields, 'root DESC' . ($order ? ", {$order}" : ''));
                
            //用户信息
            if(!empty($fields['user'])){
                $users = UserService::service()->mget(array_unique(ArrayHelper::column($nodes, 'user_id')), $fields['user']);
            }
                
            //一个会话一个会话渲染
            $chats = array();
            $chat = array();
            foreach($nodes as $n){
                if($n['left_value'] == 1){//根节点
                    //若遇到下一个根节点，则将以渲染好的会话挂载到会话列表中
                    if(!empty($chat)){
                        $chats[] = $chat;
                    }
                    $chat = array(
                        $this->field_key=>array(),
                        'children'=>array(),
                    );
                    //评论字段
                    foreach($fields[$this->field_key] as $cf){
                        $chat[$this->field_key][$cf] = $n[$cf];
                    }
                        
                    //作者字段
                    if(!empty($fields['user'])){
                        $chat['user'] = $users[$n['user_id']];
                    }
                }else{
                    $sub_chat = array(
                        'children'=>array(),//这里写死一个children是为了每项返回的数据结构一致
                    );
                    //评论字段
                    foreach($fields[$this->field_key] as $cf){
                        $sub_chat[$this->field_key][$cf] = $n[$cf];
                    }
                    
                    //作者字段
                    if(!empty($fields['user'])){
                        $sub_chat['user'] = $users[$n['user_id']];
                    }
                    $chat['children'][] = $sub_chat;
                }
            }
            if(!empty($chat)){
                //最后一个会话挂载到会话列表
                $chats[] = $chat;
            }
            
            return array(
                'data'=>$chats,
                'pager'=>$listview->getPager(),
            );
        }else{
            return array(
                'data'=>array(),
                'pager'=>$listview->getPager(),
            );
        }
    }
    
    /**
     * 通过父节点ID，以列表形式返回所有子节点
     * @param int $parent_id 父节点ID，不能为0
     * @param int $count
     * @param int $page
     * @param string|array $fields
     * @param array $conditions
     * @param array $join_conditions
     * @param string $order 排序方式（ASC, DESC可选）
     * @return array
     * @throws ErrorException
     */
    protected function _getChildrenList($parent_id, $count = 10, $page = 1, $fields = '*', $conditions = array(), $join_conditions = array(), $order = 'ASC'){
        if(!$parent_id){
            throw new ErrorException('父节点不能为空');
        }
        
        $node = \F::table($this->model)->find($parent_id, 'root,left_value,right_value');
        if(!$node){
            throw new ErrorException('指定父节点不存在');
        }
        
        //解析$fields
        $fields = FieldHelper::parse($fields, $this->field_key);
        if(empty($fields[$this->field_key]) || in_array('*', $fields[$this->field_key])){
            $fields[$this->field_key] = PostCommentsTable::model()->getFields();
        }
        
        $comment_fields = $fields[$this->field_key];
        if(!empty($fields['user']) && !in_array('user_id', $comment_fields)){
            //若需要获取用户信息，但评论字段未指定user_id，则插入user_id
            $comment_fields[] = 'user_id';
        }
        
        if(!empty($fields['parent'])){
            if(!empty($fields['parent'][$this->field_key]) && in_array('*', $fields['parent'][$this->field_key])){
                $fields['parent'][$this->field_key] = PostCommentsTable::model()->getFields();
            }
            $parent_comment_fields = empty($fields['parent'][$this->field_key]) ? array() : $fields['parent'][$this->field_key];
            if(!empty($fields['parent']['user']) && !in_array('user_id', $parent_comment_fields)){
                //若需要获取父节点用户信息，但父节点评论字段未指定user_id，则插入user_id
                $parent_comment_fields[] = 'user_id';
            }
        }
        
        $sql = new Sql();
        $sql->from(array('t'=>\F::table($this->model)->getTableName()), $comment_fields)
            ->where('t.root = ' . $node['root'])
            ->where('t.left_value > ' . $node['left_value'])
            ->where('t.right_value < ' . $node['right_value'])
            ->order('t.id ' . ($order == 'DESC' ? 'DESC' : 'ASC'))
        ;
        
        if($conditions){
            $sql->where($conditions);
        }
        
        if(!empty($parent_comment_fields)){
            //表自连接，字段名都是一样的，需要设置别名
            foreach($parent_comment_fields as $key => $f){
                $parent_comment_fields[$key] = $f . ' AS parent_' . $f;
            }
            
            $_join_conditions = array(
                't.parent = t2.id',
            );
            if($join_conditions){
                //开启审核，仅返回通过审核的评论
                $_join_conditions = array_merge($_join_conditions, $join_conditions);
            }
            $sql->joinLeft(array('t2'=>\F::table($this->model)->getTableName()), $_join_conditions, $parent_comment_fields);
        }
        
        $listview = new ListView($sql, array(
            'current_page'=>$page,
            'page_size'=>$count,
        ));
        
        $data = $listview->getData();
        
        if(!empty($fields['user'])){
            //获取评论用户信息集合
            $users = UserService::service()->mget(ArrayHelper::column($data, 'user_id'), $fields['user']);
        }
        if(!empty($fields['parent']['user'])){
            //获取父节点评论用户信息集合
            $parent_users = UserService::service()->mget(ArrayHelper::column($data, 'parent_user_id'), $fields['parent']['user']);
        }
        $comments = array();
        
        foreach($data as $k => $d){
            $comment = array();
            //评论字段
            foreach($fields[$this->field_key] as $cf){
                $comment[$this->field_key][$cf] = $d[$cf];
            }
            
            //作者字段
            if(!empty($fields['user'])){
                $comment['user'] = $users[$d['user_id']];
            }
            
            //父评论字段
            if(!empty($fields['parent'][$this->field_key])){
                if($d['parent_' . $fields['parent'][$this->field_key][0]] === null){
                    //为null的话意味着父节点不存在或已删除（数据库字段一律非null）
                    $comment['parent'][$this->field_key] = array();
                }else{
                    foreach($fields['parent'][$this->field_key] as $pcf){
                        $comment['parent'][$this->field_key][$pcf] = $d['parent_' . $pcf];
                    }
                }
            }
            
            //父评论作者字段
            if(!empty($fields['parent']['user'])){
                $comment['parent']['user'] = $parent_users[$d['parent_user_id']];
            }
            
            $comments[] = $comment;
        }
        
        return array(
            'data'=>$comments,
            'pager'=>$listview->getPager(),
        );
    }
}