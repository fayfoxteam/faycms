<?php
namespace fay\helpers;

class FieldHelper{
    /**
     * 将user.username,user.nickname,user.id,user.avatar:320x320,props.*,user.role.id,user.role.title这样的字符串，
     * 转换为如下格式的数组
     * array(
     *   'user'=>array(
     *     'username', 'nickname', 'id', 'role'=>array(
     *       'id', 'title'
     *     )
     *   ),
     *   'props'=>array(
     *     '*'
     *   ),
     *   '_extra'=>array(
     *     'user'=>array(
     *       'avatar'=>'320x320'
     *     )
     *   )
     * )
     * @param string|array $fields
     * @param null|string $default_key 不包含.(点号)的项会被归属到$default_key下
     * @param array $allowed_fields 若该字段非空，则会调用self::filter()方法对解析后的$fields进行过滤
     * @return array
     */
    public static function parse($fields, $default_key = null, $allowed_fields = array()){
        if(is_array($fields)){
            $return = self::_parseArray($fields, $default_key);
        }else{
            //解析字符串
            $return = self::_parseString($fields, $default_key);
        }
        
        return $allowed_fields ? self::filter($return, $allowed_fields) : $return;
    }
    
    /**
     * 将字符串解析为数组
     * //post.id,post.thumbnail:320x320,user.id,user.avatar:200x200,user.roles.id
     * array(
     *   'post'=>array(
     *     'fields'=>array('id', 'thumbnail'),
     *     'extra'=>array(
     *       'thumbnail'=>'320x320',
     *     )
     *   ),
     *   'user'=>array(
     *     'fields'=>array(
     *       'id',
     *       'avatar',
     *       'roles'=>array(
     *         'fields'=>array('id')
     *       )
     *     ),
     *     'extra'=>array(
     *       'avatar'=>'200x200',
     *     )
     *   )
     * )
     * @param string $string
     * @param string $default_key
     * @return array
     */
    private static function _parseString($string, $default_key){
        $fields = explode(',', $string);
        $return = array();
        foreach($fields as $field){
            $field = trim($field);
            if(strpos($field, '.')){
                //如果带有点号，则归属到指定的数组项
                $field_path = explode('.', $field);
                $field = array_pop($field_path);//最后一项是字段值
                
                if(strpos($field, ':')){
                    //若存在冒号，则有附加信息
                    $field_extra = explode(':', $field, 2);
                    $field = $field_extra[0];
                    
                    eval('$return[\'' . implode("']['fields']['", $field_path) . "']['extra']['$field']='{$field_extra[1]}';");
                }
                
                eval('$return[\'' . implode("']['fields']['", $field_path) . "']['fields'][]='{$field}';");
            }else if(!empty($field)){
                //没有点好，且非空，则归属到顶级或默认键值下
                if(strpos($field, ':')){
                    //若存在冒号，则有附加信息
                    $field_extra = explode(':', $field, 2);
                    $field = $field_extra[0];
                    if($default_key === null){
                        $return['extra'][$field] = $field_extra[1];
                    }else{
                        $return[$default_key]['extra'][$field] = $field_extra[1];
                    }
                }
                if($default_key === null){
                    $return['fields'][] = $field;
                }else{
                    $return[$default_key]['fields'][] = $field;
                }
            }
        }
        
        return $return;
    }
    
    /**
     * 将数组（外层$fields解析后得到的）转换为标准结构
     * @param array $array
     * @param string $default_key
     * @return array
     */
    private static function _parseArray($array, $default_key){
        if(!isset($array['fields'])){
            if($array){
                list($key, $value) = each($array);
                if(is_int($key) && !is_array($value)){
                    //传入一维数组，为了书写方便，不需要附加信息的时候，会直接使用一维数组代表fields字段
                    if($default_key === null){
                        return array(
                            'fields'=>$array,
                        );
                    }else{
                        return array(
                            $default_key=>array(
                                'fields'=>$array,
                            )
                        );
                    }
                }else{
                    //重复解析，直接返回
                    return $array;
                }
            }
        }
        $return = array();
        foreach($array['fields'] as $k => $field){
            if(is_int($k)){
                if($default_key !== null){
                    $return[$default_key]['fields'][] = $field;
                }else{
                    $return['fields'][] = $field;
                }
            }else{
                $return[$k] = $field;
            }
        }
        
        if(isset($array['extra'])){
            if($default_key !== null){
                $return[$default_key]['extra'] = $array['extra'];
            }else{
                $return['extra'] = $array['extra'];
            }
        }
        
        return $return;
    }
    
    /**
     * 从$fields中过滤出被允许的字段
     * @param array $fields 解析成数组后的用户指定字段
     * @param array $allowed_fields 允许的字段
     * @return array
     */
    public static function filter($fields, $allowed_fields){
        if(isset($fields['fields'])){
            return self::_filterSimpleSection($fields, $allowed_fields);
        }else{
            return self::_filterSections($fields, $allowed_fields);
        }
    }
    
    /**
     * 过滤单组字段，例如：
     * array(
     *   'fields'=>array('id', 'thumbnail'),
     *   'extra'=>array(
     *     'thumbnail'=>'320x320',
     *   )
     * )
     * @param array $fields
     * @param array $allowed_fields
     * @return array
     */
    private static function _filterSimpleSection($fields, $allowed_fields){
        //单组字段过滤
        if(in_array('*', $fields['fields'])){
            //指定字段中带有*，则返回所有允许的字段
            $fields['fields'] = $allowed_fields;
        }else if(!in_array('*', $allowed_fields)){
            //允许的字段中不含信号，则做交集
            $fields['fields'] = array_intersect($allowed_fields, $fields['fields']);
        }
        return $fields;
    }
    
    /**
     * 过滤多组字段，例如：
     * array(
     *   'post'=>array(
     *     'fields'=>array('id', 'thumbnail'),
     *     'extra'=>array(
     *       'thumbnail'=>'320x320',
     *     )
     *   ),
     *   'user'=>array(
     *     'fields'=>array(
     *       'id',
     *       'avatar',
     *       'roles'=>array(
     *         'fields'=>array('id')
     *       )
     *     ),
     *     'extra'=>array(
     *       'avatar'=>'200x200',
     *     )
     *   )
     * )
     * @param array $fields
     * @param array $allowed_fields
     * @return array
     */
    private static function _filterSections($fields, $allowed_fields){
        //多组字段过滤
        foreach($fields as $key => $section){
            if(!isset($allowed_fields[$key])){
                unset($fields[$key]);
                continue;
            }
            
            if(in_array('*', $section['fields'])){
                //若获取字段中包含*，则返回所有允许的字段
                $fields[$key]['fields'] = $allowed_fields[$key];
            }else if(!in_array('*', $allowed_fields[$key])){
                //若允许的字段中不包含*，则逐个判断是否允许的字段
                foreach($section['fields'] as $k => $field){
                    if(is_array($field)){
                        //若是数组，则包含了子字段集
                        if(!isset($allowed_fields[$key][$k])){
                            //若允许的字段中并没有对应的key，直接跳过
                            unset($section['fields'][$k]);
                            continue;
                        }
                        $sub_filter = self::filter(array(
                            $k => $field
                        ), array(
                            $k => $allowed_fields[$key][$k]
                        ));
                        if(!empty($sub_filter[$k]['fields'])){
                            $section['fields'][$k] = $sub_filter[$k];
                        }else{
                            unset($section['fields'][$k]);
                        }
                    }else if(!in_array($field, $allowed_fields[$key])){
                        //不允许的字段，删掉
                        unset($section['fields'][$k]);
                    }
                }
                $fields[$key] = $section;
            }
        }
        
        return $fields;
    }
    
    /**
     * 将self::parse()解析出来的字符串拼凑回去
     * @param array $data self::parse()得到的结果
     * @param string $prefix 前缀
     * @return string
     */
    public static function build($data, $prefix = ''){
        $return = array();
        foreach($data as $sk => $section){
            foreach($section['fields'] as $fk => $field){
                if(is_int($fk)){
                    $field_str = $prefix ? "{$prefix}.{$sk}.{$field}" : "{$sk}.{$field}";
                    if(isset($section['extra'][$field])){
                        $field_str.= ":{$section['extra'][$field]}";
                    }
                    $return[] = $field_str;
                }else{
                    $return[] = self::build(
                        array($fk=>$field),
                        $prefix ? "{$prefix}.{$sk}" : "{$sk}"
                    );
                }
            }
        }
        
        return implode(',', $return);
    }
}