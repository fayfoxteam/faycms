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

    /**
     * @var array 允许请求的字段（多维数组）
     */
    private $allow_fields = array();
    
    public function __construct($fields, $section, $allow_fields = array()){
        $this->section = $section;
        $this->allow_fields = $allow_fields;
        
        if(is_string($fields)){
            $this->_parseString($fields);
        }
    }
    
    public function filter($filters){
        foreach($filters as $section => $fields){
            if($section == $this->section && is_array($fields)){
                //过滤字段
                $this->fields = array_intersect($this->fields, $fields);
                
                if($this->allow_fields){
                    //若原先有设置过滤字段，取交集
                    $this->allow_fields = ArrayHelper::intersect($this->allow_fields, $filters);
                }else{
                    //若原先没有设置过滤字段，直接赋值
                    $this->allow_fields = $filters;
                }
            }else if(isset($this->siblings[$section])){
                //递归给其他实例过滤
                $this->siblings[$section]->filter(isset($fields[0]) ? array($section => $fields) : $fields);
            }
        }
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
                if($field_explode[0] == $this->section){
                    $this->_parseString($field_explode[1]);
                }else if(isset($this->siblings[$field_explode[0]])){
                    $this->siblings[$field_explode[0]]->addField($field_explode[1]);
                }else{
                    $allow_fields = array();
                    if(isset($this->allow_fields[$field_explode[0]])){
                        if(isset($this->allow_fields[$field_explode[0]][0])){
                            //是索引数组，说明已经是底层结果，带上键。例如：['user'=>['id', 'nickname']]
                            $allow_fields[$field_explode[0]] = $this->allow_fields[$field_explode[0]];
                        }else{
                            //是关联数组，说明只是个父节点，不带键。例如：['parent'=>['user'=>['id', 'nickname']]]
                            $allow_fields = $this->allow_fields[$field_explode[0]];
                        }
                    }
                    $this->siblings[$field_explode[0]] = new self(
                        $field_explode[1],
                        $field_explode[0],
                        $allow_fields
                    );
                }
            }else if(!empty($field)){
                //没有点好，且非空，则归属到顶级或默认键值下
                if(strpos($field, ':')){
                    //若存在冒号，则有附加信息
                    $field_extra = explode(':', $field, 2);
                    $field = $field_extra[0];
                    
                    $this->extra[$field] = $field_extra[1];
                }
                
                if(!in_array($field, $this->fields) &&
                    (empty($this->allow_fields[$this->section]) || in_array($field, $this->allow_fields[$this->section]) ||
                        //由于层级关系，很难处理是在当前数组还是在当前集合键所在数组
                        in_array($field, $this->allow_fields))){
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