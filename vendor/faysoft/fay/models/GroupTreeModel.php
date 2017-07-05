<?php
namespace fay\models;

use fay\core\db\Expr;
use fay\core\db\Table;
use fay\core\ErrorException;
use fay\core\Exception;
use fay\helpers\StringHelper;

/**
 * 基于左右值的树操作
 * 该模型针对一张表对应多棵树的数据结构，适用于多站点分类管理等需求
 * 该模型基于ID或直接传入数组操作，不根据alias进行操作，也不做数据正确性验证
 * **关键字段**：
 *  - id 节点ID
 *  - $this->group_field 归档字段
 *  - left_value 左值
 *  - right_value 右值
 *  - parent 父节点ID
 *  - sort 排序值（在平级内部起作用，不同层级之间的排序不受sort影响。）
 */
abstract class GroupTreeModel{
    /**
     * 归档字段
     * @var string
     */
    protected $group_field;

    /**
     * 获取表model
     * @return Table
     */
    abstract protected function getModel();
    
    public function __construct(){
        if(!$this->group_field){
            throw new ErrorException(__CLASS__ . '::$group_field属性未指定');
        }
    }

    /**
     * 索引记录
     * @param int $group_id 归档ID
     * @param int $parent
     * @param int $start_num
     * @param array $nodes
     * @return int
     */
    public function buildIndex($group_id, $parent = 0, $start_num = 1, $nodes = array()){
        $nodes || $nodes = $this->getModel()->fetchAll(array(
            "{$this->group_field} = ?"=>$group_id,
            'parent = ?'=>$parent,
        ), 'id', 'sort, id ASC');
        foreach($nodes as $node){
            $children = $this->getModel()->fetchAll(array(
                "{$this->group_field} = ?"=>$group_id,
                'parent = ?'=>$node['id'],
            ), 'id', 'sort, id ASC');
            if($children){
                //有孩子，先记录左节点，右节点待定
                $left = ++$start_num;
                $start_num = $this->buildIndex($group_id, $node['id'], $start_num, $children);
                $this->getModel()->update(array(
                    'left_value'=>$left,
                    'right_value'=>++$start_num,
                ), $node['id']);
            }else{
                //已经是叶子节点，直接记录左右节点
                $this->getModel()->update(array(
                    'left_value'=>++$start_num,
                    'right_value'=>++$start_num,
                ), $node['id']);
            }
        }
        return $start_num;
    }

    /**
     * 创建一个节点
     * @param int $group_id 归档ID
     * @param int $parent 父节点
     * @param int $sort 排序值
     * @param array $data 其它参数
     * @return int 节点ID
     * @throws Exception
     */
    public function create($group_id, $parent, $sort, $data){
        if($parent == 0){
            //插入根节点
            $right_node = $this->getModel()->fetchRow(array(
                "{$this->group_field} = ?"=>$group_id,
                'parent = 0',
                'sort > ' . $sort,
            ), 'left_value,right_value', 'sort ASC, id ASC');
            
            if($right_node){
                //存在右节点
                $this->getModel()->incr(array(
                    "{$this->group_field} = ?"=>$group_id,
                    'left_value >= ' . $right_node['left_value'],
                ), 'left_value', 2);
                $this->getModel()->incr(array(
                    "{$this->group_field} = ?"=>$group_id,
                    'right_value >= ' . $right_node['left_value'],
                ), 'right_value', 2);
                $node_id = $this->getModel()->insert(array_merge($data, array(
                    $this->group_field=>$group_id,
                    'sort'=>$sort,
                    'parent'=>$parent,
                    'left_value'=>$right_node['left_value'],
                    'right_value'=>$right_node['left_value'] + 1,
                )));
            }else{
                //不存在右节点，即在孩子的最后面插入
                $max_right_node = $this->getModel()->fetchRow(array(
                    "{$this->group_field} = ?"=>$group_id,
                ), 'MAX(right_value) AS max');
                //编号从2开始，所以第一个插入的节点，左值应该是2，而不是1
                $max_right_node_value = empty($max_right_node['max']) ? 1 : $max_right_node['max']; 
                $node_id = $this->getModel()->insert(array_merge($data, array(
                    $this->group_field=>$group_id,
                    'sort'=>$sort,
                    'parent'=>$parent,
                    'left_value'=>$max_right_node_value + 1,
                    'right_value'=>$max_right_node_value + 2,
                )));
            }
        }else{
            $parent_node = $this->getModel()->find($parent, 'left_value,right_value,'.$this->group_field);
            if(!$parent_node){
                throw new Exception('父节点不存在， 参数异常');
            }
            if($parent_node[$this->group_field] != $group_id){
                throw new Exception('父节点归档ID与指定归档ID不一致');
            }
            
            if($parent_node['right_value'] - $parent_node['left_value'] == 1){
                //父节点本身是叶子节点，直接挂载
                $this->getModel()->incr(array(
                    "{$this->group_field} = ?"=>$group_id,
                    'left_value > ' . $parent_node['left_value'],
                ), 'left_value', 2);
                $this->getModel()->incr(array(
                    "{$this->group_field} = ?"=>$group_id,
                    'right_value > ' . $parent_node['left_value'],
                ), 'right_value', 2);
                $node_id = $this->getModel()->insert(array_merge($data, array(
                    $this->group_field=>$group_id,
                    'sort'=>$sort,
                    'parent'=>$parent,
                    'left_value'=>$parent_node['left_value'] + 1,
                    'right_value'=>$parent_node['left_value'] + 2,
                )));
            }else{
                //父节点非叶子节点
                //定位新插入节点的排序位置
                $left_node = $this->getModel()->fetchRow(array(
                    "{$this->group_field} = ?"=>$group_id,
                    'parent = ' . $parent,
                    'sort <= ' . $sort,
                ), 'left_value,right_value', 'sort DESC, id DESC');
                
                if($left_node){
                    //存在左节点
                    $this->getModel()->incr(array(
                        "{$this->group_field} = ?"=>$group_id,
                        'left_value > ' . $left_node['right_value'],
                    ), 'left_value', 2);
                    $this->getModel()->incr(array(
                        "{$this->group_field} = ?"=>$group_id,
                        'right_value > ' . $left_node['right_value'],
                    ), 'right_value', 2);
                    $node_id = $this->getModel()->insert(array_merge($data, array(
                        $this->group_field=>$group_id,
                        'sort'=>$sort,
                        'parent'=>$parent,
                        'left_value'=>$left_node['right_value'] + 1,
                        'right_value'=>$left_node['right_value'] + 2,
                    )));
                }else{
                    //不存在左节点，即在孩子的最前面插入
                    $this->getModel()->incr(array(
                        "{$this->group_field} = ?"=>$group_id,
                        'left_value > ' . $parent_node['left_value'],
                    ), 'left_value', 2);
                    $this->getModel()->incr(array(
                        "{$this->group_field} = ?"=>$group_id,
                        'right_value > ' . $parent_node['left_value'],
                    ), 'right_value', 2);
                    $node_id = $this->getModel()->insert(array_merge($data, array(
                        $this->group_field=>$group_id,
                        'sort'=>$sort,
                        'parent'=>$parent,
                        'left_value'=>$parent_node['left_value'] + 1,
                        'right_value'=>$parent_node['left_value'] + 2,
                    )));
                }
            }
        }
        return $node_id;
    }

    /**
     * 修改一个节点
     * @param int $id 节点ID
     * @param array $data 数据
     * @param int $sort 排序值
     * @param int $parent 父节点
     * @throws Exception
     */
    public function update($id, $data, $sort = null, $parent = null){
        $node = $this->getModel()->find($id);
        if($parent !== null){
            if($parent != 0){
                $parent_node = $this->getModel()->find($parent, $this->group_field);
                if($parent_node[$this->group_field] != $node[$this->group_field]){
                    throw new Exception('节点不能在不同归档之间移动');
                }
            }
            $data['parent'] = $parent;
        }
        if($sort !== null){
            $data['sort'] = $sort;
        }
        $this->getModel()->update($data, $id);
        
        if($parent !== null && $parent != $node['parent']){
            //修改了parent
            //获取该节点为根节点的树枝
            $branch_ids = $this->getModel()->fetchCol('id', array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'left_value >= ' . $node['left_value'],
                'right_value <= ' . $node['right_value'],
            ));
            /*
             * 先视为删除这个树枝
             */
            $diff = $node['right_value'] - $node['left_value'] + 1;//差值
            //所有后续节点减去差值
            $this->getModel()->update(array(
                'left_value'=>new Expr('left_value - ' . $diff),
                'right_value'=>new Expr('right_value - ' . $diff),
            ), array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'right_value > ' . $node['right_value'],
                'left_value > ' . $node['right_value'],
            ));
            //所有父节点的右节点减去差值
            $this->getModel()->update(array(
                'right_value'=>new Expr('right_value - ' . $diff),
            ), array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'right_value > ' . $node['right_value'],
                'left_value < ' . $node['left_value'],
            ));
            /*
             * 将树枝挂载过去
             */
            //获取父节点
            if($parent != 0){
                $parent_node = $this->getModel()->find($parent, 'left_value,right_value');
            }else{
                //移到根节点
                $max_right = $this->getModel()->fetchRow(array(
                    "{$this->group_field} = ?"=>$node[$this->group_field],
                ), 'MAX(right_value) AS max');
                $parent_node = array(
                    'left_value'=>0,
                    'right_value'=>$max_right['max'] + 1,
                );
            }
            if($parent_node['right_value'] - $parent_node['left_value'] == 1){
                //叶子节点，直接挂
                //所有后续节点加上差值
                $this->getModel()->update(array(
                    'left_value'=>new Expr('left_value + ' . $diff),
                    'right_value'=>new Expr('right_value + ' . $diff),
                ), array(
                    "{$this->group_field} = ?"=>$node[$this->group_field],
                    'right_value > ' . $parent_node['right_value'],
                    'left_value > ' . $parent_node['right_value'],
                    'id NOT IN ('.implode(',', $branch_ids).')',
                ));
                //所有父节点的右节点加上差值
                $this->getModel()->update(array(
                    'right_value'=>new Expr('right_value + ' . $diff),
                ), array(
                    "{$this->group_field} = ?"=>$node[$this->group_field],
                    'right_value >= ' . $parent_node['right_value'],
                    'left_value <= ' . $parent_node['left_value'],
                    'id NOT IN ('.implode(',', $branch_ids).')',
                ));
                $diff2 = $parent_node['right_value'] - $node['left_value'];
                $this->getModel()->update(array(
                    'left_value'=>new Expr('left_value + ' . $diff2),
                    'right_value'=>new Expr('right_value + ' . $diff2),
                ), 'id IN ('.implode(',', $branch_ids).')');
            }else{
                //若未指定sort，获取源节点的sort值
                if($sort === null){
                    $sort = $node['sort'];
                }
                //寻找挂载位置的右节点
                $right_node = $this->getModel()->fetchRow(array(
                    "{$this->group_field} = ?"=>$node[$this->group_field],
                    'parent = ' . $parent,
                    'or'=>array(
                        'sort > ' . $sort,
                        'and'=>array(
                            'sort = ' . $sort,
                            'id > ' . $id,
                        ),
                    ),
                    'id != ' . $id,
                ), 'left_value,right_value', 'sort ASC, id ASC');
                if($right_node){
                    //存在右节点
                    //所有后续节点及其子节点加上差值
                    $this->getModel()->update(array(
                        'left_value'=>new Expr('left_value + ' . $diff),
                        'right_value'=>new Expr('right_value + ' . $diff),
                    ), array(
                        "{$this->group_field} = ?"=>$node[$this->group_field],
                        'or'=>array(
                            'and'=>array(
                                'right_value >= ' . $right_node['right_value'],
                                'left_value >= ' . $right_node['left_value'],
                            ),
                            'AND'=>array(
                                'left_value > ' . $right_node['left_value'],
                                'right_value < ' . $right_node['right_value'],
                            )
                        ),
                        'id NOT IN ('.implode(',', $branch_ids).')',
                    ));
                    //所有父节点的右节点加上差值
                    $this->getModel()->update(array(
                        'right_value'=>new Expr('right_value + ' . $diff),
                    ), array(
                        "{$this->group_field} = ?"=>$node[$this->group_field],
                        'right_value > ' . $right_node['right_value'],
                        'left_value < ' . $right_node['left_value'],
                        'id NOT IN ('.implode(',', $branch_ids).')',
                    ));
                    $diff2 = $right_node['left_value'] - $node['left_value'];
                    if($diff2 > 0){
                        //右移
                        $diff2 = '+'.($diff2);
                    }
                    $this->getModel()->update(array(
                        'left_value'=>new Expr('left_value ' . $diff2),
                        'right_value'=>new Expr('right_value ' . $diff2),
                    ), 'id IN ('.implode(',', $branch_ids).')');
                }else{
                    //不存在右节点，插到最后
                    //所有后续节点加上差值
                    $this->getModel()->update(array(
                        'left_value'=>new Expr('left_value + ' . $diff),
                        'right_value'=>new Expr('right_value + ' . $diff),
                    ), array(
                        "{$this->group_field} = ?"=>$node[$this->group_field],
                        'right_value > ' . $parent_node['right_value'],
                        'left_value > ' . $parent_node['left_value'],
                        'id NOT IN ('.implode(',', $branch_ids).')',
                    ));
                    //所有父节点的右节点加上差值
                    $this->getModel()->update(array(
                        'right_value'=>new Expr('right_value + ' . $diff),
                    ), array(
                        "{$this->group_field} = ?"=>$node[$this->group_field],
                        'right_value >= ' . $parent_node['right_value'],
                        'left_value <= ' . $parent_node['left_value'],
                        'id NOT IN ('.implode(',', $branch_ids).')',
                    ));
                    $diff2 = $parent_node['right_value'] - $node['left_value'];
                    if($diff2 > 0){
                        //右移
                        $diff2 = '+'.($diff2);
                    }
                    $this->getModel()->update(array(
                        'left_value'=>new Expr('left_value ' . $diff2),
                        'right_value'=>new Expr('right_value ' . $diff2),
                    ), 'id IN ('.implode(',', $branch_ids).')');
                }
            }
        }else if($sort !== null && $sort != $node['sort']){
            //没修改parent，只是修改了排序字段
            $this->sort($node, $sort);
        }
    }

    /**
     * 根据顶层节点ID返回一棵树，但并不包含顶层节点本身
     * @param int $group_id 归档ID
     * @param int $parent
     * @param string $fields
     * @return array
     */
    public function getTree($group_id, $parent = 0, $fields = '*'){
        if($parent == 0){
            $nodes = $this->getModel()->fetchAll(array(
                "{$this->group_field} = ?"=>$group_id,
            ), $fields, 'left_value');
        }else{
            $parent_node = $this->getModel()->find($parent, 'left_value,right_value');
            $nodes = $this->getModel()->fetchAll(array(
                "{$this->group_field} = ?"=>$group_id,
                'left_value > ' . $parent_node['left_value'],
                'right_value < ' . $parent_node['right_value'],
            ), $fields, 'left_value');
        }
        return $this->renderTreeByParent($nodes, $parent);
    }
    
    /**
     * 根据left_value和right_value渲染出一个多维数组
     * （依靠parent构建树逻辑会清晰很多，这个方法只是为了装逼，一般不调用）
     * @param array $nodes
     * @return array
     */
    public function renderTree($nodes){
        if(empty($nodes)) return array();
        $level = 0;//下一根树枝要挂载的层级
        $current_level = 0;//当前层级
        $left = $nodes[0]['left_value'] - 1;//上一片叶子的左值
        $branch = array();//树枝
        $parent_node = null;//叶子前一级树枝
        $leaf = null;//叶子
        $tree = array();//树
        foreach($nodes as $n){
            if($n['left_value'] - $left == 1){
                //子节点
                if(empty($branch)){
                    $branch[] = $n;
                    $leaf = &$branch[0];
                    $parent_node = &$branch;
                }else{
                    $leaf['children'] = array($n);
                    $parent_node = &$leaf;
                    $leaf = &$leaf['children'][0];
                }
                $current_level++;
            }else if($n['left_value'] - $left == 2){
                //同级叶子
                if(isset($parent_node['children'])){
                    $parent_node['children'][] = $n;
                    $leaf = &$parent_node['children'][count($parent_node['children']) - 1];
                }else{
                    //该树枝的根
                    $parent_node[] = $n;
                    $leaf = &$parent_node[count($parent_node) - 1];
                }
            }else{
                //当前树枝遍历完毕，转向父节点进行遍历
                $tree = $this->mountBranch($branch, $tree, $level);//将之前产生的树枝先挂到树上
                $level = $current_level - ($n['left_value'] - $left - 1);//下次挂在这个位置
                $current_level = $level + 1;
                $branch = array($n);//重置树枝
                $parent_node = &$branch;
                $leaf = &$branch[0];
            }
            $left = $n['left_value'];
        }
        $tree = $this->mountBranch($branch, $tree, $level);
        return $tree;
    }
    
    /**
     * 将一根树枝挂载到指定树的指定层级的最右侧
     * @param array $branch
     * @param array $tree
     * @param int $level
     * @return array
     */
    private function mountBranch($branch, $tree, $level){
        if($level == 0){
            $tree = array_merge($tree, $branch);
        }else{
            $temp = &$tree[count($tree) - 1];//第一层的最后一个元素的引用
            for($i = 1; $i < $level; $i++){
                $temp = &$temp['children'][count($temp['children']) - 1];
            }
            $temp['children'] = array_merge($temp['children'], $branch);
        }
        return $tree;
    }
    
    /**
     * 根据parent字段来渲染出一个多维数组
     * @param array $nodes
     * @param int $parent 若根节点非0，需要指定正确的根节点ID
     * @return array
     */
    public function renderTreeByParent(&$nodes, $parent = 0){
        $tree = array();
        if(empty($nodes)){
            return $tree;
        }
        foreach($nodes as $k => $n){
            if($n['parent'] == $parent){
                $tree[] = $n;
                unset($nodes[$k]);
            }
        }
        foreach($tree as &$t){
            if($t['right_value'] - $t['left_value'] != 1){
                //非叶子
                $t['children'] = $this->renderTreeByParent($nodes, $t['id']);
            }else{
                $t['children'] = array();
            }
        }
        return $tree;
    }
    
    /**
     * 删除一个节点，其子节点将被挂载到父节点
     * @param int $id 节点ID
     * @return bool
     */
    public function remove($id){
        //获取被删除节点
        $node = $this->getModel()->find($id, 'left_value,right_value,parent,' . $this->group_field);
        if(!$node){
            //节点不存在，直接返回false
            return false;
        }
        //所有子节点左右值-1
        $this->getModel()->update(array(
            'left_value'=>new Expr('left_value - 1'),
            'right_value'=>new Expr('right_value - 1'),
        ), array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'left_value > ' . $node['left_value'],
            'right_value < ' . $node['right_value'],
        ));
        //所有后续节点左右值-2
        $this->getModel()->update(array(
            'left_value'=>new Expr('left_value - 2'),
            'right_value'=>new Expr('right_value - 2'),
        ), array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'left_value > ' . $node['right_value'],
            'right_value > ' . $node['right_value'],
        ));
        //所有父节点
        $this->getModel()->update(array(
            'right_value'=>new Expr('right_value - 2'),
        ), array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'left_value < ' . $node['left_value'],
            'right_value > ' . $node['right_value'],
        ));
        //删除当前节点
        $this->getModel()->delete($id);
        //将所有父节点为该节点的parent字段指向其parent
        $this->getModel()->update(array(
            'parent'=>$node['parent'],
        ), 'parent = ' . $id);
        
        return true;
    }
    
    /**
     * 删除一个节点，及其所有子节点
     * @param int $id 节点ID
     * @return bool
     */
    public function removeAll($id){
        //获取被删除节点
        $node = $this->getModel()->find($id, 'left_value,right_value,parent,' . $this->group_field);
        if(!$node){
            return false;
        }
        
        //删除所有树枝节点
        $this->getModel()->delete(array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'left_value >= ' . $node['left_value'],
            'right_value <= ' . $node['right_value'],
        ));
        
        //差值
        $diff = $node['right_value'] - $node['left_value'] + 1;
        //所有后续节点减去差值
        $this->getModel()->update(array(
            'left_value'=>new Expr('left_value - ' . $diff),
            'right_value'=>new Expr('right_value - ' . $diff),
        ), array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'left_value > ' . $node['left_value'],
            'right_value > ' . $node['right_value'],
        ));
        //所有父节点的右节点减去差值
        $this->getModel()->update(array(
            'right_value'=>new Expr('right_value - ' . $diff),
        ), array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'left_value < ' . $node['left_value'],
            'right_value > ' . $node['right_value'],
        ));
        return true;
    }

    /**
     * 修改一条记录的sort值，并修改左右值
     * @param int|array $node
     * @param int $sort
     */
    public function sort($node, $sort){
        $sort < 0 && $sort = 0;
        //获取被移动的节点
        if(StringHelper::isInt($node)){
            $node = $this->getModel()->find($node, 'id,left_value,right_value,parent,sort,' . $this->group_field);
            $this->getModel()->update(array(
                'sort'=>$sort,
            ), $node['id']);
        }
        if($node['sort'] == $sort){
            //排序值并未改变
            return;
        }
        //被移动节点原来的左节点（排序值小于该节点 或 ID小于该节点ID）
        $ori_left_node = $this->getModel()->fetchRow(array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'parent = ' . $node['parent'],
            'or'=>array(
                'sort < ' . $node['sort'],
                'and'=>array(
                    'sort = ' . $node['sort'],
                    'id < ' . $node['id'],
                ),
            ),
        ), 'id,sort', 'sort DESC, id DESC');
        $ori_left_node_sort = isset($ori_left_node['sort']) ? $ori_left_node['sort'] : -1;
        //被移动节点原来的右节点（排序值大于该节点 或 ID大于该节点ID）
        $ori_right_node = $this->getModel()->fetchRow(array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'parent = ' . $node['parent'],
            'or'=>array(
                'sort > ' . $node['sort'],
                'and'=>array(
                    'sort = ' . $node['sort'],
                    'id > ' . $node['id'],
                ),
            ),
        ), 'id,sort', 'sort, id ASC');
        $ori_right_node_sort = isset($ori_right_node['sort']) ? $ori_right_node['sort'] : PHP_INT_MAX;
        if($sort < $ori_left_node_sort || ($sort == $ori_left_node_sort && $node['id'] < $ori_left_node['id'])){//节点左移
            //新位置的右节点
            $right_node = $this->getModel()->fetchRow(array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'parent = ' . $node['parent'],
                'or'=>array(
                    'sort > ' . $sort,
                    'and'=>array(
                        'sort = ' . $sort,
                        'id > ' . $node['id'],
                    ),
                ),
                'id != ' . $node['id'],
            ), 'id,left_value', 'sort ASC, id ASC');
            //获取被移动的树枝的所有节点
            $branch_ids = $this->getModel()->fetchCol('id', array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'left_value >= ' . $node['left_value'],
                'right_value <= ' . $node['right_value'],
            ));
            //修改移动区间内树枝的左右值
            $diff = $node['right_value'] - $node['left_value'] + 1;
            $this->getModel()->update(array(
                'left_value'=>new Expr('left_value + ' . $diff),
                'right_value'=>new Expr('right_value + ' . $diff),
            ), array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'left_value >= ' . $right_node['left_value'],
                'right_value < ' . $node['left_value'],
                'id NOT IN('.implode(',', $branch_ids).')',
            ));
            //修改被移动树枝的左右值
            $diff = $node['left_value'] - $right_node['left_value'];
            $this->getModel()->update(array(
                'left_value'=>new Expr('left_value - ' . $diff),
                'right_value'=>new Expr('right_value - ' . $diff),
            ), 'id IN ('.implode(',', $branch_ids).')');
        }else if($sort > $ori_right_node_sort || ($sort == $ori_right_node_sort && $node['id'] > $ori_right_node['id'])){//节点右移
            //新位置的左节点
            $left_node = $this->getModel()->fetchRow(array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'parent = ' . $node['parent'],
                'or'=>array(
                    'sort < ' . $sort,
                    'and'=>array(
                        'sort = ' . $sort,
                        'id < ' . $node['id'],
                    )
                ),
                'id != ' . $node['id']
            ), 'right_value', 'sort DESC, id DESC');
            //获取被移动的树枝的所有节点
            $branch_ids = $this->getModel()->fetchCol('id', array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'left_value >= ' . $node['left_value'],
                'right_value <= ' . $node['right_value'],
            ));
            //修改移动区间内树枝的左右值
            $diff = $node['right_value'] - $node['left_value'] + 1;
            $this->getModel()->update(array(
                'left_value'=>new Expr('left_value - ' . $diff),
                'right_value'=>new Expr('right_value - ' . $diff),
            ), array(
                "{$this->group_field} = ?"=>$node[$this->group_field],
                'left_value > ' . $node['right_value'],
                'right_value <= ' . $left_node['right_value'],
                'id NOT IN('.implode(',', $branch_ids).')',
            ));
            //修改被移动树枝的左右值
            $diff = $left_node['right_value'] - $node['right_value'];
            $this->getModel()->update(array(
                'left_value'=>new Expr('left_value + ' . $diff),
                'right_value'=>new Expr('right_value + ' . $diff),
            ), 'id IN ('.implode(',', $branch_ids).')');
        }
    }
    
    /**
     * 判断$node1是否为$node2的子节点（是同一节点也返回true）
     * @param int|array $node1
     *  - 若为数字，视为分类ID获取分类；
     *  - 若是数组，必须包含left_value, right_value和$this->group_field字段
     * @param int|string|array $node2
     *  - 若为数字，视为分类ID获取分类；
     *  - 若是数组，必须包含left_value, right_value和$this->group_field字段
     * @return bool
     */
    public function isChild($node1, $node2){
        if(!is_array($node1)){
            $node1 = $this->getModel()->find($node1, 'left_value,right_value,' . $this->group_field);
        }
        if(!is_array($node2)){
            $node2 = $this->getModel()->find($node2, 'left_value,right_value,' . $this->group_field);
        }
        
        return $node1['left_value'] >= $node2['left_value'] &&
            $node1['right_value'] <= $node2['right_value'] &&
            $node1[$this->group_field] == $node2[$this->group_field];
    }

    /**
     * 获取祖谱
     * 若$root为null，则会一直追溯到根节点，否则追溯到root为止
     * $node和$root都可以是：
     *  - 数字:代表分类ID;
     *  - 数组:分类数组（必须包含left_value, right_value和$this->group_field字段）
     * @param int|array $node
     * @param int|array $root
     * @return array
     * @throws Exception
     */
    public function getParentPath($node, $root = null){
        if(!is_array($node)){
            $node = $this->getModel()->find($node, 'left_value,right_value,' . $this->group_field);
        }
        
        if($root && !is_array($root)){
            $root = $this->getModel()->find($root, 'left_value,right_value,' . $this->group_field);
            if($node[$this->group_field] != $root[$this->group_field]){
                throw new Exception('指定节点与根节点不属于同一个归档');
            }
        }
        
        return $this->getModel()->fetchAll(array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'left_value < ' . $node['left_value'],
            'right_value > ' . $node['right_value'],
            'left_value > ?'=>$root ? $root['left_value'] : false,
            'right_value < ?'=>$root ? $root['right_value'] : false,
        ), '*', 'left_value');
    }

    /**
     * 获取指定节点的祖先节点的ID，以一位数组方式返回（包含指定节点ID）
     * 若$root为null，则会一直追溯到根节点，否则追溯到root为止
     * $node和$root都可以是：
     *  - 数字:代表分类ID;
     *  - 数组:分类数组（节约服务器资源，少一次数据库搜索。必须包含left_value, right_value和$this->group_field字段）
     * @param int|array $node
     * @param int|array $root
     * @param bool $with_own 是否包含当前节点返回
     * @return array
     * @throws Exception
     */
    public function getParentIds($node, $root = null, $with_own = true){
        if(!is_array($node)){
            $node = $this->getModel()->find($node, 'left_value,right_value,' . $this->group_field);
        }
        
        if($root && !is_array($root)){
            $root = $this->getModel()->find($root, 'left_value,right_value,' . $this->group_field);
            if($node[$this->group_field] != $root[$this->group_field]){
                throw new Exception('指定节点与根节点不属于同一个归档');
            }
        }
        
        return $this->getModel()->fetchCol('id', array(
            "{$this->group_field} = ?"=>$node[$this->group_field],
            'left_value <' . ($with_own ? '=' : '') . $node['left_value'],
            'right_value >' . ($with_own ? '=' : '') . $node['right_value'],
            'left_value >= ?'=>$root ? $root['left_value'] : false,
            'right_value <= ?'=>$root ? $root['right_value'] : false,
        ), 'left_value');
    }
}