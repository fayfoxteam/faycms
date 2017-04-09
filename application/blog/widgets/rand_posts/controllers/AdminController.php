<?php
namespace blog\widgets\rand_posts\controllers;

use fay\widget\Widget;

class AdminController extends Widget{
    
    public $title = '随机文章';
    public $author = 'fayfox';
    public $author_link = 'http://www.fayfox.com';
    public $description = '博客右侧随机文章';
    public $screenshot = 'screenshot.jpg';
    
    public function index(){}
}