<?php
namespace cms\library;

use fay\core\Controller;

/**
 * API积累
 */
class ApiController extends Controller{
	public function __construct(){
		parent::__construct();
		
		$this->current_user = \F::session()->get('user.id');
	}
}