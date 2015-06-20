<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/20
 * Time: 下午7:51
 */
namespace hq\models;

use fay\core\Model;

class ParentBiao extends Model
{
    /**
     * @return ParentBiao
     */
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
}