<?php
namespace faywiki\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use faywiki\models\tables\WikiDocSharesTable;
use faywiki\services\doc\DocService;
use faywiki\services\doc\DocShareService;

/**
 * 文档点赞
 */
class DocShareController extends ApiController{
    /**
     * 点赞
     * @parameter int $doc_id 文档ID
     * @parameter string $trackid 追踪ID
     */
    public function add(){
        //表单验证
        $this->form()->setModel(WikiDocSharesTable::model())->check();

        $doc_id = $this->form()->getData('doc_id');

        if(!DocService::isDocIdExist($doc_id)){
            Response::notify(Response::NOTIFY_FAIL, array(
                'message'=>"指定文档ID[{$doc_id}]不存在",
                'code'=>'invalid-parameter:doc_id-is-not-exist',
            ));
        }

        DocShareService::add(
            $doc_id,
            $this->form()->getData('type', ''),
            $this->form()->getData('trackid', '')
        );

        Response::notify(Response::NOTIFY_SUCCESS, '分享成功');
    }
}