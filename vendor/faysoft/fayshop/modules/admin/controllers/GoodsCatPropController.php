<?php
namespace fayshop\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\CategoriesTable;
use cms\services\CategoryService;
use fay\common\ListView;
use fay\core\exceptions\NotFoundHttpException;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\HtmlHelper;
use fayshop\models\tables\ShopGoodsCatPropsTable;
use fayshop\models\tables\ShopGoodsCatPropValuesTable;

/**
 * 商品属性
 */
class GoodsCatPropController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'goods';
    }
    
    public function index(){
        $this->layout->sublink = array(
            'uri'=>array('fayshop/admin/goods/cat'),
            'text'=>'返回商品分类',
        );
        
        $cat_id = $this->input->get('cat_id', 'intval');
        $cat = CategoryService::service()->get($cat_id, 'id,title');
        if(!$cat){
            throw new NotFoundHttpException('指定商品分类不存在');
        }
        
        $this->form()->setModel(ShopGoodsCatPropsTable::model());
        
        $this->layout->subtitle = HtmlHelper::encode($cat['title']) . ' - 分类属性';
        
        $this->_setListview($cat_id);
        return $this->view->render();
    }
    
    public function create(){
        if($this->input->post()){
            $is_sale_prop = $this->input->post('is_sale_prop', 'intval', 0);
            if($is_sale_prop){
                $required = 1;
            }else{
                $required = $this->input->post('required', 'intval', 0);
            }
            $prop_id = ShopGoodsCatPropsTable::model()->insert(array(
                'alias'=>$this->input->post('alias', 'trim'),
                'cat_id'=>$this->input->post('cat_id', 'intval'),
                'type'=>$this->input->post('type', 'intval'),
                'required'=>$required,
                'title'=>$this->input->post('title'),
                'is_sale_prop'=>$is_sale_prop,
                'is_input_prop'=>$this->input->post('is_input_prop', 'intval', 0),
                'sort'=>$this->input->post('sort', 'intval', 0),
            ));
            
            //设置属性值
            if($this->input->post('type', 'intval') != ShopGoodsCatPropsTable::TYPE_INPUT){//手工录入属性没有属性值
                $prop_values = $this->input->post('prop_values', array());
                $i = 0;
                foreach($prop_values as $pv){
                    $i++;
                    ShopGoodsCatPropValuesTable::model()->insert(array(
                        'prop_id'=>$prop_id,
                        'title'=>$pv,
                        'sort'=>$i,
                        'cat_id'=>$this->input->request('cat_id', 'intval'),
                    ));
                }
            }
            $this->actionlog(ActionlogsTable::TYPE_GOODS_PROP, '添加一个商品属性', $prop_id);
            
            Response::notify('success', '商品属性添加成功');
        }else{
            Response::notify('error', '无数据被提交');
        }
    }
    
    public function delete(){
        $prop_id = $this->input->get('id', 'intval');
        //仅将属性的删除字段置为1，而不改动属性值表，否则无法还原
        ShopGoodsCatPropsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $prop_id);

        $this->actionlog(ActionlogsTable::TYPE_GOODS_PROP, '软删除一个商品属性', $prop_id);

        Response::notify('success', '一个商品属性被移入回收站 - '.HtmlHelper::link('撤销', array('fayshop/admin/goods-cat-prop/undelete', array(
            'id'=>$prop_id,
        ))));
    }
    
    public function undelete(){
        $prop_id = $this->input->get('id', 'intval');
        ShopGoodsCatPropsTable::model()->update(array(
            'delete_time'=>0,
        ), $prop_id);
        
        $this->actionlog(ActionlogsTable::TYPE_GOODS_PROP, '还原一个商品属性', $prop_id);
        
        Response::notify('success', '一个商品属性被还原');
    }
    
    public function remove(){
        $prop_id = $this->input->get('id', 'intval');
        ShopGoodsCatPropsTable::model()->delete($prop_id);
        ShopGoodsCatPropValuesTable::model()->delete(array(
            'prop_id = ?'=>$prop_id,
        ));

        $this->actionlog(ActionlogsTable::TYPE_GOODS_PROP, '永久删除一个商品属性', $prop_id);
        
        Response::notify('success', '永久删除一个商品属性');
    }
    
    public function edit(){
        $prop_id = $this->input->get('id', 'intval');
        if($this->input->post()){
            $is_sale_prop = $this->input->post('is_sale_prop', 'intval', 0);
            if($is_sale_prop){
                $required = 1;
            }else{
                $required = $this->input->post('required', 'intval', 0);
            }
            ShopGoodsCatPropsTable::model()->update(array(
                'alias'=>$this->input->post('alias', 'trim'),
                'type'=>$this->input->post('type', 'intval'),
                'required'=>$required,
                'title'=>$this->input->post('title'),
                'is_sale_prop'=>$is_sale_prop,
                'is_input_prop'=>$this->input->post('is_input_prop', 'intval', 0),
                'sort'=>$this->input->post('sort', 'intval', 0),
            ), $prop_id);

            //删除原有属性值
            $old_prop_value_ids = $this->input->post('old_prop_value_ids', 'intval', array('-1'));
            ShopGoodsCatPropValuesTable::model()->update(array(
                'delete_time'=>\F::app()->current_time,
            ),array(
                'prop_id = ?'=>$prop_id,
                'id NOT IN ('.implode(',', $old_prop_value_ids).')',
            ));
            //设置属性值
            if($this->input->post('type', 'intval') != ShopGoodsCatPropsTable::TYPE_INPUT){//手工录入属性没有属性值
                $prop_values = $this->input->post('prop_values', 'trim', array());
                $i = 0;
                foreach($prop_values as $key => $pv){
                    $i++;
                    if(in_array($key, $old_prop_value_ids)){
                        ShopGoodsCatPropValuesTable::model()->update(array(
                            'title'=>$pv,
                            'sort'=>$i,
                        ), array(
                            'id = ?'=>$key,
                        ));
                    }else{
                        ShopGoodsCatPropValuesTable::model()->insert(array(
                            'prop_id'=>$prop_id,
                            'title'=>$pv,
                            'sort'=>$i,
                            'cat_id'=>$this->input->request('cat_id', 'intval'),
                        ));
                    }
                }
            }
            $this->actionlog(ActionlogsTable::TYPE_GOODS_PROP, '编辑一个商品属性', $prop_id);
        }
        $prop = ShopGoodsCatPropsTable::model()->find($prop_id);
        $this->view->prop = $prop;
        $cat = CategoriesTable::model()->find($prop['cat_id'], 'id,title');
        $this->layout->sublink = array(
            'uri'=>array('fayshop/admin/goods-cat-prop/index', array(
                'cat_id'=>$cat['id'],
            )),
            'text'=>'返回属性列表',
        );
        $this->layout->subtitle = HtmlHelper::encode($cat['title']) . ' - 分类属性 - ' . $prop['title'];
        $this->view->prop_values = ShopGoodsCatPropValuesTable::model()->fetchAll(array(
            'prop_id = ?'=>$prop['id'],
            'delete_time = 0',
        ), '*', 'sort');
        
        $this->form()->setData($prop);
        
        $this->_setListview($prop['cat_id']);
        
        return $this->view->render();
    }
    
    public function sort(){
        $id = $this->input->get('id', 'intval');
        ShopGoodsCatPropsTable::model()->update(array(
            'sort'=>$this->input->get('sort', 'intval'),
        ), array(
            'id = ?'=>$id,
        ));
        $this->actionlog(ActionlogsTable::TYPE_GOODS_PROP, '改变了商品属性排序', $id);
        
        $data = ShopGoodsCatPropsTable::model()->find($id, 'sort');
        Response::notify('success', array(
            'message'=>'一个商品属性的排序值被编辑',
            'data'=>array(
                'sort'=>$data['sort'],
            ),
        ));
    }
    
    public function isAliasNotExist(){
        if(ShopGoodsCatPropsTable::model()->fetchRow(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            return Response::json('', 0, '别名已存在');
        }else{
            return Response::json('', 1, '别名不存在');
        }
    }
    /**
     * 设置右侧项目列表
     */
    private function _setListview($cat_id){
        $sql = new Sql();
        $sql->from('goods_cat_props')
            ->where(array(
                'delete_time = 0',
                'cat_id IN ('.implode(',', CategoryService::service()->getParentIDs($cat_id)).')',
            ))
            ->order('sort, id DESC');
        $listview = new ListView($sql, array(
            'page_size'=>15,
            'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
        ));
        $this->view->listview = $listview;
        
    }
}