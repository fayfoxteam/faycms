<?php
namespace cms\services\prop\elements;

/**
 * 单图上传
 * 虽然业务上和数字输入框不一样，但由于文件系统是通过文件id关联的，所以其实也是存一个数字，处理逻辑与数字输入框是一致的
 */
class PropElementImage extends PropElementNumber{
    /**
     * 获取表单元素名称
     * @return string
     */
    public static function getName(){
        return '图片';
    }
}