<?php
namespace cms\services;

use cms\models\tables\MenusTable;
use fay\core\Loader;
use fay\helpers\FieldsHelper;
use fay\helpers\StringHelper;
use fay\models\TreeModel;

class MenuService extends TreeModel{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    protected function getModel(){
        return MenusTable::model();
    }

    /**
     * 获取一个菜单项
     * @param int|string $menu
     *  - 若为数字，视为分类ID获取菜单
     *  - 若为字符串，视为分类别名获取菜单
     * @param string $fields
     * @param null $root
     * @return array|bool
     */
    public function get($menu, $fields = 'id,parent,alias,title,sort', $root = null){
        $fields = new FieldsHelper($fields, 'category', MenusTable::model()->getFields());

        if($root){
            if(is_int($root) || is_string($root)){
                $root = $this->getOrFail($root, 'left_value,right_value');
            }
            if(!isset($root['left_value']) || !isset($root['right_value'])){
                throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($root));
            }
        }

        $conditions = array();
        if(StringHelper::isInt($menu)){
            $conditions['id = ?'] = $menu;
        }else if(is_string($menu)){
            $conditions['alias = ?'] = $menu;
        }else{
            throw new \InvalidArgumentException('无法识别的节点格式: ' . serialize($menu));
        }

        if($root){
            $conditions['left_value >= ?'] = $root['left_value'];
            $conditions['right_value <= ?'] = $root['right_value'];
        }
        
        return MenusTable::model()->fetchRow($conditions, $fields->getFields());
    }
    
    /**
     * 根据父节点，返回导航树
     * 若不指定父节点或指定为null，返回用户自定义菜单
     * @param int|string|null $parent 父节点ID或别名
     *  - 若为数字，视为ID获取菜单；
     *  - 若为字符串，视为别名获取菜单；
     * @param bool $real_link 返回渲染后的真实url
     * @param bool $only_enabled 若为true，仅返回启用的菜单集
     * @return array
     */
    public function getTree($parent = null, $real_link = true, $only_enabled = true){
        if($parent === null){
            $parent = MenusTable::ITEM_USER_MENU;
        }

        $menu = parent::getTree($parent);
        //无法在搜索树的时候就删除关闭的菜单，在这里再循环移除
        if($only_enabled){
            $menu = $this->removeDisabledItems($menu);
        }

        if($real_link){
            return $this->renderLink($menu);
        }else{
            return $menu;
        }
    }
    
    /**
     * 递归移除未启用的菜单项（若父节点未启用，其子节点会一并被移除）
     * @param array $menu
     * @return array
     */
    protected function removeDisabledItems(&$menu){
        foreach($menu as $k => &$m){
            if(!$m['enabled']){
                unset($menu[$k]);
            }else{
                if(!empty($m['children'])){
                    //子节点
                    $children = $this->removeDisabledItems($m['children']);
                    if($children){
                        $m['children'] = $children;
                    }else{
                        //子节点全部不启用，删除children项
                        unset($m['children']);
                    }
                }
            }
        }
        return $menu;
    }
    
    /**
     * 将link替换为真实url
     * @param array $menus
     * @return array
     */
    protected function renderLink($menus){
        foreach($menus as &$m){
            $m['link'] = str_replace('{$base_url}', \F::config()->get('base_url'), $m['link']);
            if(isset($m['children'])){
                $m['children'] = $this->renderLink($m['children']);
            }
        }
        return $menus;
    }
}