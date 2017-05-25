<?php
namespace amq\modules\frontend\controllers;

use amq\library\FrontController;
use fay\core\HttpException;

class GuanwangController extends FrontController{
    private $type_cat_map = array(
        '1'=>10000,
        '65'=>10001,
        '4'=>10002,
        '2'=>10003,
        '3'=>10004,
    );
    /**
     * 从官网同步过来文章
     */
    public function article_add(){
        if($this->input->request('token') != '7cb46c0fc4cc7d78aced3fa6f096457f'){
            throw new HttpException('您请求的页面不存在');
        }
        
        $title = iconv('gbk', 'utf-8', $this->input->request('title'));
        $content = iconv('gbk', 'utf-8', $this->input->request('body'));
        $typeid = $this->input->request('typeid');
        
        $cat_id = $this->type_cat_map[$typeid];

        
        
        \F::logger()->log(json_encode($_POST));
        \F::logger()->log($title);
        \F::logger()->log($content);
        \F::logger()->log($cat_id);
    }
}