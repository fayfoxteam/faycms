<?php
namespace apidoc\modules\frontend\controllers;

use apidoc\helpers\LinkHelper;
use apidoc\library\FrontController;
use apidoc\models\tables\ApidocApisTable;
use cms\services\CategoryService;
use fay\core\HttpException;
use apidoc\helpers\TrackHelper;
use apidoc\models\tables\ApidocModelsTable;
use fay\core\Sql;

class ModelController extends FrontController{
    public function __construct(){
        parent::__construct();
    }
    
    public function item(){
        //表单验证
        $this->form()->setRules(array(
            array(array('model_id'), 'required'),
            array(array('model_id', 'api_id'), 'int', array('min'=>1)),
        ))->setFilters(array(
            'model_id'=>'intval',
            'api_id'=>'intval',
        ))->setLabels(array(
            'model_id'=>'模型ID',
            'api_id'=>'API ID',
        ))->check();
        
        //通过API ID确定展开的菜单页
        $api_id = TrackHelper::getApiId();
        if($api_id){
            $api = ApidocApisTable::model()->find($api_id, 'cat_id');
            if($api){
                $category = CategoryService::service()->get($api['cat_id'], 'alias');
                $this->layout->current_directory = $category['alias'];
                $this->layout->api_id = $api_id;
            }
        }
        
        $model_id = $this->form()->getData('model_id');
        $model = ApidocModelsTable::model()->find($model_id);
        if(!$model || $model['id'] < 1000){
            throw new HttpException('您访问的页面不存在');
        }
        
        //Layout 参数
        $this->layout->assign(array(
            'subtitle'=>$model['name'],
            'title'=>$model['description'],
            'canonical'=>LinkHelper::getModelLink($model['id']),
        ));
        
        $sql = new Sql();
        //View
        $this->view->assign(array(
            'model'=>$model,
            'properties'=>$sql->from(array('mp'=>'apidoc_model_props'), array('name', 'sample', 'description', 'type', 'is_array'))
                ->joinLeft(array('m'=>'apidoc_models'), 'mp.type = m.id', array('name AS model_name'))
                ->where('mp.model_id = ' . $model['id'])
                ->fetchAll(),
        ))->render('apidoc/frontend/model/item');
    }
}