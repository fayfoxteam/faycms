<?php
namespace fay\core;

class JsonResponse{
    /**
     * @var int HTTP状态码
     */
    protected $http_code = 200;

    /**
     * 状态，只有0，1两个取值
     * @var int
     */
    protected $status = 1;

    /**
     * @var string 错误描述。人类可读的描述，一般用于弹窗报错，例如：用户名不能为空！
     */
    protected $message = '';

    /**
     * @var string 错误码。用有意义的英文描述组成，但不是给人看的，是给程序确定错误用的。例如：username:can-not-be-empty
     */
    protected $code = '';

    /**
     * @var mixed
     */
    protected $data = '';

    /**
     * @var null|string JSONP的回调函数，若为空，则返回JSON
     */
    protected $callback;
    
    public function __construct($data = '', $status = 1, $message = '', $code = ''){
        $this->data = $data;
        $this->setStatus($status);
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * 获取数据
     * @return array
     */
    public function toArray(){
        return array(
            'status'=>$this->status,
            'data'=>$this->data,
            'message'=>$this->message,
            'code'=>$this->code,
        );
    }

    /**
     * @return int
     */
    public function getHttpCode(){
        return $this->http_code;
    }

    /**
     * @param int $http_code
     * @return $this
     */
    public function setHttpCode($http_code){
        if(!isset(Response::$http_statuses[$http_code])){
            throw new \InvalidArgumentException("非法的HTTP状态码[{$http_code}]");
        }
        $this->http_code = $http_code;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status){
        $this->status = $status ? 1 : 0;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(){
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message){
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code){
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data){
        $this->data = $data;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCallback(){
        return $this->callback;
    }

    /**
     * @param null|string $callback
     * @return $this
     */
    public function setCallback($callback){
        $this->callback = $callback;
        return $this;
    }
}