<?php
namespace cms\services\file;

use cms\models\tables\FilesTable;
use cms\services\OptionService;
use fay\core\ErrorException;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\NumberHelper;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class QiniuService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 根据本地文件ID，将本地文件上传至七牛云空间
     * @param int $file 文件ID，若为0，则获取最老的未上传到七牛的文件
     * @return array
     * @throws ErrorException
     */
    public function put($file = 0){
        if(!$file){
            //若$file_id为0，则尝试去files表获取老的一条weixin_server_id非空的数据
            $file = FilesTable::model()->fetchRow(array(
                'qiniu = 0'
            ), '*', 'id');
            if(!$file){
                return array(
                    'status'=>0,
                    'message'=>'没有需要上传的文件',
                );
            }
        }else if(NumberHelper::isInt($file)){
            $file = FilesTable::model()->find($file);
        }
        
        $qiniu_config = OptionService::getGroup('qiniu');
        if(!$qiniu_config['accessKey'] || !$qiniu_config['secretKey'] || !$qiniu_config['bucket']){
            throw new ErrorException('尝试上传文件到七牛，但七牛参数未配置');
        }
        
        // 构建鉴权对象
        $auth = new Auth($qiniu_config['accessKey'], $qiniu_config['secretKey']);
        
        // 生成上传 Token
        $token = $auth->uploadToken($qiniu_config['bucket']);
        
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile(
            $token,
            $this->getKey($file),
            FileService::getPath($file)
        );
        
        if($err !== null){
            return array(
                'status'=>0,
                'message'=>$err->message(),
            );
        }else{
            FilesTable::model()->update(array(
                'qiniu'=>1,
            ), $file['id']);
            return array(
                'status'=>1,
                'data'=>$ret,
                'file'=>$file,
            );
        }
    }
    
    /**
     * 根据本地文件ID，删除对应七牛空间的文件
     * @param $file
     * @return bool
     */
    public function delete($file){
        if(NumberHelper::isInt($file)){
            $file = FilesTable::model()->find($file, 'id,raw_name,file_ext,file_path');
        }
        
        $qiniu_config = OptionService::getGroup('qiniu');
        
        // 构建鉴权对象
        $auth = new Auth($qiniu_config['accessKey'], $qiniu_config['secretKey']);
        
        //初始化BucketManager
        $bucketMgr = new BucketManager($auth);
        
        $err = $bucketMgr->delete($qiniu_config['bucket'], $this->getKey($file));
        
        if($err !== null){
            return $err->message();
        }else{
            return true;
        }
    }
    
    /**
     * 根据本地文件信息，获取七牛对应的文件路径
     * 若文件未被上传，返回false
     * 若传入宽高参数，则会调用七牛相应接口进行处理
     *
     * @param int|array $file 若为数字，视为files表ID；若为数组，直接使用
     * @param array $options 包含宽高参数，若文件非图片，宽高参数无效
     * @return bool|string
     */
    public function getUrl($file, $options = array()){
        if(NumberHelper::isInt($file)){
            $file = FilesTable::model()->find($file, 'raw_name,file_ext,file_path,is_image,image_width,image_height,qiniu');
        }
        
        if(!$file['qiniu']){
            return false;
        }
        $domain = OptionService::get('qiniu:domain');
        $src = $domain . $this->getKey($file);
        
        if($file['is_image'] && (!empty($options['dw']) || !empty($options['dh']))){
            //由于七牛的缩放机制与系统不同，所以直接计算好宽高传过去，不让七牛自动算
            empty($options['dw']) && $options['dw'] = intval($options['dh'] * ($file['image_width'] / $file['image_height']));
            empty($options['dh']) && $options['dh'] = intval($options['dw'] * ($file['image_height'] / $file['image_width']));
            
            $src .= "?imageView2/1/w/{$options['dw']}/h/{$options['dh']}";//裁剪
        }
        
        return $src;
    }
    
    /**
     * 获取一个七牛上的key
     * @param array $file 必须包含file_path, raw_name, file_ext三项
     * @return string
     */
    private function getKey($file){
        if(substr($file['file_path'], 0, 4) == './..'){
            return 'pri/'.substr($file['file_path'], strpos($file['file_path'], '/', 3)+1).$file['raw_name'].$file['file_ext'];
        }else{
            return substr($file['file_path'], strpos($file['file_path'], '/', 2)+1).$file['raw_name'].$file['file_ext'];
        }
    }
}