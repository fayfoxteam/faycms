<?php
namespace faypay\models;

use faypay\services\methods\PaymentMethodException;

/**
 * 包含必要的交易信息
 */
class PaymentTradeModel{
    /**
     * @var string 商户订单号
     */
    private $out_trade_no;
    
    /**
     * @var int 以“分”为单位的整数金额
     */
    private $total_fee;
    
    /**
     * @var string 交易标题
     */
    private $subject;
    
    /**
     * @var string 交易描述
     */
    private $body;
    
    /**
     * @var string 商品展示网址
     */
    private $show_url;
    
    /**
     * @var string 超时时间
     */
    private $it_b_pay;
    
    /**
     * @var string 附加数据，透传字段
     *  - 在微信支付里对应：attach
     *  - 在银联支付里对应：reqReserved
     *  - 在支付宝支付里没有这个值
     */
    private $attach;
    
    /**
     * @var string 订单生成时间（strtotime能识别的时间格式都行）
     */
    private $time_start;
    
    /**
     * @var string 订单失效时间（strtotime能识别的时间格式都行）
     */
    private $time_expire;
    
    /**
     * @var string 服务器异步通知页面路径
     */
    private $notify_url;
    
    /**
     * @var string 页面跳转同步通知页面路径
     */
    private $return_url;
    
    /**
     * @var int 交易支付记录ID
     * （并不属于支付需要用到的字段，但是做微信支付OAuth认证的时候需要做跳转，要用到这个字段）
     */
    private $trade_payment_id;
    
    /**
     * 判断传入字段是否都有值（不同支付方式，必选字段有所不同）。
     * 返回不满足条件的空字段一维数组，若返回空数组，代表验证成功。
     * @param array $fields
     * @param string $payment 支付方式，仅用于报错时明确错误
     * @return bool
     * @throws PaymentMethodException
     */
    public function checkRequiredField($fields, $payment){
        $empty_fields = array();
        foreach($fields as $f){
            if(!$this->{$f}){
                $empty_fields[] = $f;
            }
        }
        
        if($empty_fields){
            throw new PaymentMethodException($payment . '交易参数：字段[' . implode(', ', $empty_fields) . ']不能为空');
        }
        
        return true;
    }
    
    /**
     * @return string
     */
    public function getOutTradeNo()
    {
        return $this->out_trade_no;
    }
    
    /**
     * @param string $out_trade_no
     * @return $this
     */
    public function setOutTradeNo($out_trade_no)
    {
        $this->out_trade_no = $out_trade_no;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getTotalFee()
    {
        return $this->total_fee;
    }
    
    /**
     * @param int $total_fee
     * @return $this
     */
    public function setTotalFee($total_fee)
    {
        $this->total_fee = $total_fee;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getShowUrl()
    {
        return $this->show_url;
    }
    
    /**
     * @param string $show_url
     * @return $this
     */
    public function setShowUrl($show_url)
    {
        $this->show_url = $show_url;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getItBPay()
    {
        return $this->it_b_pay;
    }
    
    /**
     * @param string $it_b_pay
     * @return $this
     */
    public function setItBPay($it_b_pay)
    {
        $this->it_b_pay = $it_b_pay;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAttach()
    {
        return $this->attach;
    }
    
    /**
     * @param string $attach
     * @return $this
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTimeStart()
    {
        return $this->time_start;
    }
    
    /**
     * @param string $time_start
     * @return $this
     */
    public function setTimeStart($time_start)
    {
        $this->time_start = $time_start;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTimeExpire()
    {
        return $this->time_expire;
    }
    
    /**
     * @param string $time_expire
     * @return $this
     */
    public function setTimeExpire($time_expire)
    {
        $this->time_expire = $time_expire;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getNotifyUrl()
    {
        return $this->notify_url;
    }
    
    /**
     * @param string $notify_url
     * @return $this
     */
    public function setNotifyUrl($notify_url)
    {
        $this->notify_url = $notify_url;
        return $this;
    }
    
    /**
     * 获取同步跳转地址
     * @param array $params 若非空，则会在设置的链接地址后面带上参数
     * @return string
     */
    public function getReturnUrl($params = array())
    {
        if(!$params){
            return $this->return_url;
        }
        
        if(strpos($this->return_url, '?') === false){
            return $this->return_url . '?' . http_build_query($params);
        }else{
            return $this->return_url . '&' . http_build_query($params);
        }
    }
    
    /**
     * @param string $return_url
     * @return $this
     */
    public function setReturnUrl($return_url)
    {
        $this->return_url = $return_url;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getTradePaymentId()
    {
        return $this->trade_payment_id;
    }
    
    /**
     * @param int $trade_payment_id
     * @return $this
     */
    public function setTradePaymentId($trade_payment_id)
    {
        $this->trade_payment_id = $trade_payment_id;
        return $this;
    }
}