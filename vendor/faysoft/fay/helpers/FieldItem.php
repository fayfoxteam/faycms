<?php
namespace fay\helpers;

/**
 * Field项
 */
class FieldItem implements \ArrayAccess{
    private $section = '';

    private $fields = array();

    private $extra = array();

    private $children = array();
    
    public function __construct($fields, $section = ''){
        $this->section = $section;
        
        if(is_string($fields)){
            $this->_parseString($fields);
        }else if(is_array($fields)){
            $this->_parseArray($fields);
        }
    }
    
    private function _parseArray($array){
        
    }

    /**
     * 根据字符串初始化
     * @param string $string
     */
    private function _parseString($string){
        $fields = explode(',', $string);
        
        foreach($fields as $field){
            $field = trim($field);
            if(strpos($field, '.')){
                //如果带有点号，则归属到指定的数组项
                $field_explode = explode('.', $field, 2);
                if(isset($this->children[$field_explode[0]])){
                    $this->children[$field_explode[0]]->addField($field_explode[1]);
                }else{
                    $this->children[$field_explode[0]] = new self($field_explode[1], $field_explode[0]);
                }
            }else if(!empty($field)){
                //没有点好，且非空，则归属到顶级或默认键值下
                if(strpos($field, ':')){
                    //若存在冒号，则有附加信息
                    $field_extra = explode(':', $field, 2);
                    $field = $field_extra[0];
                    
                    $this->extra[$field] = $field_extra[1];
                }
                
                if(!in_array($field, $this->fields)){
                    $this->fields[] = $field;
                }
            }
        }
    }

    /**
     * 增加一个field
     * @param string $field
     */
    public function addField($field){
        $this->_parseString($field);
    }

    /**
     * 获取指定field扩展信息
     * @param string $key
     * @return string|null
     */
    public function getExtra($key){
        return isset($this->extra[$key]) ? $this->extra[$key] : null;
    }

    /**
     * 判断一个字段是否存在
     * @param string $field
     * @return bool
     */
    public function hasField($field){
        return in_array($field, $this->fields);
    }

    /**
     * 不支持通过ArrayAccess判断
     * @param mixed $offset
     * @return bool|void
     */
    public function offsetExists($offset){
        return;
    }

    /**
     * 获取fields，extra或下层元素
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset){
        if($offset === 'fields'){
            return $this->fields;
        }else if($offset === 'extra'){
            return $this->extra;
        }else if(isset($this->children[$offset])){
            return $this->children[$offset];
        }else{
            return null;
        }
    }

    /**
     * 不允许通过ArrayAccess赋值
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value){
        return;
    }

    /**
     * 不允许通过ArrayAccess删除值
     * @param mixed $offset
     */
    public function offsetUnset($offset){
        return;
    }
}