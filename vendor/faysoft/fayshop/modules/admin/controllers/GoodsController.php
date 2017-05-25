<?php
namespace fayshop\modules\admin\controllers;

use cms\library\AdminController;
use fayshop\models\tables\ShopGoodsTable;
use fayshop\models\tables\ShopGoodsFilesTable;
use fayshop\models\tables\ShopGoodsCatPropValuesTable;
use fayshop\models\tables\ShopGoodsPropValuesTable;
use fayshop\models\tables\ShopGoodsSkusTable;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\CategoriesTable;
use fayshop\models\tables\ShopGoodsCatPropsTable;
use fay\core\Sql;
use fay\common\ListView;
use cms\services\CategoryService;
use fay\helpers\DateHelper;
use cms\services\shop\ShopGoodsService;
use fay\core\Response;
use fay\helpers\HtmlHelper;
use cms\services\FlashService;
use cms\services\SettingService;
use fay\core\HttpException;

class GoodsController extends AdminController{
    /**
     * box列表
     */
    public $boxes = array(
        array('name'=>'sku', 'title'=>'SKU'),
        array('name'=>'guide', 'title'=>'导购'),
        array('name'=>'shipping', 'title'=>'物流参数'),
        array('name'=>'publish_time', 'title'=>'发布时间'),
        array('name'=>'thumbnail', 'title'=>'缩略图'),
        array('name'=>'views', 'title'=>'浏览量'),
        array('name'=>'seo', 'title'=>'SEO优化'),
        array('name'=>'files', 'title'=>'画廊'),
        array('name'=>'props', 'title'=>'商品属性'),
        array('name'=>'sale_info', 'title'=>'销售属性'),
    );
    
    /**
     * 默认box排序
     */
    public $default_box_sort = array(
        'side'=>array(
            'publish-time', 'guide', 'shipping', 'thumbnail', 'views',
        ),
        'normal'=>array(
            'sku', 'props', 'files', 'seo'
        ),
    );
    
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'goods';
        if(!$this->input->isAjaxRequest()){
            FlashService::set('这个模块只是做着玩的，并没有实现购物功能。', 'warning');
        }
    }
    
    public function cat(){
        $this->layout->current_directory = 'goods';
        
        $this->layout->_help_panel = '_help';
        
        $this->layout->subtitle = '商品分类';
        $this->view->cats = CategoryService::service()->getTree('_system_goods');
        $root_node = CategoryService::service()->getByAlias('_system_goods', 'id');
        $this->view->root = $root_node['id'];
        
        if($this->checkPermission('fayshop/admin/goods/cat-create')){
            $this->layout->sublink = array(
                'uri'=>'#create-cat-dialog',
                'text'=>'添加商品分类',
                'html_options'=>array(
                    'class'=>'create-cat-link',
                    'data-title'=>'商品',
                    'data-id'=>$root_node['id'],
                ),
            );
        }
        $this->view->render();
    }
    
    public function create(){
        //获取分类
        $cat = CategoryService::service()->get($this->input->get('cat_id', 'intval'), 'id,title');
        
        if(!$cat){
            throw new HttpException('未指定商品分类或指定分类不存在');
        }
        
        $this->layout->subtitle = '添加商品 - 所属分类：'.$cat['title'];
        $this->layout->sublink = array(
            'uri'=>array('fayshop/admin/goods/cat'),
            'text'=>'商品分类',
        );
        
        $this->form()->setModel(ShopGoodsTable::model());
        if($this->input->post()){
            //插入goods表
            $data = ShopGoodsTable::model()->fillData($this->input->post());
            $data['create_time'] = $this->current_time;
            $data['update_time'] = $this->current_time;
            $data['user_id'] = $this->current_user;
            $data['cat_id'] = $cat['id'];
            empty($data['sub_stock']) && $data['sub_stock'] = ShopGoodsTable::SUB_STOCK_PAY;
            empty($data['publish_time']) ? $data['publish_time'] = $this->current_time : $data['publish_time'] = strtotime($data['publish_time']);
            
            $goods_id = ShopGoodsTable::model()->insert($data);
            
            //设置gallery
            $description = $this->input->post('description');
            $files = $this->input->post('files', 'intval', array());
            $i = 0;
            foreach($files as $f){
                $i++;
                ShopGoodsFilesTable::model()->insert(array(
                    'goods_id'=>$goods_id,
                    'file_id'=>$f,
                    'description'=>$description[$f],
                    'create_time'=>$this->current_time,
                    'sort'=>$i,
                ));
            }
            
            //属性别名
            $cp_alias = $this->input->post('cp_alias');
            //普通属性
            foreach($this->input->post('cp', null, array()) as $k=>$v){
                $k = intval($k);
                if(is_array($v)){//多选属性
                    foreach($v as $v2){
                        $v2 = intval($v2);
                        if(!empty($cp_alias[$k][$v2])){
                            //若有属性值传过来，则以输入值作为属性值
                            $prop_value_alias = $cp_alias[$k][$v2];
                        }else{
                            //若没有属性值传过来，则以默认值作为属性值
                            $cat_prop_value = ShopGoodsCatPropValuesTable::model()->fetchRow(array(
                                'id = ?'=>$v2,
                            ));
                            $prop_value_alias = $cat_prop_value['title'];
                        }
                        ShopGoodsPropValuesTable::model()->insert(array(
                            'goods_id'=>$goods_id,
                            'prop_id'=>$k,
                            'prop_value_id'=>$v2,
                            'prop_value_alias'=>$prop_value_alias,
                        ));
                    }
                }else{//单选属性或输入属性
                    $v = intval($v);
                    if($v != 0){//属性值id为0，则意味着这个属性是{手工录入属性}
                        if(!empty($cp_alias[$k][$v])){
                            //若有属性值传过来，则以输入值作为属性值
                            $prop_value_alias = $cp_alias[$k][$v];
                        }else{
                            //若没有属性值传过来，则以默认值作为属性值
                            $cat_prop_value = ShopGoodsCatPropValuesTable::model()->fetchRow(array(
                                'id = ?'=>$v,
                            ));
                            $prop_value_alias = $cat_prop_value['title'];
                        }
                        ShopGoodsPropValuesTable::model()->insert(array(
                            'goods_id'=>$goods_id,
                            'prop_id'=>$k,
                            'prop_value_id'=>$v,
                            'prop_value_alias'=>$prop_value_alias,
                        ));
                    }else{
                        if(!empty($cp_alias[$k][$v])){
                            //若有属性值传过来，则设置属性值
                            //若没有，则跳过此属性
                            $prop_value_alias = $cp_alias[$k][$v];
                            ShopGoodsPropValuesTable::model()->insert(array(
                                'goods_id'=>$goods_id,
                                'prop_id'=>$k,
                                'prop_value_id'=>$v,
                                'prop_value_alias'=>$prop_value_alias,
                            ));
                        }
                    }
                }
            }
            
            //销售属性
            foreach($this->input->post('cp_sale', null, array()) as $k=>$v){
                //销售属性必是多选属性，且必然设置了alias
                foreach($v as $v2){
                    $v2 = intval($v2);
                    ShopGoodsPropValuesTable::model()->insert(array(
                        'goods_id'=>$goods_id,
                        'prop_id'=>$k,
                        'prop_value_id'=>$v2,
                        'prop_value_alias'=>$cp_alias[$k][$v2],
                    ));
                }
            }
            
            //sku
            $prices = $this->input->post('prices', 'floatval', array());
            $quantities = $this->input->post('quantities', 'intval', array());
            $tsces = $this->input->post('tsces', array());
            foreach($prices as $k => $p){
                ShopGoodsSkusTable::model()->insert(array(
                    'goods_id'=>$goods_id,
                    'sku_key'=>$k,
                    'price'=>$p,
                    'quantity'=>$quantities[$k],
                    'tsces'=>$tsces[$k],
                ));
            }

            $this->actionlog(ActionlogsTable::TYPE_GOODS, '添加一个商品', $goods_id);
        }
        $this->form()->setData($this->input->post());
        
        $parentIds = CategoryService::service()->getParentIds($cat['id']);
        //props
        $props = ShopGoodsCatPropsTable::model()->fetchAll(array(
            'cat_id IN ('.implode(',', $parentIds).')',
            'delete_time = 0',
        ), '!deleted', 'sort, id');
        
        //prop_values
        $prop_values = ShopGoodsCatPropValuesTable::model()->fetchAll(array(
            'cat_id IN ('.implode(',', $parentIds).')',
            'delete_time = 0',
        ), '!deleted', 'prop_id, sort');
        
        //合并属性和属性值
        foreach($props as &$p){
            $p['prop_values'] = array();
            foreach($prop_values as $pv){
                if($pv['prop_id'] != $p['id'])continue;
                $p['prop_values'][] = $pv;
            }
        }
        
        $this->view->props = $props;
        
        //box排序
        $_box_sort_settings = SettingService::service()->get('admin_goods_box_sort');
        $_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
        $this->view->_box_sort_settings = $_box_sort_settings;
        
        //页面设置
        $_setting_key = 'admin_goods_boxes';
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_edit', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        $this->view->render();
    }
    
    public function index(){
        $this->layout->subtitle = '商品';
        
        $this->layout->sublink = array(
            'uri'=>array('fayshop/admin/goods/cat'),
            'text'=>'添加商品',
        );
        
        //页面设置
        $_settings = $this->settingForm('admin_goods_index', '_setting_index', array(
            'cols'=>array('thumbnail', 'category', 'price', 'sales', 'status', 'create_time', 'sort'),
            'display_name'=>'username',
            'display_time'=>'short',
            'page_size'=>10,
        ));

        $this->form()->setData($this->input->get());
        
        $sql = new Sql();
        $sql->from(array('g'=>'shop_goods'))
            ->joinLeft(array('c'=>'categories'), 'g.cat_id = c.id', 'title AS cat_title')
            ->joinLeft(array('gc'=>'shop_goods_counter'), 'g.id = gc.goods_id', '*')
        ;
        $sql->where('g.delete_time = 0');
        if($this->input->get('start_time')){
            $sql->where(array("g.{$this->input->get('time_field')} > ?"=>$this->input->get('start_time','strtotime')));
        }
        if($this->input->get('end_time')){
            $sql->where(array("g.{$this->input->get('time_field')} < ?"=>$this->input->get('end_time','strtotime')));
        }
        if($this->input->get('cat_id')){
            $sql->where(array('g.cat_id = ?'=>$this->input->get('cat_id', 'intval')));
        }
        if($this->input->get('status')){
            $sql->where(array('g.status = ?'=>$this->input->get('status', 'intval')));
        }
        if($this->input->get('keywords')){
            $sql->where(array("g.{$this->input->get('field')} like ?"=>'%'.$this->input->get('keywords').'%'));
        }
        
        if(in_array('user', $_settings['cols'])){
            $sql->joinLeft(array('u'=>'users'), 'g.user_id = u.id', 'username,nickname,realname');
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size', 20),
            'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
        ));

        $this->view->cats = CategoryService::service()->getTree('_system_goods');
        $this->view->render();
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑商品';
        
        $goods_id = $this->input->get('id', 'intval');
        
        //这里获取enabled_boxes是为了更新商品的时候用
        //由于box可能被hook改掉，后面还会再获取一次enabled_boxes
        $_setting_key = 'admin_goods_boxes';
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        
        if($this->input->post()){
            //更新goods表
            $data = ShopGoodsTable::model()->fillData($this->input->post());
            $data['update_time'] = $this->current_time;
            
            if(in_array('publish_time', $enabled_boxes)){
                if(empty($data['publish_time'])){
                    $data['publish_time'] = $this->current_time;
                    $this->form()->setData(array(
                        'publish_time'=>date('Y-m-d H:i:s', $data['publish_time']),
                    ));
                }else{
                    $data['publish_time'] = strtotime($data['publish_time']);
                }
            }
            
            ShopGoodsTable::model()->update($data, $goods_id);
            
            //设置gallery
            $description = $this->input->post('description');
            $files = $this->input->post('files', 'intval', array());
            //删除已被删除的图片
            if($files){
                ShopGoodsFilesTable::model()->delete(array(
                    'goods_id = ?'=>$goods_id,
                    'file_id NOT IN ('.implode(',', $files).')',
                ));
            }else{
                ShopGoodsFilesTable::model()->delete(array(
                    'goods_id = ?'=>$goods_id,
                ));
            }
            //获取已存在的图片
            $old_files_ids = ShopGoodsFilesTable::model()->fetchCol('file_id', array(
                'goods_id = ?'=>$goods_id,
            ));
            $i = 0;
            foreach($files as $f){
                $i++;
                if(in_array($f, $old_files_ids)){
                    ShopGoodsFilesTable::model()->update(array(
                        'description'=>$description[$f],
                        'sort'=>$i,
                    ), array(
                        'goods_id = ?'=>$goods_id,
                        'file_id = ?'=>$f,
                    ));
                }else{
                    ShopGoodsFilesTable::model()->insert(array(
                        'file_id'=>$f,
                        'goods_id'=>$goods_id,
                        'description'=>$description[$f],
                        'sort'=>$i,
                        'create_time'=>$this->current_time,
                    ));
                }
            }
            
            //属性别名
            $cp_alias = $this->input->post('cp_alias');
            
            $new_prop_values = array();//记录所有属性（普通属性+销售属性）
            $old_prop_values = ShopGoodsPropValuesTable::model()->fetchCol('prop_value_id', array(
                'goods_id = ?'=>$goods_id,
            ));//所有原属性（普通属性+销售属性）
            //普通属性
            foreach($this->input->post('cp', null, array()) as $k=>$v){
                $k = intval($k);
                if(is_array($v)){//多选属性
                    foreach($v as $v2){
                        $v2 = intval($v2);
                        $new_prop_values[] = $v2;
                        if(!empty($cp_alias[$k][$v2])){
                            //若有属性值传过来，则以输入值作为属性值
                            $prop_value_alias = $cp_alias[$k][$v2];
                        }else{
                            //若没有属性值传过来，则以默认值作为属性值
                            $cat_prop_value = ShopGoodsCatPropValuesTable::model()->fetchRow(array(
                                'id = ?'=>$v2,
                            ));
                            $prop_value_alias = $cat_prop_value['title'];
                        }
                        if(in_array($v2, $old_prop_values)){
                            ShopGoodsPropValuesTable::model()->update(array(
                                'prop_value_alias'=>$prop_value_alias,
                            ), array(
                                'goods_id = ?'=>$goods_id,
                                'prop_value_id = ?'=>$v2,
                            ));
                        }else{
                            ShopGoodsPropValuesTable::model()->insert(array(
                                'goods_id'=>$goods_id,
                                'prop_id'=>$k,
                                'prop_value_id'=>$v2,
                                'prop_value_alias'=>$prop_value_alias,
                            ));
                        }
                    }
                }else{//单选属性或输入属性
                    $v = intval($v);
                    $new_prop_values[] = $v;
                    if($v != 0){//属性值id为0，则意味着这个属性是{手工录入属性}
                        if(!empty($cp_alias[$k][$v])){
                            //若有属性值传过来，则以输入值作为属性值
                            $prop_value_alias = $cp_alias[$k][$v];
                        }else{
                            //若没有属性值传过来，则以默认值作为属性值
                            $cat_prop_value = ShopGoodsCatPropValuesTable::model()->fetchRow(array(
                                'id = ?'=>$v,
                            ));
                            $prop_value_alias = $cat_prop_value['title'];
                        }
                        if(in_array($v, $old_prop_values)){
                            //只改了别名
                            ShopGoodsPropValuesTable::model()->update(array(
                                'prop_value_alias'=>$prop_value_alias,
                            ), array(
                                'goods_id = ?'=>$goods_id,
                                'prop_value_id = ?'=>$v,
                            ));
                        }else{
                            if(ShopGoodsPropValuesTable::model()->fetchRow(array(
                                'goods_id = ?'=>$goods_id,
                                'prop_id = ?'=>$k,
                            ))){//单值属性若已存在，直接更新，不重新插入
                                ShopGoodsPropValuesTable::model()->update(array(
                                    'prop_value_alias'=>$prop_value_alias,
                                    'prop_value_id'=>$v,
                                ), array(
                                    'goods_id = ?'=>$goods_id,
                                    'prop_id = ?'=>$k,
                                ));
                            }else{
                                ShopGoodsPropValuesTable::model()->insert(array(
                                    'goods_id'=>$goods_id,
                                    'prop_id'=>$k,
                                    'prop_value_id'=>$v,
                                    'prop_value_alias'=>$prop_value_alias,
                                ));
                            }
                        }
                    }else{
                        if(!empty($cp_alias[$k][$v])){
                            //若有属性值传过来，则设置属性值
                            //若没有，则跳过此属性
                            $prop_value_alias = $cp_alias[$k][$v];
                            if(in_array($v, $old_prop_values)){
                                ShopGoodsPropValuesTable::model()->update(array(
                                    'prop_value_alias'=>$prop_value_alias,
                                ), array(
                                    'goods_id = ?'=>$goods_id,
                                    'prop_value_id = ?'=>$v,
                                ));
                            }else{
                                if(ShopGoodsPropValuesTable::model()->fetchRow(array(
                                    'goods_id = ?'=>$goods_id,
                                    'prop_id = ?'=>$k,
                                ))){//单值属性若已存在，直接更新，不重新插入
                                    ShopGoodsPropValuesTable::model()->update(array(
                                        'prop_value_alias'=>$prop_value_alias,
                                        'prop_value_id'=>$v,
                                    ), array(
                                        'goods_id = ?'=>$goods_id,
                                        'prop_id = ?'=>$k,
                                    ));
                                }else{
                                    ShopGoodsPropValuesTable::model()->insert(array(
                                        'goods_id'=>$goods_id,
                                        'prop_id'=>$k,
                                        'prop_value_id'=>$v,
                                        'prop_value_alias'=>$prop_value_alias,
                                    ));
                                }
                            }
                        }
                    }
                }
            }
            
            //销售属性
            foreach($this->input->post('cp_sale', null, array()) as $k=>$v){
                //销售属性必是多选属性，且必然设置了alias
                foreach($v as $v2){
                    $v2 = intval($v2);
                    $new_prop_values[] = $v2;
                    if(in_array($v2, $old_prop_values)){
                        ShopGoodsPropValuesTable::model()->update(array(
                            'prop_value_alias'=>$cp_alias[$k][$v2],
                        ), array(
                            'goods_id = ?'=>$goods_id,
                            'prop_value_id = ?'=>$v2,
                        ));
                    }else{
                        ShopGoodsPropValuesTable::model()->insert(array(
                            'goods_id'=>$goods_id,
                            'prop_id'=>$k,
                            'prop_value_id'=>$v2,
                            'prop_value_alias'=>$cp_alias[$k][$v2],
                        ));
                    }
                }
            }
            //删除已被删除的所有属性（普通属性+销售属性）
            ShopGoodsPropValuesTable::model()->delete(array(
                'goods_id = ?'=>$goods_id,
                'prop_value_id NOT IN ('.implode(',', $new_prop_values).')',
            ));
                
            //sku
            $prices = $this->input->post('prices', 'floatval', array());
            $quantities = $this->input->post('quantities', 'intval', array());
            $tsces = $this->input->post('tsces', array());
            $old_skus = ShopGoodsSkusTable::model()->fetchCol('sku_key', array(
                'goods_id = ?'=>$goods_id,
            ));
            //删除已被删除的sku
            $new_sku_keys = array_keys($prices);
            ShopGoodsSkusTable::model()->delete(array(
                'goods_id = ?'=>$goods_id,
                "sku_key NOT IN ('".implode("','", $new_sku_keys)."')"
            ));
            foreach($prices as $k => $p){
                if(in_array($k, $old_skus)){
                    ShopGoodsSkusTable::model()->update(array(
                        'price'=>$p,
                        'quantity'=>$quantities[$k],
                        'tsces'=>$tsces[$k],
                    ), array(
                        'goods_id = ?'=>$goods_id,
                        'sku_key = ?'=>$k,
                    ));
                }else{
                    ShopGoodsSkusTable::model()->insert(array(
                        'goods_id'=>$goods_id,
                        'sku_key'=>$k,
                        'price'=>$p,
                        'quantity'=>$quantities[$k],
                        'tsces'=>$tsces[$k],
                    ));
                }
            }
            
            $this->actionlog(ActionlogsTable::TYPE_GOODS, '编辑一个商品', $goods_id);
            Response::notify('success', '一个商品被编辑', false);
        }
        
        $goods = ShopGoodsService::service()->get($goods_id);
        //做一些格式化处理
        $goods['publish_time'] = DateHelper::format($goods['publish_time']);
        
        //获取分类
        $cat = CategoriesTable::model()->find($goods['cat_id'], 'id,title');
        
        $parentIds = CategoryService::service()->getParentIds($cat['id']);
        //props
        $props = ShopGoodsCatPropsTable::model()->fetchAll(array(
            'cat_id IN ('.implode(',', $parentIds).')',
            'delete_time = 0',
        ), '!deleted', 'sort, id');
        
        //prop_values
        $prop_values = ShopGoodsCatPropValuesTable::model()->fetchAll(array(
            'cat_id IN ('.implode(',', $parentIds).')',
            'delete_time = 0',
        ), '!deleted', 'prop_id, sort');
        
        //合并属性和属性值
        foreach($props as &$p){
            $p['prop_values'] = array();
            foreach($prop_values as $pv){
                if($pv['prop_id'] != $p['id'])continue;
                $p['prop_values'][] = $pv;
            }
        }
        
        $this->view->props = $props;
        
        //可配置信息
        $_box_sort_settings = SettingService::service()->get('admin_goods_box_sort');
        $_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
        $this->view->_box_sort_settings = $_box_sort_settings;
        
        //页面设置
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_edit', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        $this->view->files = $goods['files'];
        $this->view->goods = $goods;
        $this->form()->setData($goods);

        $this->view->render();
    }
    
    public function delete(){
        $goods_id = $this->input->get('id', 'intval');
        ShopGoodsTable::model()->update(array(
            'delete_time'=>$this->current_time,
        ), $goods_id);
        $this->actionlog(ActionlogsTable::TYPE_GOODS, '软删除一个商品', $goods_id);
        
        Response::notify('success', array(
            'message'=>'一个商品被移入回收站 - '.HtmlHelper::link('撤销', array('fayshop/admin/goods/undelete', array(
                'id'=>$goods_id,
            ))),
            'id'=>$goods_id
        ));
    }
    
    public function undelete(){
        $goods_id = $this->input->get('id', 'intval');
        ShopGoodsTable::model()->update(array(
            'delete_time'=>0
        ), array('id = ?'=>$goods_id));
        $this->actionlog(ActionlogsTable::TYPE_GOODS, '将商品移出回收站', $goods_id);
        
        Response::notify('success', array(
            'message'=>'一个商品被还原',
            'id'=>$goods_id
        ));
    }
    
    public function remove(){

    }
    
    public function setIsNew(){
        ShopGoodsTable::model()->update(array(
            'is_new'=>$this->input->get('is_new', 'intval'),
        ), $this->input->get('id', 'intval'));
        
        $goods = ShopGoodsTable::model()->find($this->input->get('id', 'intval'), 'is_new');
        Response::notify('success', array(
            'message'=>'',
            'data'=>array(
                'is_new'=>$goods['is_new'],
            ),
        ));
    }
    
    public function setIsHot(){
        ShopGoodsTable::model()->update(array(
            'is_hot'=>$this->input->get('is_hot', 'intval'),
        ), $this->input->get('id', 'intval'));
        
        $goods = ShopGoodsTable::model()->find($this->input->get('id', 'intval'), 'is_hot');
        Response::notify('success', array(
            'message'=>'',
            'data'=>array(
                'is_hot'=>$goods['is_hot'],
            ),
        ));
    }
    
    public function setSort(){
        ShopGoodsTable::model()->update(array(
            'sort'=>$this->input->get('sort', 'intval'),
        ), $this->input->get('id', 'intval'));
        $goods = ShopGoodsTable::model()->find($this->input->get('id', 'intval'), 'sort');
        
        Response::notify('success', array(
            'message'=>'一个商品的排序值被编辑',
            'data'=>array(
                'sort'=>$goods['sort'],
            ),
        ));
    }
}