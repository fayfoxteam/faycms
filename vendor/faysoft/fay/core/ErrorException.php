<?php
namespace fay\core;

class ErrorException extends \ErrorException{
    /**
     * @var string
     */
    private $description;
    
    public function __construct($message = '', $description = '', $code = E_USER_ERROR, $filename = __FILE__, $lineno = __LINE__, $severity = 1, \Exception $previous = null){
        parent::__construct($message, $code, $severity, $filename, $lineno, $previous);
        $this->description = $description;
    }
    
    /**
     * @return string
     */
    public function getDescription(){
        return $this->description ? $this->description : '';
    }
    
    public function __toString(){
        return parent::__toString() . ($this->description ? PHP_EOL . $this->description : '');
    }
}