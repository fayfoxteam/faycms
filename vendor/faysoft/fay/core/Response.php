<?php
namespace fay\core;

use cms\services\FlashService;
use fay\helpers\UrlHelper;

class Response{
    const FORMAT_RAW = 'raw';
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_JSONP = 'jsonp';

    /**
     * 消息 - 成功
     */
    const NOTIFY_SUCCESS = 'success';

    /**
     * 消息 - 失败
     */
    const NOTIFY_FAIL = 'fail';

    /**
     * 事件 - 发送前
     */
    const EVENT_BEFORE_SEND = 'response_before_send';
    
    /**
     * 事件 - 发送后
     */
    const EVENT_AFTER_SEND = 'response_after_send';
    
    /**
     * HTTP状态码
     */
    public static $http_statuses = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        118 => 'Connection timed out',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        210 => 'Content Different',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range unsatisfiable',
        417 => 'Expectation failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable entity',
        423 => 'Locked',
        424 => 'Method failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway or Proxy Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        507 => 'Insufficient storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    );

    /**
     * @var int HTTP状态码
     */
    protected $status_code = 200;

    /**
     * @var int HTTP状态码
     */
    protected $status_text = 'OK';

    /**
     * 返回数据格式
     */
    protected $format;

    /**
     * 当format为json或jsonp时候，$data为源数据，$content为json_encode后的数据
     */
    protected $data;

    /**
     * 返回数据
     */
    protected $content;

    /**
     * HTTP头
     */
    protected $headers = array();
    
    /**
     * @var bool 是否已发送
     */
    protected $is_sent = false;

    /**
     * @var string HTTP版本
     */
    protected $version;

    public function __construct(){
        if($this->version === null){
            if(Request::getServer('SERVER_PROTOCOL') === 'HTTP/1.0'){
                $this->version = '1.0';
            }else{
                $this->version = '1.1';
            }
        }
    }

    /**
     * Sets the response status code.
     * This method will set the corresponding status text if `$text` is null.
     * @param int $code the status code
     * @param string $text the status text. If not set, it will be set automatically based on the status code.
     * @return $this
     */
    public function setStatusCode($code, $text = null){
        if($code === null){
            $code = 200;
        }
        
        if($code < 100 || $code >= 600){
            throw new \InvalidArgumentException("非法的HTTP状态码[{$code}]");
        }
        
        $this->status_code = $code;
        if($text === null){
            $this->status_text = isset(static::$http_statuses[$this->status_code]) ? static::$http_statuses[$this->status_code] : '';
        } else {
            $this->status_text = $text;
        }
        return $this;
    }

    /**
     * 设置返回数据格式
     * @param string $format
     * @return $this
     * @throws \ErrorException
     */
    public function setFormat($format){
        if($format != self::FORMAT_HTML &&
            $format != self::FORMAT_JSON &&
            $format != self::FORMAT_JSONP &&
            $format != self::FORMAT_RAW
        ){
            throw new \ErrorException("非法的返回格式[{$format}]");
        }
        
        $this->format = $format;
        return $this;
    }

    /**
     * 获取format
     * @return string
     */
    public function getFormat(){
        return $this->format;
    }

    /**
     * 当返回为json或jsonp时
     * @param mixed $data
     * @return $this
     */
    public function setData($data){
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData(){
        return $this->data;
    }

    /**
     * 设置返回文本
     * @param string $content
     * @return $this
     */
    public function setContent($content){
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(){
        return $this->content;
    }

    /**
     * 清空类
     */
    public function clear(){
        $this->headers = array();
        $this->status_code = 200;
        $this->status_text = 'OK';
        $this->content = null;
        $this->is_sent = false;
    }

    /**
     * 页面跳转
     * @param string $uri
     * @param array $params
     * @param string $anchor 锚点，仅当$uri非空且不是完整url时有效
     * @param int $status_code HTTP状态码
     * @return $this
     */
    public function redirect($uri = null, $params = array(), $anchor = '', $status_code = 302){
        if($uri === null){
            //跳转到首页
            $this->setHeader('location', UrlHelper::createUrl());
        }else if(preg_match('/^(http|https):\/\/\w+.*$/', $uri)){
            //指定了一个完整的url，跳转到指定url
            $this->setHeader('location', $uri);
        }else{
            $this->setHeader('location', UrlHelper::createUrl($uri, $params, $anchor));
        }
        
        $this->setStatusCode($status_code);
        
        return $this;
    }

    /**
     * 设置HTTP头
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setHeader($name, $value){
        $this->headers[strtolower($name)] = $value;
        return $this;
    }

    /**
     * 输出响应
     */
    public function send(){
        if($this->is_sent){
            return;
        }
        
        \F::event()->trigger(self::EVENT_BEFORE_SEND, array(
            'response'=>$this,
        ));
        $this->prepare();
        $this->sendHeaders();
        $this->sendContent();
        \F::event()->trigger(self::EVENT_AFTER_SEND, array(
            'respo
            nse'=>$this,
        ));
        $this->is_sent = true;
    }

    /**
     * 发送前的格式化工作
     */
    protected function prepare(){
        if($this->format == self::FORMAT_JSON){
            $this->setHeader('Content-Type', 'application/json; charset=utf-8');
            $this->content = json_encode($this->data);
        }else if($this->format == self::FORMAT_JSONP){
            $this->setHeader('Content-Type', 'application/javascript; charset=utf-8');
            $this->content = $this->data['callback'] . '(' . json_encode($this->data['data']) . ');';
        }
    }

    /**
     * 发送HTTP头
     */
    protected function sendHeaders(){
        foreach($this->headers as $name => $value) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            header("$name: $value");
        }
        
        header("HTTP/{$this->version} {$this->status_code} {$this->status_text}");
    }

    /**
     * 发送内容（若配置有缓存，则会自动设置缓存）
     */
    protected function sendContent(){
        $router = Uri::getInstance()->router;

        //根据router设置缓存
        $cache_routers = \F::config()->get('*', 'pagecache');
        $cache_routers_keys = array_keys($cache_routers);
        if(in_array($router, $cache_routers_keys)){
            $filename = md5(\F::config()->get('base_url') . json_encode(\F::input()->get(isset($cache_routers[$router]['params']) ? $cache_routers[$router]['params'] : array())));
            $cache_key = 'pages/' . $router . '/' . $filename;
            if(\F::input()->post()){
                //有post数据的时候，是否更新页面
                if(isset($cache_routers[$router]['on_post'])){
                    if($cache_routers[$router]['on_post'] == 'rebuild'){//刷新缓存
                        \F::cache()->set($cache_key, $this->content, $cache_routers[$router]['ttl']);
                    }else if($cache_routers[$router]['on_post'] == 'remove'){//删除缓存
                        \F::cache()->delete($cache_key);
                    }
                }
            }else{
                //没post数据的时候，直接重新生成页面缓存
                \F::cache()->set($cache_key, $this->content, $cache_routers[$router]['ttl']);
            }
        }
        
        echo $this->content;
    }

    /**
     * 获取一个Response实例，优先获取Controller中的Response实例，若Controller未初始化，则new一个Response返回
     */
    public static function getInstance(){
        $app = \F::app();
        return $app ? $app->response : new Response();
    }
    
    /**
     * 返回上一页
     */
    public static function goback(){
        if(isset($_SERVER['HTTP_REFERER'])){
            self::getInstance()->redirect($_SERVER['HTTP_REFERER'])
                ->send();
        }else{
            self::getInstance()->setContent('<script>history.go(-1);</script>')
                ->send();
        }
    }

    /**
     * 在非显示性页面调用此方法输出。
     * 若为ajax访问，则返回json
     * 若是浏览器访问，则设置flash后跳转
     * @param string $status 状态success, error
     * @param array|string $data
     * @param bool|array $redirect 跳转地址，若为true且是浏览器访问，则返回上一页。若为false，则不会跳转。若非布尔型，则视为跳转地址进行跳转
     * @return JsonResponse
     */
    public static function notify($status = self::NOTIFY_SUCCESS, $data = array(), $redirect = true){
        if(!is_array($data)){
            $data = array(
                'message'=>$data,
            );
        }
        if(Request::isAjax()){
            return Response::json(
                isset($data['data']) ? $data['data'] : '',
                $status == self::NOTIFY_SUCCESS ? 1 : 0,
                isset($data['message']) ? $data['message'] : '',
                isset($data['code']) ? $data['code'] : ''
            );
        }else{
            if(!empty($data['message'])){
                //若设置了空 的message，则不发flash
                FlashService::set($data['message'], $status);
            }else if(self::NOTIFY_SUCCESS){
                FlashService::set('操作成功', $status);
            }else{
                FlashService::set('操作失败', $status);
            }

            if($redirect === true){
                self::goback();
            }else if($redirect !== false){
                if(is_array($redirect)){
                    $redirect = UrlHelper::createUrl(
                        $redirect[0],
                        empty($redirect[1]) ? array() : $redirect[1],
                        empty($redirect[1]) ? '' : $redirect[1]
                    );
                }
                self::getInstance()->redirect($redirect)
                    ->send();
            }
        }
    }

    /**
     * 用一个单页来做信息提示，并在$delay时间后跳转
     * @param string $message 信息
     * @param string $status
     * @param bool|string $redirect 跳转的url，默认为回到上一页
     * @param int $delay 停留时间
     */
    public static function jump($message, $status = 'success', $redirect = false, $delay = 3){
        if(!$redirect && !empty($_SERVER['HTTP_REFERER'])){
            $redirect = $_SERVER['HTTP_REFERER'];
        }
        \F::app()->view->renderPartial('common/jump', array(
            'redirect'=>$redirect,
            'status'=>$status,
            'message'=>$message,
            'delay'=>$delay,
        ));
        die;
    }
    
    /**
     * 返回json
     * @param mixed $data
     * @param int $status 1代表成功，0代表失败。（无其它状态，错误描述放$error_code）
     * @param string $message 错误描述。人类可读的描述，一般用于弹窗报错，例如：用户名不能为空！
     * @param string $code 错误码。用有意义的英文描述组成，但不是给人看的，是给程序确定错误用的。例如：username:can-not-be-empty
     * @return JsonResponse
     */
    public static function json($data = '', $status = 1, $message = '', $code = ''){
        return new JsonResponse($data, $status, $message, $code);
    }

    /**
     * 返回jsonp
     * @param string $func jsonp请求的回调函数名，在调用的地方，从请求中获取，例如jquery发送的请求：$func = $this->input->get('callback');！
     * @param mixed $data 内容部分
     * @param int $status 1代表成功，0代表失败。（无其它状态，错误描述放$error_code）
     * @param string $message 错误描述。人类可读的描述，一般用于弹窗报错，例如：用户名不能为空！
     * @param string $code 错误码。用有意义的英文描述组成，但不是给人看的，是给程序确定错误用的。例如：username:can-not-be-empty
     * @return JsonResponse
     */
    public static function jsonp($func, $data, $status = 1, $message = '', $code = ''){
        $json_response = new JsonResponse($data, $status, $message, $code);
        $json_response->setCallback($func);
        
        return $json_response;
    }

    /**
     * 清除所有未输出的缓冲区
     */
    public function clearOutput(){
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }
}