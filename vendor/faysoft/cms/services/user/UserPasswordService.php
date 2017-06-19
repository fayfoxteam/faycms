<?php
namespace cms\services\user;
    
    use cms\models\tables\UsersTable;
    use fay\core\Loader;
    use fay\core\Service;
    use fay\helpers\StringHelper;

    class UserPasswordService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 根据明码，得到一个加密后的密码和混淆码
     * @param $password
     * @return array
     */
    public function generate($password){
        $salt = StringHelper::random('alnum', 5);
        return array(
            $salt,
            md5(md5($password) . $salt),
        );
    }
    
    /**
     * 验证指定的用户名密码是否匹配
     * @param string $username
     * @param string $password
     * @param bool $admin 若为true，则限定为管理员登录（管理员也可以登录前台，但前后台的Session空间是分开的）
     * @return array
     */
    public function checkPassword($username, $password, $admin = false){
        if(!$username){
            return array(
                'status'=>0,
                'message'=>'用户名不能为空',
                'error_code'=>'username:can-not-be-empty',
            );
        }
        if(!$password){
            return array(
                'status'=>0,
                'message'=>'密码不能为空',
                'error_code'=>'password:can-not-be-empty',
            );
        }
        
        $user = UsersTable::model()->fetchRow(array(
            'username = ?'=>$username,
            'delete_time = 0',
        ), 'id,password,salt,block,status,admin');
        
        //判断用户名是否存在
        if(!$user){
            return array(
                'user_id'=>0,
                'message'=>'用户名不存在',
                'error_code'=>'username:not-exist',
            );
        }
        $password = md5(md5($password).$user['salt']);
        if($password != $user['password']){
            return array(
                'user_id'=>0,
                'message'=>'密码错误',
                'error_code'=>'password:not-match',
            );
        }
        
        if($admin && $user['admin'] != $admin){
            return array(
                'user_id'=>0,
                'message'=>'您不是管理员，不能登陆！',
                'error_code'=>'not-admin',
            );
        }
        
        return array(
            'user_id'=>$user['id'],
            'message'=>'',
            'error_code'=>'',
        );
    }
}