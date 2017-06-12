<?php
namespace baike\modules\api\controllers;

use baike\library\ApiController;
use cms\services\CategoryService;
use cms\services\file\FileService;
use fay\core\HttpException;
use fay\core\Response;
use fay\core\Validator;

class FileController extends ApiController{
    /**
     * 此接口仅允许上传图片
     */
    public function imgUpload(){
        $this->checkLogin();
        
        $validator = new Validator();
        $check = $validator->check(array(
            array(array('x','y', 'dw', 'dh', 'w', 'h'), 'int'),
        ));

        if($check !== true){
            throw new HttpException('参数异常');
        }

        set_time_limit(0);

        $cat = $this->input->request('cat');
        if($cat){
            $cat = CategoryService::service()->get('_system_file_' . $cat, 'id,alias');
            if(!$cat){
                throw new HttpException('指定的文件分类不存在');
            }
        }else{
            $cat = 0;
        }

        $private = !!$this->input->get('p');
        $result = FileService::service()->upload($cat, $private, array('gif', 'jpg', 'jpeg', 'jpe', 'png'));
        $data = $result['data'];

        if($result['status']){
            $data = $this->afterUpload($data);
        }

        if($this->input->request('CKEditorFuncNum')){
            if($result['status']){
                echo "<script>window.parent.CKEDITOR.tools.callFunction({$this->input->request('CKEditorFuncNum')}, '{$data['src']}', '');</script>";
            }else{
                echo '<script>common.alert("' . implode("\r\n", $data) . '");</script>';
            }
        }else if($this->input->request('guid')){
            if($result['status']){
                echo json_encode(array(
                    'success'=>1,
                    'message'=>'上传成功',
                    'url'=>$data['src'],
                ));
            }else{
                echo json_encode(array(
                    'success'=>0,
                    'message'=>'上传失败',
                    'url'=>'',
                ));
            }
        }else{
            Response::json($data);
        }
    }

    /**
     * 文件上传后的额外处理（例如裁剪、缩放等）
     * @param array $data 文件信息
     * @return array
     */
    private function afterUpload($data){
        //如果是图片，可能要缩放/裁剪处理
        if($data['is_image']){
            switch($this->input->request('handler')){
                case 'resize':
                    $data = FileService::service()->edit($data, 'resize', array(
                        'dw'=>$this->input->request('dw', 'intval'),
                        'dh'=>$this->input->request('dh', 'intval'),
                    ));
                    break;
                case 'crop':
                    $params = array(
                        'x'=>$this->input->request('x', 'intval'),
                        'y'=>$this->input->request('y', 'intval'),
                        'w'=>$this->input->request('w', 'intval'),
                        'h'=>$this->input->request('h', 'intval'),
                        'dw'=>$this->input->request('dw', 'intval'),
                        'dh'=>$this->input->request('dh', 'intval'),
                    );
                    if($params['x'] && $params['y'] && $params['w'] && $params['h']){
                        //若参数不完整，则不裁剪
                        $data = FileService::service()->edit($data, 'crop', $params);
                    }
                    break;
            }
        }
        return $data;
    }
}