<?php
namespace sam\modules\frontend\controllers;

use cms\services\file\ImageService;
use cms\services\file\ImageTextService;
use fay\core\Controller;
use fay\helpers\StringHelper;

class IndexController extends Controller{
    public function index(){
        
        
        $this->view->render();
    }
    
    public function pic(){
        $visits = $this->input->get('visits', 'intval', 291);//访客数
        $visit_percent = $this->input->get('visit_percent', 'trim', '14.91');//访客增长率
        $conversion = $this->input->get('pay_percent', 'trim', '6.87');//下单转化率
        $conversion_percent = $this->input->get('conversion_percent', 'trim', '113.35');//下单增长率

        $start_time = $this->input->get('start_time', 'trim', date('Y-m-d'));
        $end_time = $this->input->get('end_time', 'trim', date('Y-m-d'));
        
        $image = new ImageTextService(BASEPATH . 'apps/sam/images/bg.png');
        $arrow_up = new ImageService(BASEPATH . 'apps/sam/images/arrow-up.png');
        $arrow_down = new ImageService(BASEPATH . 'apps/sam/images/arrow-down.png');
        
        $font_size = 9;
        $color = '#555555';
        
        
        
        $image
            //访客数
            ->write($visits, $font_size, $color, '343,0,0,391')
            ->write(intval($visits * 0.35), $font_size, $color, '396,0,0,391')
            ->write(intval($visits * 0.26), $font_size, $color, '449,0,0,391')
            ->write(intval($visits * 0.21), $font_size, $color, '502,0,0,391')
            //访客数增长率
            ->write(number_format($visit_percent, 2, '.', '') . '%', $font_size, $color, '343,0,0,492')
            ->write(number_format($visit_percent * 2.36, 2, '.', '') . '%', $font_size, $color, '396,0,0,492')
            ->write(number_format($visit_percent * 1.57, 2, '.', '') . '%', $font_size, $color, '449,0,0,492')
            ->write(number_format($visit_percent * 1.35, 2, '.', '') . '%', $font_size, $color, '502,0,0,492')
            //访客箭头
            ->merge($arrow_up, '341,0,0,479', array('left', 'top'))
            ->merge($arrow_up, '394,0,0,479', array('left', 'top'))
            ->merge($arrow_up, '447,0,0,479', array('left', 'top'))
            ->merge($arrow_down, '500,0,0,479', array('left', 'top'))
            //下单卖家数
            ->write(round($visits * $conversion / 100), $font_size, $color, '343,0,0,613')
            ->write(round($visits * 0.35 * $conversion * 1.23 / 100), $font_size, $color, '396,0,0,613')
            ->write(round($visits * 0.26 * $conversion * 2.14 / 100), $font_size, $color, '449,0,0,613')
            ->write(round($visits * 0.21 * $conversion * 1.54 / 100), $font_size, $color, '502,0,0,613')
            //下单买家数箭头
            ->merge($arrow_up, '341,0,0,701', array('left', 'top'))
            ->merge($arrow_up, '394,0,0,701', array('left', 'top'))
            ->merge($arrow_down, '447,0,0,701', array('left', 'top'))
            ->merge($arrow_up, '500,0,0,701', array('left', 'top'))
            //下单买家数增长率
            ->write(number_format($conversion_percent, 2, '.', '') . '%', $font_size, $color, '343,0,0,713')
            ->write(number_format($conversion_percent * 2.06, 2, '.', '') . '%', $font_size, $color, '396,0,0,713')
            ->write(number_format($conversion_percent * 1.22, 2, '.', '') . '%', $font_size, $color, '449,0,0,713')
            ->write(number_format($conversion_percent * 1.03, 2, '.', '') . '%', $font_size, $color, '502,0,0,713')
            //转化率增长率
            ->write(number_format($conversion_percent, 2, '.', '') . '%', $font_size, $color, '343,0,0,936')
            ->write(number_format($conversion_percent * 2.36, 2, '.', '') . '%', $font_size, $color, '396,0,0,936')
            ->write(number_format($conversion_percent * 1.57, 2, '.', '') . '%', $font_size, $color, '449,0,0,936')
            ->write(number_format($conversion_percent * 1.35, 2, '.', '') . '%', $font_size, $color, '502,0,0,936')
            //下单转化率
            ->write(number_format($conversion, 2, '.', '') . '%', $font_size, $color, '343,0,0,836')
            ->write(number_format($conversion * 1.23, 2, '.', '') . '%', $font_size, $color, '396,0,0,836')
            ->write(number_format($conversion * 2.14, 2, '.', '') . '%', $font_size, $color, '449,0,0,836')
            ->write(number_format($conversion * 1.54, 2, '.', '') . '%', $font_size, $color, '502,0,0,836')
            //下单转化率箭头
            ->merge($arrow_up, '341,0,0,923', array('left', 'top'))
            ->merge($arrow_up, '394,0,0,923', array('left', 'top'))
            ->merge($arrow_up, '447,0,0,923', array('left', 'top'))
            ->merge($arrow_down, '500,0,0,923', array('left', 'top'))
            //时间
            ->write(date('Y-m-d', strtotime($start_time)) . ' ~ ' . date('Y-m-d', strtotime($end_time)), 10, $color, '172,0,0,861')
        ;
        if($this->input->get('download')){
            $image->download(StringHelper::random('uuid').'.png');
        }else{
            $image->output();
        }
    }
}