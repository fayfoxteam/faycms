<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;

class GatherController extends AdminController{
	public function getUrl(){
		echo file_get_contents($this->input->get('url'));
	}
}