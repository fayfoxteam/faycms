<?php
namespace guangong\modules\frontend\controllers;

use cms\services\file\FileService;
use guangong\library\FrontController;
use guangong\models\tables\GuangongRanksTable;

/**
 * 网络体验
 */
class RankController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '军职';
    }
    
    public function index(){
        $ranks = GuangongRanksTable::model()->fetchAll(array(), '*', 'sort');
        foreach($ranks as &$r){
            $r['description_picture'] = FileService::get($r['description_picture']);
        }
        
        $this->view->ranks = $ranks;
        return $this->view->render();
    }
}