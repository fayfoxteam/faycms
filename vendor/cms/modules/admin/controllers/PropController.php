<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Response;
use fay\models\tables\Props;

/**
 * 通用分类属性
 */
class PropController extends AdminController{
	public function isAliasNotExist(){
		$alias = $this->input->post('value', 'trim');
		if(Props::model()->fetchRow(array(
			'alias = ?'=>$alias,
			'id != ?'=>$this->input->get('id', 'intval', false),
		))){
			echo Response::json('', 0, '别名已存在');
		}else{
			echo Response::json('', 1, '别名不存在');
		}
	}
}