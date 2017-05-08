<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\forms\SettingForm;
use cms\models\tables\FilesTable;
use cms\services\file\FileService;
use cms\services\file\ImageService;
use cms\services\file\ImageTextService;
use cms\services\file\WeixinFileService;
use cms\services\SettingService;
use fay\core\Sql;
use fay\common\ListView;
use fay\helpers\LocalFileHelper;
use fay\helpers\ImageHelper;
use cms\services\file\QiniuService;
use fay\core\HttpException;
use fay\core\Validator;
use fay\core\Response;
use cms\models\tables\ActionlogsTable;
use cms\services\OptionService;
use cms\services\CategoryService;
use fay\helpers\StringHelper;

class FileController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'file';
    }
    
    /**
     * 不做限制，可以上传配置文件中允许的任何文件
     */
    public function upload(){
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
            $cat = CategoryService::service()->get($cat, 'id,alias');
            if(!$cat){
                throw new HttpException('指定的文件分类不存在');
            }
        }else{
            $cat = 0;
        }
        
        $private = !!$this->input->get('p');
        $result = FileService::service()->upload($cat, $private);
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
                    'message'=>'上传失败：'.implode("\r\n", $data),
                    'url'=>'',
                ));
            }
        }else{
            if($result['status']){
                Response::json($data);
            }else{
                Response::json(new \stdClass(), 0, '上传失败：'.implode("\r\n", $data));
            }
        }
    }
    
    public function uploadByBase64(){
        $validator = new Validator();
        $check = $validator->check(array(
            array('file', 'required'),
            array(array('x','y', 'dw', 'dh', 'w', 'h'), 'int'),
        ));
        
        if($check !== true){
            throw new HttpException('参数异常', 500);
        }
        
        set_time_limit(0);
        
        $cat = $this->input->request('cat');
        if($cat){
            $cat = CategoryService::service()->get($cat, 'id,alias');
            if(!$cat){
                throw new HttpException('指定的文件分类不存在');
            }
        }else{
            $cat = array(
                'id'=>0,
                'alias'=>'',
            );
        }
        
        $private = !!$this->input->get('p');
        $client_name = $this->input->post('client_name', 'trim', '');
        
        $file = @imagecreatefromstring(base64_decode($this->input->post('file')));
        if(!$file){
            throw new HttpException('上传文件格式错误', 500);
        }
        
        $target = $cat['alias'];
        if($target && substr($target, -1) != '/'){
            //目标路径末尾不是斜杠的话，加上斜杠
            $target .= '/';
        }
        $upload_path = $private ? './../uploads/' . APPLICATION . '/' . $target . date('Y/m/')
            : './uploads/' . APPLICATION . '/' . $target . date('Y/m/');
        //若指定目录不存在，则创建目录
        LocalFileHelper::createFolder($upload_path);
        $filename = FileService::getFileName($upload_path, '.jpg');
        if(defined('NO_REWRITE')){
            $destination = './public/'.$upload_path . $filename;
        }else{
            $destination = $upload_path . $filename;
        }
        
        imagejpeg($file, $destination);
        
        $data = array(
            'raw_name'=>substr($filename, 0, -4),
            'file_ext'=>'.jpg',
            'file_type'=>'image/jpeg',
            'file_size'=>filesize($destination),
            'file_path'=>$upload_path,
            'client_name'=>$client_name,
            'is_image'=>1,
            'image_width'=>imagesx($file),
            'image_height'=>imagesy($file),
            'upload_time'=>\F::app()->current_time,
            'user_id'=>\F::app()->current_user,
            'cat_id'=>$cat['id'],
        );
        $data['id'] = FilesTable::model()->insert($data);
        
        $data = $this->afterUpload($data);
        
        Response::json($data);
    }
    
    /**
     * 此接口仅允许上传图片
     */
    public function imgUpload(){
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
            $cat = CategoryService::service()->get($cat, 'id,alias');
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
    
    public function doUpload(){
        //获取文件类目树
        $this->view->cats = CategoryService::service()->getTree('_system_file');
        $this->layout->subtitle = '上传文件';
        $this->view->render();
    }
    
    public function remove(){
        if($file_id = $this->input->get('id', 'intval')){
            $file = FilesTable::model()->find($file_id);
            if($file['qiniu']){//如果已经上传到七牛，则先从七牛删除
                QiniuService::service()->delete($file);
            }
            
            FilesTable::model()->delete($file_id);
            @unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'] . $file['raw_name'] . $file['file_ext']);
            @unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'] . $file['raw_name'] . '-100x100' . $file['file_ext']);
            Response::notify('success', '删除成功');
        }else{
            Response::notify('error', '参数不完整');
        }
    }
    
    public function index(){
        $this->layout->subtitle = '文件';
        
        $this->layout->_setting_panel = '_setting_index';
        $_setting_key = 'admin_file_index';
        $_settings = SettingService::service()->get($_setting_key);
        $_settings || $_settings = array(
            'cols'=>array('client_name', 'file_type', 'file_size', 'username', 'upload_time'),
            'display_name'=>'username',
            'display_time'=>'short',
            'page_size'=>10,
        );
        
        //如果未配置七牛参数，则强制不显示七牛那一列
        if(!OptionService::getGroup('qiniu')){
            foreach($_settings['cols'] as $k => $v){
                if($v == 'qiniu'){
                    unset($_settings['cols'][$k]);
                    break;
                }
            }
        }
        
        $this->form('setting')->setModel(SettingForm::model())
            ->setJsModel('setting')
            ->setData($_settings)
            ->setData(array(
                '_key'=>$_setting_key,
            ));
        
        $this->view->cats = CategoryService::service()->getTree('_system_file');
        
        $sql = new Sql();
        $sql->from(array('f'=>'files'))
            ->joinLeft(array('u'=>'users'), 'u.id = f.user_id', 'username,nickname,realname')
            ->order('id DESC');
        
        if($this->input->get('keywords')){
            $sql->where(array('f.client_name LIKE ?'=>'%'.$this->input->get('keywords').'%'));
        }
        
        if($this->input->get('cat_id')){
            $sql->where(array('f.cat_id = ?'=>$this->input->get('cat_id', 'intval')));
        }

        if($this->input->get('qiniu') !== '' && $this->input->get('qiniu') !== null){
            $sql->where(array('f.qiniu = ?'=>$this->input->get('qiniu', 'intval')));
        }
        
        if($this->input->get('start_time')){
            $sql->where(array("f.upload_time > ?"=>$this->input->get('start_time', 'strtotime')));
        }
        if($this->input->get('end_time')){
            $sql->where(array("f.upload_time < ?"=>$this->input->get('end_time', 'strtotime')));
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size', 20),
            'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 3).'" align="center">无相关记录！</td></tr>',
        ));
        
        $this->view->render();
    }
    
    public function batch(){
        $ids = $this->input->post('ids', 'intval');
        $action = $this->input->post('batch_action');
        
        switch($action){
            case 'remove':
                $affected_rows = 0;
                foreach($ids as $id){
                    $file = FilesTable::model()->find($id);
                    if($file){
                        if($file['qiniu']){//如果已经上传到七牛，则先从七牛删除
                            QiniuService::service()->delete($file);
                        }
                            
                        FilesTable::model()->delete($id);
                        @unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'] . $file['raw_name'] . $file['file_ext']);
                        @unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'] . $file['raw_name'] . '-100x100' . $file['file_ext']);
                        $affected_rows++;
                    }
                }
                
                $this->actionlog(ActionlogsTable::TYPE_FILE, '批处理：'.$affected_rows.'个文件被删除');
                Response::notify('success', $affected_rows.'个文件被删除');
            break;
            
            //移动到目标分类图片
            case 'exchange':
                $cat_id = $this->input->post('cat_id', 'intval');
                
                if(!$cat_id){
                    Response::notify('error', '未指定分类');
                }
            
                $cat = CategoryService::service()->get($cat_id,'title');
                if(!$cat){
                    Response::notify('error', '指定分类不存在');
                }
                
                $affected_rows = FilesTable::model()->update(array(
                    'cat_id'=>$cat_id,
                ), array(
                    'id IN (?)'=>$ids,
                ));
                $this->actionlog(ActionlogsTable::TYPE_FILE, "批处理：{$affected_rows}个文件被移动到{$cat['title']}");
                Response::notify('success', "{$affected_rows}个文件被移动到分类{$cat['title']}");
            break;
        }
    }
    
    public function download(){
        if($file_id = $this->input->get('id', 'intval')){
            if($file = FilesTable::model()->find($file_id)){
                if(!$file['file_path'] && $file['weixin_server_id']){
                    //微信图片，还未下载到本地
                    header('location:' . WeixinFileService::getUrl($file['weixin_server_id']));
                }
                
                //可选下载文件名格式
                if($this->input->get('name') == 'date'){
                    $filename = date('YmdHis', $file['upload_time']).$file['file_ext'];
                }else if($this->input->get('name') == 'timestamp'){
                    $filename = $file['upload_time'].$file['file_ext'];
                }else if($this->input->get('name') == 'client_name'){
                    $filename = $file['client_name'];
                }else{
                    $filename = $file['raw_name'].$file['file_ext'];
                }
                
                FilesTable::model()->incr($file_id, 'downloads', 1);
                $data = file_get_contents((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
                if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
                    header('Content-Type: "'.$file['file_type'].'"');
                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header("Content-Transfer-Encoding: binary");
                    header('Pragma: public');
                    header("Content-Length: ".strlen($data));
                }else{
                    header('Content-Type: "'.$file['file_type'].'"');
                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                    header("Content-Transfer-Encoding: binary");
                    header('Expires: 0');
                    header('Pragma: no-cache');
                    header("Content-Length: ".strlen($data));
                }
                die($data);
            }else{
                throw new HttpException('文件不存在');
            }
        }else{
            throw new HttpException('参数不正确', 500);
        }
    }

    /**
     * 分类管理
     */
    public function cat(){
        $this->layout->current_directory = 'file';
        $this->layout->subtitle = '文件分类';
        $this->view->cats = CategoryService::service()->getTree('_system_file');
        $root_node = CategoryService::service()->getByAlias('_system_file', 'id');
        $this->view->root = $root_node['id'];
        if($this->checkPermission('cms/admin/link/cat-create')){
            $this->layout->sublink = array(
                'uri'=>'#create-cat-dialog',
                'text'=>'添加文件分类',
                'html_options'=>array(
                    'class'=>'create-cat-link',
                    'data-title'=>'文件分类',
                    'data-id'=>$root_node['id'],
                ),
            );
        }

        $this->view->render();
    }

    public function pic(){
        $validator = new Validator();
        $check = $validator->check(array(
            array(array('f'), 'required'),
            array(array('t'), 'range', array('range'=>array(
                FileService::PIC_ORIGINAL,
                FileService::PIC_THUMBNAIL,
                FileService::PIC_CROP,
                FileService::PIC_RESIZE,
                FileService::PIC_CUT
            ))),
            array(array('x','y', 'dw', 'dh', 'w', 'h'), 'int'),
        ));
        
        if($check !== true){
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            header('Content-type: image/png');
            readfile($spare);
            die;
        }
        
        //显示模式
        $t = $this->input->get('t', 'intval', 1);
        
        //文件名或文件id号
        $f = $this->input->get('f');
        if(StringHelper::isInt($f)){
            if($f == 0){
                //这里不直接返回图片不存在的提示，因为可能需要缩放，让后面的逻辑去处理
                $file = false;
            }else{
                $file = FilesTable::model()->find($f);
            }
        }else{
            $file = FilesTable::model()->fetchRow(array('raw = ?'=>$f));
        }

        if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $file['raw_name'] == $_SERVER['HTTP_IF_NONE_MATCH']){
            header('HTTP/1.1 304 Not Modified');
            die;
        }
        
        //设置缓存
        header("Expires: Sat, 26 Jul 2020 05:00:00 GMT");
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $file['upload_time']).' GMT');
        header("Cache-control: max-age=3600");
        header("Pragma: cache");
        header('Etag:'.$file['raw_name']);
        
        switch ($t) {
            case FileService::PIC_ORIGINAL:
                //直接输出图片
                $this->_pic($file);
                break;
            case FileService::PIC_THUMBNAIL:
                //输出图片的缩略图
                $this->_thumbnail($file);
                break;
            case FileService::PIC_CROP:
                /**
                 * 根据起始坐标，宽度及宽高比裁剪后输出图片
                 * @param $_GET['x'] 起始点x坐标
                 * @param $_GET['y'] 起始点y坐标
                 * @param $_GET['dw'] 输出图像宽度
                 * @param $_GET['dh'] 输出图像高度
                 * @param $_GET['w'] 截图图片的宽度
                 * @param $_GET['h'] 截图图片的高度
                 */
                $this->_crop($file);
                break;
            case FileService::PIC_RESIZE:
                /**
                 * 根据给定的宽高对图片进行裁剪后输出图片
                 * @param $_GET['dw'] 输出图像宽度
                 * @param $_GET['dh'] 输出图像高度
                 * 若仅指定高度或者宽度，则会按比例缩放
                 * 若均不指定，则默认输出原图
                 */
                $this->_resize($file);
                break;
            case FileService::PIC_CUT:
                /**
                 * 根据给定的宽高对图片进行裁剪后输出图片
                 * @parameter $_GET['dw'] 输出图像宽度
                 * @parameter $_GET['dh'] 输出图像高度
                 * 若未指定高度或者宽度，则会取文件实际宽高（这与RESIZE不同）
                 * 若均不指定，则默认输出原图
                 */
                $this->_cut($file);
                break;
        
            default:
                ;
                break;
        }
    }
    
    private function _pic($file){
        if($file !== false){
            //出于性能考虑，这里不会去判断物理文件是否存在（除非服务器挂了，否则肯定存在）
            header('Content-type: '.$file['file_type']);
            readfile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
        }else{
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            header('Content-type: image/png');
            readfile($spare);
        }
    }
    
    private function _thumbnail($file){
        if($file !== false){
            header('Content-type: '.$file['file_type']);
            readfile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'-100x100' . $file['file_ext']);
        }else{
            $spare = $this->config->get($this->input->get('s', 'trim', 'thumbnail'), 'noimage');
            $spare || $spare = $this->config->get('thumbnail', 'noimage');
            header('Content-type: image/png');
            readfile($spare);
        }
    }
    
    private function _crop($file){
        //x坐标位置
        $x = $this->input->get('x', 'intval', 0);
        //y坐标
        $y = $this->input->get('y', 'intval', 0);
        //输出宽度
        $dw = $this->input->get('dw', 'intval', 0);
        //输出高度
        $dh = $this->input->get('dh', 'intval', 0);
        //选中部分的宽度
        $w = $this->input->get('w', 'intval');
        if(!$w)throw new HttpException('不完整的请求', 500);
        //选中部分的高度
        $h = $this->input->get('h', 'intval');
        if(!$h)throw new HttpException('不完整的请求', 500);
        
        if($file !== false){
            $img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
        
            if($dw == 0){
                $dw = $w;
            }
            if($dh == 0){
                $dh = $h;
            }
            $img = ImageHelper::crop($img, $x, $y, $w, $h);
            $img = ImageHelper::resize($img, $dw, $dh);

            ImageHelper::output($img, $file['file_type']);
        }else{
            //图片不存在，显示一张默认图片吧
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            $img = ImageHelper::getImage($spare);
            header('Content-type: image/jpeg');
            $img = ImageHelper::resize($img, $dw ? $dw : 325, $dh ? $dh : 235);
            imagejpeg($img);
        }
    }
    
    private function _resize($file){
        //输出宽度
        $dw = $this->input->get('dw', 'intval');
        //输出高度
        $dh = $this->input->get('dh', 'intval');
        
        if($dw && !$dh){
            $dh = $dw * ($file['image_height'] / $file['image_width']);
        }else if($dh && !$dw){
            $dw = $dh * ($file['image_width'] / $file['image_height']);
        }else if(!$dw && !$dh){
            $dw = $file['image_width'];
            $dh = $file['image_height'];
        }
        
        if($file !== false){
            $img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
            
            $img = ImageHelper::resize($img, $dw, $dh);

            ImageHelper::output($img, $file['file_type']);
        }else{
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            $img = ImageHelper::getImage($spare);
            header('Content-type: image/jpeg');
            $img = ImageHelper::resize($img, $dw ? $dw : 325, $dh ? $dh : 235);
            imagejpeg($img);
        }
    }
    
    private function _cut($file){
        //输出宽度
        $dw = $this->input->get('dw', 'intval');
        //输出高度
        $dh = $this->input->get('dh', 'intval');
        
        if($file !== false){
            $dw || $dw = $file['image_width'];
            $dh || $dh = $file['image_height'];
            
            $img = ImageHelper::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
            
            $img = ImageHelper::resize($img, $dw, $dh);

            ImageHelper::output($img, $file['file_type']);
        }else{
            $spare = $this->config->get($this->input->get('s', 'trim', 'default'), 'noimage');
            $spare || $spare = $this->config->get('default', 'noimage');
            $img = ImageHelper::getImage($spare);
            header('Content-type: image/jpeg');
            $img = ImageHelper::resize($img, $dw ? $dw : 325, $dh ? $dh : 235);
            imagejpeg($img);
        }
    }

    /**
     * 水印图预览
     */
    public function watermarkPreview(){
        $this->form()->setScene('final')
            ->setRules(array(
                array(array('type'), 'range', array('range'=>array('text', 'image'))),
                array(array('watermark', 'size'), 'int', array('min'=>1)),
                array(array('margin'), 'int'),
                array(array('opacity'), 'int', array('min'=>0, 'max'=>100)),
            ))->setFilters(array(
                'type'=>'trim',
                'watermark'=>'intval',
                'margin'=>'trim',
                'size'=>'intval',
            ))->setLabels(array(
                'type'=>'水印类型',
                'watermark'=>'水印图ID',
                'margin'=>'边距',
                'size'=>'字号',
                'opacity'=>'透明度'
            ))->check();
        
        $margin = $this->form()->getData('margin', '0,10,10,0');
        $align = array(
            $this->form()->getData('align', 'right'),
            $this->form()->getData('valign', 'bottom'),
        );
        if($this->form()->getData('type') == 'text'){
            //文本水印
            $image = new ImageTextService(BASEPATH . 'assets/images/demo-image.jpg');
            $text = $this->form()->getData('text', 'faycms.com');
            $image->write(
                $text,
                BASEPATH . 'assets/fonts/msyh.ttc',
                $this->form()->getData('size', 20),
                $this->form()->getData('color', '#FFFFFF'),
                $align,
                $margin,
                1.5,
                0,
                0,
                30
            );
        }else{
            //图片水印
            $image = new ImageService(BASEPATH . 'assets/images/demo-image.jpg');
            $image->merge(
                $this->form()->getData('watermark', BASEPATH . 'assets/images/watermark.png'),
                $margin,
                $align,
                $this->form()->getData('opacity', 30)
            );
        }
        $image->output();
    }
}