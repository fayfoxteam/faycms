<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;

class CssController extends ToolsController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'css';
    }
    
    public function compress(){
        $this->layout->subtitle = 'CSS Compress';
        
        $buffer = preg_replace("!/\*[^*]*\*+([^/][^*]*\*+)*/!", "", $this->input->post('data'));
        $arr = array("\r\n", "\r", "\n", "\t", "  ", "    ", "    ");
        $buffer = str_replace($arr, "", $buffer);
        $buffer = str_replace(" {", "{", $buffer);
        $buffer = str_replace(": ", ":", $buffer);
        $buffer = str_replace("}", "}\r\n", $buffer);
        $this->view->after_compress = $buffer;
        
        return $this->view->render();
    }
    
}