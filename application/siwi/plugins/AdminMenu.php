<?php
namespace siwi\plugins;

class AdminMenu{
    public static function run(){
        if(method_exists(\F::app(), 'removeMenuTeam')){
            \F::app()->removeMenuTeam('exam-question');
            \F::app()->removeMenuTeam('exam-paper');
        }
    }
}