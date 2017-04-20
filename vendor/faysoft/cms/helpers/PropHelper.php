<?php 
namespace cms\helpers;

use cms\models\tables\PropsTable;

class PropHelper{
    /**
     * 获取文章状态
     * @param int $element
     */
    public static function getElement($element){
        switch($element){
            case PropsTable::ELEMENT_TEXT:
                echo '文本框';
                break;
            case PropsTable::ELEMENT_RADIO:
                echo '单选框';
                break;
            case PropsTable::ELEMENT_SELECT:
                echo '下拉框';
                break;
            case PropsTable::ELEMENT_CHECKBOX:
                echo '多选框';
                break;
            case PropsTable::ELEMENT_TEXTAREA:
                echo '文本域';
                break;
            case PropsTable::ELEMENT_NUMBER:
                echo '数字文本框';
                break;
            case PropsTable::ELEMENT_IMAGE:
                echo '图片';
                break;
        }
    }
}