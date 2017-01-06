<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\AnalystSites;
use fay\helpers\HtmlHelper;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;

class AnalystSiteController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'analyst';
	}
	
	public function index(){
		$this->layout->subtitle = '统计站点';
		$this->_setListview();
		
		$this->form()->setModel(AnalystSites::model());
		$this->view->render();
	}
	
	public function create(){
		$this->form()->setModel(AnalystSites::model());
		if($this->input->post()){
			if($this->form()->check()){
				AnalystSites::model()->insert($this->form()->getFilteredData());
				Response::notify('success', '站点添加成功');
			}else{
				//若表单验证出错，返回上一页
				Response::goback();
			}
		}else{
			Response::notify('error', '无数据提交');
		}
	}
	
	public function edit(){
		$this->layout->sublink = array(
			'uri'=>array('admin/analyst-site/index', $this->input->get()),
			'text'=>'添加站点',
		);
		$id = $this->input->get('id', 'intval');
		
		$this->form()->setModel(AnalystSites::model());
		if($this->input->post() && $this->form()->check()){
			AnalystSites::model()->update($this->form()->getFilteredData(), $id);
			Response::notify('success', '站点编辑成功', false);
		}
		
		$site = AnalystSites::model()->find($id);
		$this->form()->setData($site);
		
		$this->layout->subtitle = '编辑统计站点 - '.HtmlHelper::encode($this->form()->getData('title'));
		
		$this->_setListview();
		
		$this->view->render();
	}
	
	public function delete(){
		$id = $this->input->get('id', 'intval');
		AnalystSites::model()->update(array(
			'deleted'=>1,
		), $id);
		Response::notify('success', '一个站点被删除。'.HtmlHelper::link('撤销', array('admin/analyst-site/undelete', array(
			'id'=>$id,
		))));
	}
	
	public function undelete(){
		$id = $this->input->get('id', 'intval');
		AnalystSites::model()->update(array(
			'deleted'=>0,
		), $id);
		Response::notify('success', '一个站点被还原');
	}
	
	private function _setListview(){
		$sql = new Sql();
		$sql->from(array('s'=>'analyst_sites'))
			->where('deleted = 0');
	
		$this->view->listview = new ListView($sql, array(
			'page_size' => 15,
			'empty_text'=>'<tr><td colspan="2" align="center">无相关记录！</td></tr>',
		));
	}
}