<?php
namespace cms\services\oauth;

class StateManager{
    private $session_key;
    
    public function __construct($session_key){
        $this->session_key = $session_key;
    }
    
    public function setState($state){
        \F::session()->set($this->session_key, (string)$state);
    }
    
    public function getState(){
        return \F::session()->get($this->session_key);
    }
    
    public function hasState(){
        return !!$this->getState();
    }
    
    public function removeState(){
        \F::session()->remove($this->session_key);
    }
    
    public function check($state){
        return ($state === $this->getState());
    }
}