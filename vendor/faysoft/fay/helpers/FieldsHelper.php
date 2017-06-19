<?php
namespace fay\helpers;

use fay\core\ErrorException;

/**
 * Field项
 */
class FieldsHelper{
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
    private $_extra = array();

    /**
     * @var array 其他平级字段集合（键值数组，值为FieldsHelper实例）
     */
    private $siblings = array();
    
    public function __construct($fields, $section, $allow_fields = array()){
        $this->section = $section;
        
        if(is_string($fields)){
            //解析字符串
            $this->_parseString($fields);
        }else if(is_array($fields)){
            //解析数组（其实还是组装回字符串进行解析，因为附加属性不好处理）
            $this->_parseArray($fields);
        }else if($fields instanceof self){
            //重复解析，赋值即可
            $this->section = $fields->section;
            $this->_extra = $fields->_extra;
            $this->fields = $fields->fields;
            $this->siblings = $fields->siblings;
        }
        
        if($allow_fields){
            //过滤字段
            $this->filter($allow_fields);
        }
    }

    /**
     * 过滤字段
     * @param array $allow_fields
     */
    public function filter($allow_fields){
        $current_section_fields = array();//不过滤星号
        foreach($allow_fields as $section => $fields){
            if(is_int($section) && is_string($fields)){
                //传入一维数组的话，认为是对当前section过滤
                $current_section_fields[] = $fields;
            }else if($section == $this->section && is_array($fields)){
                foreach($fields as $key => $field){
                    if(is_int($key)){
                        //键是数字，视为当前section字段
                        $current_section_fields[] = $field;
                    }else if(is_array($field) && isset($this->siblings[$key])){
                        //键是字符串，视为下一层section字段
                        $this->siblings[$key]->filter($field);
                    }
                }
            }else if(isset($this->siblings[$section])){
                //下层section字段
                $this->siblings[$section]->filter($fields);
            }
            
            if(isset($this->siblings[$section]) && !$this->siblings[$section]->getFields()){
                //如果字段被全部过滤掉了，则删除这个Section
                unset($this->siblings[$section]);
            }
        }

        if($current_section_fields){
            if(!in_array('*', $allow_fields) && in_array('*', $this->fields)){
                //当前字段中有型号，允许的字段中没有星号，则将当前字段替换为允许的字段
                $this->fields = $current_section_fields;
            }else if(in_array('*', $allow_fields)){
                //允许的字段中有型号，则不做过滤
            }else{
                //两边都没星号，取交集
                $this->fields = array_intersect($this->fields, $current_section_fields);
            }
        }else{
            //全部被过滤光了，把$this->fields置空
            $this->fields = array();
        }
    }

    /**
     * 获取指定field扩展信息
     * @param string $key
     * @return string|null
     */
    public function getExtra($key){
        return isset($this->_extra[$key]) ? $this->_extra[$key] : null;
    }

    /**
     * 返回当前字段集合的所有字段
     * @return array
     */
    public function getFields(){
        return $this->fields;
    }

    /**
     * 覆盖字段集合
     * @param array|string $fields
     * @throws ErrorException
     * @return $this
     */
    public function setFields($fields){
        if(is_array($fields)){
            $fields = implode(',', $fields);
        }
        
        if(is_string($fields)){
            $this->fields = array();
            $this->addFields($fields);
        }else{
            throw new ErrorException('无法识别的$fields类型[' . serialize($fields) . ']', 'unknown-field-type');
        }
        
        return $this;
    }

    /**
     * 判断一个字段是否存在
     * @param string $field
     * @param bool $only_current_section 若为true，则只检查$this->fields中是否存在，不检查$this->siblings
     * @return bool
     */
    public function hasField($field, $only_current_section = false){
        if($only_current_section){
            return in_array($field, $this->fields);
        }else{
            return in_array($field, $this->fields) || isset($this->siblings[$field]);
        }
    }

    /**
     * 增加一个或多个字段
     * @param string $fields
     * @throws ErrorException
     */
    public function addFields($fields){
        if(is_array($fields)){
            //由于可能存在附加属性，先组合成字符串再走字符串逻辑简单一些
            $fields = implode(',', $fields);
        }

        if(is_string($fields)){
            $this->_parseString($fields);
        }else{
            throw new ErrorException('无法识别的$fields类型', json_encode($fields));
        }
    }

    /**
     * 新增一个扩展信息（若$key已存在，会被覆盖）
     * @param string $key
     * @param string $value
     */
    public function addExtra($key, $value){
        $this->_extra[$key] = $value;
    }

    /**
     * 移除一个字段
     * @param string $field
     */
    public function removeField($field){
        if($key = array_search($field, $this->fields)){
            unset($this->fields[$key]);
        }
    }

    /**
     * 根据数组初始化
     * 先解析完再过滤，这样逻辑清晰一点，过滤逻辑太绕了
     * @param array $arr
     */
    private function _parseArray($arr){
        foreach($arr as $section => $fields){
            if(is_int($section)){
                //当没有层级关系的时候，传入的$fields可能是个一维索引数组
                $this->addFields($fields);
            }else if($section == $this->section){
                $this->addFields(implode(',', $fields));
            }else if(isset($this->siblings[$section])){
                $this->siblings[$section]->addFields($fields);
            }else{
                $this->siblings[$section] = new self(isset($fields[0]) ? implode(',', $fields) : $fields, $section);
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
                    $this->siblings[$field_explode[0]]->addFields($field_explode[1]);
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
                    
                    $this->_extra[$field] = $field_extra[1];
                }
                
                if(!in_array($field, $this->fields)){
                    $this->fields[] = $field;
                }
            }
        }
    }

    /**
     * 以对象的方式访问
     * @param $name
     * @return FieldsHelper
     */
    public function __get($name){
        if(isset($this->siblings[$name])){
            return $this->siblings[$name];
        }else{
            return null;
        }
    }

    /**
     * 当以对象的方式访问时，用于empty和isset判断
     * @param $name
     * @return bool
     */
    public function __isset($name){
        return isset($this->siblings[$name]);
    }

    /**
     * 将对象组装回字符串
     * @return string
     */
    public function __toString(){
        //@todo 待实现
        return '';
    }
}