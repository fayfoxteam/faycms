<?php
namespace fay\helpers;

/**
 * Field项
 */
class FieldItem implements \ArrayAccess{
    /**
     * @var string
     */
    private $section = '';

    /**
     * @var array 包含字段（一维索引数组）
     */
    private $fields = array();

    /**
     * @var array 扩展信息（键值数组）
     */
    private $extra = array();

    /**
     * @var array 其他平级字段集合（键值数组，值为FieldItem实例）
     */
    private $siblings = array();
    
    public function __construct($fields, $section, $allow_fields = array()){
        $this->section = $section;
        
        if(is_string($fields)){
            $this->_parseString($fields);
            if($allow_fields){
                //过滤字段
                $this->filter($allow_fields);
            }
        }
    }

    /**
     * 过滤字段
     * @param array $allow_fields
     */
    public function filter($allow_fields){
        foreach($allow_fields as $section => $fields){
            if($section == $this->section && is_array($fields)){
                if(in_array('*', $fields)){
                    //若包含星号，则什么都不做
                    continue;
                }
                //过滤字段
                $this->fields = array_intersect($this->fields, $fields);
            }else if(isset($this->siblings[$section])){
                //递归给其他实例过滤
                $this->siblings[$section]->filter(isset($fields[0]) ? array($section => $fields) : $fields);
            }
        }
    }
    
    /**
     * 根据字符串初始化
     * 先解析完再过滤，这样逻辑清晰一点，过滤逻辑太绕了
     * @param string $string
     */
    private function _parseString($string){
        $fields = explode(',', $string);
        
        foreach($fields as $field){
            $field = trim($field);
            if(strpos($field, '.')){
                //如果带有点号，则归属到指定的数组项
                $field_explode = explode('.', $field, 2);
                if($field_explode[0] == $this->section){
                    $this->_parseString($field_explode[1]);
                }else if(isset($this->siblings[$field_explode[0]])){
                    $this->siblings[$field_explode[0]]->addField($field_explode[1]);
                }else{
                    $this->siblings[$field_explode[0]] = new self(
                        $field_explode[1],
                        $field_explode[0]
                    );
                }
            }else if(!empty($field)){
                //没有点号，且非空，则归属到顶级或默认键值下
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
     * 返回当前字段集合的所有字段
     * @return array
     */
    public function getFields(){
        return $this->fields;
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
     * 获取fields，extra或下层元素
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset){
        if($offset === 'fields'){
            return $this->fields;
        }else if($offset === 'extra'){
            return $this->extra;
        }else if(isset($this->siblings[$offset])){
            return $this->siblings[$offset];
        }else{
            return null;
        }
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