<?php
namespace apidoc\modules\admin\controllers;

use apidoc\models\tables\ApidocApiCategoriesTable;
use apidoc\services\ApiCategoryService;
use cms\library\AdminController;
use fay\core\exceptions\ValidationException;
use fay\core\Response;
use fay\helpers\HtmlHelper;
use fay\helpers\NumberHelper;
use fay\helpers\PinyinHelper;

class ApiCatController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'apidoc-api';
    }
    
    public function index(){
        if($this->checkPermission('apidoc/admin/api-cat/create')){
            $this->layout->sublink = array(
                'uri'=>'#create-cat-dialog',
                'text'=>'添加分类',
                'html_options'=>array(
                    'class'=>'create-cat-link',
                    'data-title'=>'API',
                    'data-id'=>0,
                ),
            );
        }
        
        $app_id = $this->input->get('app_id', 'intval');
        if(!$app_id){
            throw new ValidationException('app_id参数不能为空');
        }

        //页面设置
        $this->settingForm('admin_api_cat', '_setting', array(
            'default_dep'=>2,
        ));

        \F::form('create')->setModel(ApidocApiCategoriesTable::model());
        \F::form('edit')->setModel(ApidocApiCategoriesTable::model());

        $this->layout->subtitle = 'API分类';
        return $this->view->assign(array(
            'app_id'=>$app_id,
            'cats'=>ApiCategoryService::service()->getTree($app_id),
        ))->render();
    }

    /**
     * 获取指定id对应的分类，及该分类下的所有子分类
     */
    public function get(){
        $cat = ApidocApiCategoriesTable::model()->find($this->input->get('id', 'intval'));
        $children = ApidocApiCategoriesTable::model()->fetchCol('id', array(
            'left_value > '.$cat['left_value'],
            'right_value < '.$cat['right_value'],
        ));

        return Response::json(array(
            'cat'=>$cat,
            'children'=>$children,
        ));
    }

    public function create(){
        $this->form()->setModel(ApidocApiCategoriesTable::model())
            ->setScene('create');
        if($this->input->post()){
            if($this->form()->check()){
                $data = $this->form()->getFilteredData();
                empty($data['is_nav']) && $data['is_nav'] = 0;
                empty($data['file_id']) && $data['file_id'] = 0;
                empty($data['alias']) && $data['alias'] = $this->generateCatAlias($data['title']);

                $parent = $this->form()->getData('parent', 0);
                $sort = $this->form()->getData('sort', 1000);

                $cat_id = ApiCategoryService::service()->create($data['app_id'], $parent, $sort, $data);

                $cat = ApidocApiCategoriesTable::model()->find($cat_id);
                Response::notify('success', array(
                    'cat'=>$cat,
                    'message'=>'分类“'.HtmlHelper::encode($cat['title']).'”添加成功',
                ));
            }else{
                Response::notify('error', '参数异常');
            }
        }else{
            Response::notify('error', '请提交数据');
        }
    }

    public function edit(){
        if($this->input->post()){
            if($this->form()->setModel(ApidocApiCategoriesTable::model())->check()){
                $cat_id = $this->input->post('id', 'intval');
                $data = $this->form()->getFilteredData();
                empty($data['is_nav']) && $data['is_nav'] = 0;
                empty($data['file_id']) && $data['file_id'] = 0;

                $parent = $this->input->post('parent', 'intval');
                $sort = $this->input->post('sort', 'intval');

                ApiCategoryService::service()->update($cat_id, $data, $sort, $parent);

                $cat = ApidocApiCategoriesTable::model()->find($cat_id);
                Response::notify('success', array(
                    'message'=>'分类“'.HtmlHelper::encode($cat['title']).'”编辑成功',
                    'cat'=>$cat,
                ));
            }else{
                Response::notify('error', '参数异常');
            }
        }else{
            Response::notify('error', '请提交数据');
        }
    }

    public function remove(){
        if(ApiCategoryService::service()->remove($this->input->get('id', 'intval'))){
            Response::notify('success', array(
                'message'=>'一个分类被移除',
            ));
        }else{
            Response::notify('error', '请提交数据');
        }
    }

    public function removeAll(){
        if(ApiCategoryService::service()->removeAll($this->input->get('id', 'intval'))){
            Response::notify('success', array(
                'message'=>'一个分类分支被移除',
            ));
        }else{
            Response::notify('error', '请提交数据');
        }
    }

    /**
     * 获取唯一的别名（遇到中文会将其转为拼音）
     * @param string $title
     * @param null|string $spelling
     * @param int $dep
     * @return string
     */
    private function generateCatAlias($title = '', $spelling = null, $dep = 0){
        if(!$spelling){
            if($title){
                if(preg_match('/[^\x00-\x80]/', $title)){//如果包含中文，将中文转成拼音
                    $spelling = PinyinHelper::change($title);
                }else{
                    $spelling = $title;
                }
            }else{
                return $title;
            }
        }

        //转为纯小写，非数字字母的字符转为横线（连续多个特殊字符转化为一根横线）
        $spelling = preg_replace('/[^\w]+/', '-', strtolower($spelling));

        //若第一个字符是数字，加c前缀
        if(NumberHelper::isInt(substr($spelling, 0, 1))){
            $spelling = 'c'.$spelling;
        }

        $alias = $dep ? $spelling.'-'.$dep : $spelling;
        $cat = ApidocApiCategoriesTable::model()->fetchRow(array('alias = ?'=>$alias), 'id');
        if($cat){
            return $this->generateCatAlias('', $spelling, $dep + 1);
        }else{
            return $alias;
        }
    }

    /**
     * 判断指定别名是否可用
     */
    public function isAliasNotExist(){
        if(ApidocApiCategoriesTable::model()->has(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            return Response::json('', 0, '别名已存在');
        }else{
            return Response::json('', 1, '别名不存在');
        }
    }

    public function sort(){
        $id = $this->input->get('id', 'intval');
        ApiCategoryService::service()->sort($id, $this->input->get('sort', 'intval'));

        $node = ApidocApiCategoriesTable::model()->find($id, 'sort,title');
        Response::notify('success', array(
            'data'=>array(
                'sort'=>$node['sort'],
            ),
            'message'=>"分类{$node['title']}的排序值被修改为{$node['sort']}",
        ));
    }
}