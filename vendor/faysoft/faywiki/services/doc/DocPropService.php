<?php
namespace faywiki\services\doc;

use fay\core\Loader;
use fay\core\Service;

class DocPropService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
}