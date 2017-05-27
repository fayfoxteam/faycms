<?php
namespace cms\services\shop;

use fay\core\Loader;
use fay\core\Service;
use cms\models\tables\UserAddressesTable;

class ShopAddressService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 添加一个地址
     * @param array $data 地址相关参数，此函数会对多余参数进行过滤，但不会进行校验
     * @param int $user_id 用户ID
     * @return int
     */
    public function create($data, $user_id = null){
        $data = UserAddressesTable::model()->fillData($data, false);
        if($user_id){
            $data['user_id'] = $user_id;
        }else if(empty($data['user_id'])){
            $data['user_id'] = \F::app()->current_user;
        }
        $data['create_time'] = \F::app()->current_time;
        return UserAddressesTable::model()->insert($data);
    }
    
    /**
     * 删除一个地址。
     * 直接根据地址ID物理删除，此函数不做权限验证。
     * @param int $address_id 地址ID
     * @return int
     */
    public function remove($address_id){
        return UserAddressesTable::model()->delete($address_id);
    }
    
    /**
     * 编辑一个地址。
     * 直接根据地址ID进行跟新，此函数不做权限验证
     * @param int $address_id
     * @param array $data 地址相关参数，此函数会对多余参数进行过滤，但不会进行校验
     * @return int
     */
    public function edit($address_id, $data){
        $data = UserAddressesTable::model()->fillData($data, false);
        return UserAddressesTable::model()->update($data, array(
            'address_id = ?'=>$address_id,
        ));
    }
    
    /**
     * 根据地址ID，将一个地址设为默认地址。
     * 该函数不会验证给定地址id是否为当前登录用户所有，但是会把所有此id对用user_id的其他地址标记为非默认地址
     * @param int $address_id 地址ID
     * @return bool
     */
    public function setDefault($address_id){
        $address = UserAddressesTable::model()->find($address_id);
        if(!$address){
            //指定地址ID不存在
            return false;
        }
        if($address['is_default']){
            //若该地址本来就是默认地址，直接返回true
            return true;
        }
        
        //将原来的默认地址置为非默认地址
        UserAddressesTable::model()->update(array(
            'is_default'=>0,
        ), array(
            'user_id = ' . $address['user_id'],
            'is_default = 1'
        ));
        
        UserAddressesTable::model()->update(array(
            'is_default'=>1,
        ), array(
            'address_id = ?'=>$address_id,
        ));
        
        return true;
    }
    
    public function get($address_id){
        return UserAddressesTable::model()->find($address_id);
    }
}