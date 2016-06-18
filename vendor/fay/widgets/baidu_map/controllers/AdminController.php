<?php
namespace fay\widgets\baidu_map\controllers;

use fay\widget\Widget;
use fay\models\Flash;

class AdminController extends Widget{
	public function index($data){
		$this->view->assign(array(
			'data'=>$data
		))->render();
	}
	
	public function onPost(){
		$this->setConfig($this->form->getFilteredData());
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array(array('ak'), 'required'),
			array('zoom_num', 'int', array('min'=>1, 'max'=>19)),
			array(array('width', 'height'), 'int'),
			array(array('enable_scroll_wheel_zoom', 'navigation_control', 'scale_control'), 'range', array('range'=>array('0', '1'))),
		);
	}
	
	public function labels(){
		return array(
			'ak'=>'百度地图密钥',
			'point'=>'地图中心点经纬度',
			'width'=>'宽度',
			'height'=>'高度',
			'zoom_num'=>'地图级别',
			'enable_scroll_wheel_zoom'=>'是否启用滚轮缩放',
			'navigation_control'=>'是否显示平移缩放控件',
			'scale_control'=>'是否显示比例尺',
			'marker_point'=>'标注点经纬度',
			'marker_info'=>'标注点描述',
		);
	}
	
	public function filters(){
		return array(
			'ak'=>'trim',
			'point'=>'trim',
			'width'=>'intval',
			'height'=>'intval',
			'zoom_num'=>'intval',
			'enable_scroll_wheel_zoom'=>'intval',
			'navigation_control'=>'intval',
			'scale_control'=>'intval',
			'marker_point'=>'trim',
			'marker_info'=>'trim',
		);
	}
}