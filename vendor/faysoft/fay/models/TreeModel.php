<?php
namespace fay\models;

use fay\core\db\Expr;
use fay\core\db\Table;
use fay\helpers\ArrayHelper;
use fay\helpers\FieldsHelper;

/**
 * 基于左右值的树操作
 * 该模型针对一张表对应一棵树的数据结构，适用于分类等需求
 * 该模型基于ID或直接传入数组操作，不根据alias进行操作，也不做数据正确性验证
 * **关键字段**：
 *  - id 节点ID
 *  - left_value 左值
 *  - right_value 右值
 *  - parent 父节点ID
 *  - sort 排序值（在平级内部起作用，不同层级之间的排序不受sort影响。）
 */
abstract class TreeModel{
    /**
     * 获取表model
     * @return Table
     */
    abstract protected function getModel();
    
    /**
     * 索引记录
     * @param int $parent
     * @param int $start_num
     * @param array $nodes
     * @return int
     */
    public function buildIndex($parent = 0, $start_num = 1, $nodes = array()){
        $nodes || $nodes = $this->getModel()->fetchAll('parent = ' . $parent, 'id', 'sort, id');
        foreach($nodes as $node){
            $children = $this->getModel()->fetchAll('parent = ' . $node['id'], 'id', 'sort, id');
            if($children){
                //有孩子，先记录左节点，右节点待定
                $left = ++$start_num;
                $start_num = $this->buildIndex($node['id'], $start_num, $children);
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
     * @param int $parent 父节点
     * @param int $sort 排序值
     * @param array $data 其它参数
     * @return int 节点ID
     */
    public function create($parent, $sort, $data){
        if($parent == 0){
            //插入根节点
            $right_node = $this->getModel()->fetchRow(array(
                'parent = 0',
                'sort > ' . $sort,
            ), 'left_value,right_value', 'sort, id');
            
            if($right_node){
                //存在右节点
                $this->getModel()->incr('left_value >= ' . $right_node['left_value'], 'left_value', 2);
                $this->getModel()->incr('right_value >= ' . $right_node['left_value'], 'right_value', 2);
                $node_id = $this->getModel()->insert(array_merge($data, array(
                    'sort'=>$sort,
                    'parent'=>$parent,
                    'left_value'=>$right_node['left_value'],
                    'right_value'=>$right_node['left_value'] + 1,
                )));
            }else{
                //不存在右节点，即在孩子的最后面插入
                $max_right_node = $this->getModel()->fetchRow(array(), 'MAX(right_value) AS max');
                $node_id = $this->getModel()->insert(array_merge($data, array(
                    'sort'=>$sort,
                    'parent'=>$parent,
                    'left_value'=>$max_right_node['max'] + 1,
                    'right_value'=>$max_right_node['max'] + 2,
                )));
            }
        }else{
            $parent_node = $this->getModel()->find($parent, 'left_value,right_value');
            if(!$parent_node){
                throw new \UnexpectedValueException('父节点不存在， 参数异常');
            }
            
            if($parent_node['right_value'] - $parent_node['left_value'] == 1){
                //父节点本身是叶子节点，直接挂载
                $this->getModel()->incr('left_value > ' . $parent_node['left_value'], 'left_value', 2);
                $this->getModel()->incr('right_value > ' . $parent_node['left_value'], 'right_value', 2);
                $node_id = $this->getModel()->insert(array_merge($data, array(
                    'sort'=>$sort,
                    'parent'=>$parent,
                    'left_value'=>$parent_node['left_value'] + 1,
                    'right_value'=>$parent_node['left_value'] + 2,
                )));
            }else{
                //父节点非叶子节点
                //定位新插入节点的排序位置
                $left_node = $this->getModel()->fetchRow(array(
                    'parent = ' . $parent,
                    'sort <= ' . $sort,
                ), 'left_value,right_value', 'sort DESC, id DESC');
                
                if($left_node){
                    //存在左节点
                    $this->getModel()->incr('left_value > ' . $left_node['right_value'], 'left_value', 2);
                    $this->getModel()->incr('right_value > ' . $left_node['right_value'], 'right_value', 2);
                    $node_id = $this->getModel()->insert(array_merge($data, array(
                        'sort'=>$sort,
                        'parent'=>$parent,
                        'left_value'=>$left_node['right_value'] + 1,
                        'right_value'=>$left_node['right_value'] + 2,
                    )));
                }else{
                    //不存在左节点，即在孩子的最前面插入
                    $this->getModel()->incr('left_value > ' . $parent_node['left_value'], 'left_value', 2);
                    $this->getModel()->incr('right_value > ' . $parent_node['left_value'], 'right_value', 2);
                    $node_id = $this->getModel()->insert(array_merge($data, array(
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
     */
    public function update($id, $data, $sort = null, $parent = null){
        $node = $this->getModel()->find($id);
        if($parent !== null){
            if($parent != 0){
                $parent_node = $this->getModel()->find($parent, 'id');
                if(!$parent_node){
                    throw new \UnexpectedValueException("指定父节点[{$parent}]不存在");
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
                'right_value > ' . $node['right_value'],
                'left_value > ' . $node['right_value'],
            ));
            //所有父节点的右节点减去差值
            $this->getModel()->update(array(
                'right_value'=>new Expr('right_value - ' . $diff),
            ), array(
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
                $max_right = $this->getModel()->fetchRow(array(), 'MAX(right_value) AS max');
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
                    'right_value > ' . $parent_node['right_value'],
                    'left_value > ' . $parent_node['right_value'],
                    'id NOT IN ('.implode(',', $branch_ids).')',
                ));
                //所有父节点的右节点加上差值
                $this->getModel()->update(array(
                    'right_value'=>new Expr('right_value + ' . $diff),
                ), array(
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
                    'parent = ' . $parent,
                    'or'=>array(
                        'sort > ' . $sort,
                        'and'=>array(
                            'sort = ' . $sort,
                            'id > ' . $id,
                        ),
                    ),
                    'id != ' . $id,
                ), 'left_value,right_value', 'sort, id');
                if($right_node){
                    //存在右节点
                    //所有后续节点及其子节点加上差值
                    $this->getModel()->update(array(
                        'left_value'=>new Expr('left_value + ' . $diff),
                        'right_value'=>new Expr('right_value + ' . $diff),
                    ), array(
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
                        'right_value > ' . $right_node['right_value'],
                        'left_value < ' . $right_node['left_value'],
                        'id NOT IN ('.implode(',', $branch_ids).')',
                    ));
                    $diff2 = $right_node['left_value'] - $node['left_value'];
                    $this->getModel()->update(array(
                        'left_value'=>new Expr('left_value + ' . $diff2),
                        'right_value'=>new Expr('right_value + ' . $diff2),
                    ), 'id IN ('.implode(',', $branch_ids).')');
                }else{
                    //不存在右节点，插到最后
                    //所有后续节点加上差值
                    $this->getModel()->update(array(
                        'left_value'=>new Expr('left_value + ' . $diff),
                        'right_value'=>new Expr('right_value + ' . $diff),
                    ), array(
                        'right_value > ' . $parent_node['right_value'],
                        'left_value > ' . $parent_node['left_value'],
                        'id NOT IN ('.implode(',', $branch_ids).')',
                    ));
                    //所有父节点的右节点加上差值
                    $this->getModel()->update(array(
                        'right_value'=>new Expr('right_value + ' . $diff),
                    ), array(
                        'right_value >= ' . $parent_node['right_value'],
                        'left_value <= ' . $parent_node['left_value'],
                        'id NOT IN ('.implode(',', $branch_ids).')',
                    ));
                    $diff2 = $parent_node['right_value'] - $node['left_value'];
                    $this->getModel()->update(array(
                        'left_value'=>new Expr('left_value + ' . $diff2),
                        'right_value'=>new Expr('right_value + ' . $diff2),
                    ), 'id IN ('.implode(',', $branch_ids).')');
                }
            }
        }else if($sort !== null && $sort != $node['sort']){
            //没修改parent，只是修改了排序字段
            $this->sort($node, $sort);
        }
    }

    /**
     * 删除一个节点，其子节点将被挂载到父节点
     * @param int $id 节点ID
     * @return bool
     */
    public function remove($id){
        //获取被删除节点
        $node = $this->getModel()->find($id, 'left_value,right_value,parent');
        if(!$node){
            //节点不存在，直接返回false
            return false;
        }
        //所有子节点左右值-1
        $this->getModel()->update(array(
            'left_value'=>new Expr('left_value - 1'),
            'right_value'=>new Expr('right_value - 1'),
        ), array(
            'left_value > ' . $node['left_value'],
            'right_value < ' . $node['right_value'],
        ));
        //所有后续节点左右值-2
        $this->getModel()->update(array(
            'left_value'=>new Expr('left_value - 2'),
            'right_value'=>new Expr('right_value - 2'),
        ), array(
            'left_value > ' . $node['right_value'],
            'right_value > ' . $node['right_value'],
        ));
        //所有父节点
        $this->getModel()->update(array(
            'right_value'=>new Expr('right_value - 2'),
        ), array(
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
        $node = $this->getModel()->find($id, 'left_value,right_value,parent');
        if(!$node){
            //节点不存在，直接返回false
            return false;
        }

        //删除所有树枝节点
        $this->getModel()->delete(array(
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
            'left_value > ' . $node['left_value'],
            'right_value > ' . $node['right_value'],
        ));
        //所有父节点的右节点减去差值
        $this->getModel()->update(array(
            'right_value'=>new Expr('right_value - ' . $diff),
        ), array(
            'left_value < ' . $node['left_value'],
            'right_value > ' . $node['right_value'],
        ));
        return true;
    }

    /**
     * 修改一条记录的sort值，并修改左右值
     * @param mixed $node
     * @param int $sort
     */
    public function sort($node, $sort){
        $sort < 0 && $sort = 0;
        //获取被移动的节点
        if(is_int($node) || is_string($node)){
            $node = $this->getOrFail($node, 'id,left_value,right_value,parent,sort');
        }else if(!isset($node['id']) ||
            !isset($node['left_value']) || !isset($node['right_value']) ||
            !isset($node['parent']) || !isset($node['sort'])
        ){
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($node));
        }
        
        if($node['sort'] == $sort){
            //排序值并未改变
            return;
        }
        $this->getModel()->update(array(
            'sort'=>$sort,
        ), $node['id']);

        //被移动节点原来的左节点（排序值小于该节点 或 ID小于该节点ID）
        $ori_left_node = $this->getModel()->fetchRow(array(
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
            'parent = ' . $node['parent'],
            'or'=>array(
                'sort > ' . $node['sort'],
                'and'=>array(
                    'sort = ' . $node['sort'],
                    'id > ' . $node['id'],
                ),
            ),
        ), 'id,sort', 'sort, id');
        $ori_right_node_sort = isset($ori_right_node['sort']) ? $ori_right_node['sort'] : PHP_INT_MAX;
        if($sort < $ori_left_node_sort || ($sort == $ori_left_node_sort && $node['id'] < $ori_left_node['id'])){//节点左移
            //新位置的右节点
            $right_node = $this->getModel()->fetchRow(array(
                'parent = ' . $node['parent'],
                'or'=>array(
                    'sort > ' . $sort,
                    'and'=>array(
                        'sort = ' . $sort,
                        'id > ' . $node['id'],
                    ),
                ),
                'id != ' . $node['id'],
            ), 'id,left_value', 'sort, id');
            //获取被移动的树枝的所有节点
            $branch_ids = $this->getModel()->fetchCol('id', array(
                'left_value >= ' . $node['left_value'],
                'right_value <= ' . $node['right_value'],
            ));
            //修改移动区间内树枝的左右值
            $diff = $node['right_value'] - $node['left_value'] + 1;
            $this->getModel()->update(array(
                'left_value'=>new Expr('left_value + ' . $diff),
                'right_value'=>new Expr('right_value + ' . $diff),
            ), array(
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
                'left_value >= ' . $node['left_value'],
                'right_value <= ' . $node['right_value'],
            ));
            //修改移动区间内树枝的左右值
            $diff = $node['right_value'] - $node['left_value'] + 1;
            $this->getModel()->update(array(
                'left_value'=>new Expr('left_value - ' . $diff),
                'right_value'=>new Expr('right_value - ' . $diff),
            ), array(
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
     * 根据顶层节点ID返回一棵树，但并不包含顶层节点本身
     * @param int $parent
     * @param string|array $fields
     * @return array
     */
    public function getTree($parent = 0, $fields = '*'){
        $fields = new FieldsHelper($fields, '', $this->getModel()->getFields());
        if(is_int($parent) || is_string($parent)){
            if(!$parent){
                $nodes = $this->getModel()->fetchAll(
                    array(),
                    array_merge($fields->getFields(), array('parent', 'left_value', 'right_value')),
                    'left_value'
                );
                return $this->renderTreeByParent($nodes, 0, $fields->getFields());
            }
            $parent = $this->getOrFail($parent, 'id,left_value,right_value');
        }else if(!isset($parent['id']) || !isset($parent['left_value']) || !isset($parent['right_value'])){
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($parent));
        }
        
        $nodes = $this->getModel()->fetchAll(
            array(
                'left_value > ' . $parent['left_value'],
                'right_value < ' . $parent['right_value'],
            ),
            array_merge($fields->getFields(), array('parent', 'left_value', 'right_value')),
            'left_value'
        );
        return $this->renderTreeByParent(
            $nodes,
            $parent['id'],
            $fields->getFields()
        );
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
     * @param array $fields 过滤字段
     * @return array
     */
    public function renderTreeByParent(&$nodes, $parent = 0, $fields = array()){
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
                $t['children'] = $this->renderTreeByParent($nodes, $t['id'], $fields);
            }else{
                $t['children'] = array();
            }

            //过滤掉未指定返回的索引字段
            foreach(array('parent', 'left_value', 'right_value') as $field){
                if(!in_array($field, $fields)){
                    unset($t[$field]);
                }
            }
        }
        return $tree;
    }

    /**
     * 判断$node1是否为$node2的子节点（是同一节点也返回true）
     * @param mixed $node1
     *  - 若为数字，视为分类ID获取分类；
     *  - 若是数组，必须包含left_value和right_value
     * @param int|string|array $node2
     *  - 若为数字，视为分类ID获取分类；
     *  - 若是数组，必须包含left_value和right_value
     * @return bool
     */
    public function isChild($node1, $node2){
        if(is_int($node1) || is_string($node1)){
            $node1 = $this->getOrFail($node1, 'left_value,right_value');
        }else if(!isset($node1['left_value']) || !isset($node1['right_value'])){
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($node1));
        }
        
        if(is_int($node2) || is_string($node2)){
            $node2 = $this->getOrFail($node2, 'left_value,right_value');
        }else if(!isset($node2['left_value']) || !isset($node2['right_value'])){
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($node2));
        }
        
        return $node1['left_value'] >= $node2['left_value'] &&
            $node1['right_value'] <= $node2['right_value'];
    }

    /**
     * 获取祖谱
     * 若$root为null，则会一直追溯到根节点，否则追溯到root为止
     * $node和$root都可以是：
     *  - 数字:代表分类ID;
     *  - 数组:分类数组（必须包含left_value和right_value字段）
     * @param mixed $node
     * @param string|array $fields
     * @param mixed $root
     * @param bool $with_own
     * @return array
     */
    public function getParents($node, $fields = '*', $root = null, $with_own = true){
        //确定$node
        if(is_int($node) || is_string($node)){
            $node = $this->getOrFail($node, 'left_value,right_value');
        }else if(!isset($node['left_value']) || !isset($node['right_value'])){
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($node));
        }
        
        //确定$root
        if($root){
            if(is_int($root) || is_string($root)){
                $root = $this->getOrFail($root, 'left_value,right_value');
            }else if(!isset($root['left_value']) || !isset($root['right_value'])){
                throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($root));
            }
        }
        
        return $this->getModel()->fetchAll(array(
            'left_value <' . ($with_own ? '= ' : ' ') . $node['left_value'],
            'right_value >' . ($with_own ? '= ' : ' ') . $node['right_value'],
            'left_value > ?'=>$root ? $root['left_value'] : false,
            'right_value < ?'=>$root ? $root['right_value'] : false,
        ), $fields, 'left_value');
    }
    
    /**
     * 获取指定节点的祖先节点的ID，以一位数组方式返回（包含指定节点ID）
     * 若$root为null，则会一直追溯到根节点，否则追溯到root为止
     * $node和$root都可以是：
     *  - 数字:代表分类ID;
     *  - 数组:分类数组（节约服务器资源，少一次数据库搜索。必须包含left_value和right_value字段）
     * @param mixed $node
     * @param mixed $root
     * @param bool $with_own 是否包含当前节点返回
     * @return array
     */
    public function getParentIDs($node, $root = null, $with_own = true){
        return ArrayHelper::column($this->getParents($node, 'id', $root, $with_own), 'id');
    }

    /**
     * 根据父节点ID，获取其所有子节点，返回二维数组（非树形）
     * 若不指定父节点，返回整张表
     * @param mixed $node
     * @param string|array $fields
     * @param string $order
     * @return array
     */
    public function getChildren($node = 0, $fields = '*', $order = 'sort, id'){
        if(!$node){
            return $this->getModel()->fetchAll(array(), $fields, $order);
        }else if(is_int($node) || is_string($node)){
            $node = $this->getOrFail($node, 'left_value,right_value');
        }else if(!isset($node['left_value']) || !isset($node['right_value'])){
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($node));
        }
        
        return $this->getModel()->fetchAll(array(
            'left_value > ' . $node['left_value'],
            'right_value < ' . $node['right_value'],
        ), $fields, $order);
    }

    /**
     * 根据父节点，获取所有子节点的ID，以一维数组方式返回
     * 若不指定$parent，返回整张表
     * @param int|string $parent
     *  - 若为数字，视为分类ID获取分类；
     *  - 若为字符串，视为分类别名获取分类；
     * @return array
     */
    public function getChildrenIDs($parent = 0){
        return ArrayHelper::column($this->getChildren($parent, 'id', 'id'), 'id');
    }

    /**
     * 判断指定分类是否是叶子节点
     * @param mixed $node
     * @return bool
     */
    public function isTerminal($node){
        if(is_int($node) || is_string($node)){
            $node = $this->getOrFail($node, 'left_value,right_value');
        }else if(!isset($node['parent'])){
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($node));
        }
        
        return ($node['right_value'] - $node['left_value']) == 1;
    }

    /**
     * @see CategoryService::isTerminal()
     * @param mixed $node
     * @return bool
     */
    public function hasChildren($node){
        return $this->isTerminal($node);
    }

    /**
     * 获取指定分类的平级分类
     * @param mixed $node
     *  - 若为数字，视为分类ID获取分类
     *  - 若是数组，必须包含parent字段
     * @param string|array $fields
     * @param string $order
     * @return array
     */
    public function getSibling($node, $fields = '*', $order = 'sort, id'){
        if(is_int($node) || is_string($node)){
            $node = $this->getOrFail($node, 'parent');
        }else if(!isset($node['parent'])){
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($node));
        }
        
        if(!$node){
            return array();
        }

        return $this->getModel()->fetchAll(array(
            'parent = ?'=>$node['parent'],
        ), $fields, $order);
    }

    /**
     * 根据父节点，获取其下一级节点
     * @param int $node 父节点ID或别名
     * @param string|array $fields 返回字段
     * @param string $order 排序规则
     * @return array
     */
    public function getNextLevel($node, $fields = '*', $order = 'sort, id'){
        return $this->getModel()->fetchAll(array(
            'parent = ?'=>$node,
        ), $fields, $order);
    }

    /**
     * 获取一个或多个分类。
     * @param int|string $node
     * @param string|array $fields
     * @param mixed $root 若指定root，则只搜索root下的分类
     * @return array|bool
     */
    public function get($node, $fields = '*', $root = null){
        if($root && (!isset($root['left_value']) || !isset($root['right_value']))){
            //root信息不足，尝试通过get()方法获取
            $root = $this->getOrFail($root, 'left_value,right_value');
        }

        $conditions = array(
            'id = ?'=>$node,
        );
        if($root){
            $conditions['left_value >= ?'] = $root['left_value'];
            $conditions['right_value <= ?'] = $root['right_value'];
        }
        return $this->getModel()->fetchRow($conditions, $fields);
    }

    /**
     * 根据id搜索记录，若未搜到结果，抛出异常
     * @param int|string $node
     * @param string|array $fields
     * @param mixed $root 若指定root，则只搜索root下的分类
     * @return array
     */
    public function getOrFail($node, $fields = '*', $root = null){
        $result = $this->get($node, $fields, $root);
        if(!$result){
            throw new \RuntimeException("指定节点[{$node}]不存在");
        }

        return $result;
    }
}