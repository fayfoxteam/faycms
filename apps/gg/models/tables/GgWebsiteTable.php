<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * web站点表
 * 
 * @property int $id Id
 * @property int $cat_sid 行业分类
 * @property int $cat_bid 模板分类
 * @property int $merchant_id 所属管理员站点（只关联主账号）
 * @property string $domain 自动生成的二级域名前缀
 * @property string $name 站点名称
 * @property int $thumbnail 网站缩略图
 * @property string $scope 业务范围
 * @property string $address 联系地址
 * @property string $phone 联系电话
 * @property string $company_name Company Name
 * @property string $company_pinyin Company Pinyin
 * @property string $company_suffix Company Suffix
 * @property int $page_num 站点下的页面数量
 * @property int $domain_num 站点绑定的域名数量
 * @property int $end_time 站点到期时间   -1永久使用
 * @property string $msg 站点说明  关闭时候的提示文字
 * @property int $status 站点状态:0关闭,1未发布,2已发布
 * @property int $is_enable 是否开启:0代表欠费关闭的站点
 * @property int $sort 排序  会员账号等级下降 关闭排在后面的站点
 * @property string $seo_title Seo Title
 * @property string $seo_keywords Seo Keywords
 * @property string $seo_description Seo Description
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property int $is_guide Is Guide
 * @property string $company_short_name Company Short Name
 * @property string $company_intro Company Intro
 * @property string $company_logo Company Logo
 * @property string $email Email
 * @property string $qq Qq
 * @property string $weibo Weibo
 * @property string $qrcode Qrcode
 * @property string $weibo_url Weibo Url
 * @property string $weixin Weixin
 */
class GgWebsiteTable extends Table{
    protected $_name = 'gg_website';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('page_num', 'domain_num'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('cat_sid', 'cat_bid', 'merchant_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('domain', 'address', 'company_name', 'seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
            array(array('name', 'phone', 'company_pinyin', 'company_suffix'), 'string', array('max'=>32)),
            array(array('scope', 'company_short_name', 'company_logo', 'weibo', 'qrcode', 'weibo_url', 'weixin'), 'string', array('max'=>50)),
            array(array('qq'), 'string', array('max'=>20)),
            array(array('is_enable', 'is_guide'), 'range', array('range'=>array(0, 1))),
            array(array('end_time'), 'datetime'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'cat_sid'=>'行业分类',
            'cat_bid'=>'模板分类',
            'merchant_id'=>'所属管理员站点（只关联主账号）',
            'domain'=>'自动生成的二级域名前缀',
            'name'=>'站点名称',
            'thumbnail'=>'网站缩略图',
            'scope'=>'业务范围',
            'address'=>'联系地址',
            'phone'=>'联系电话',
            'company_name'=>'Company Name',
            'company_pinyin'=>'Company Pinyin',
            'company_suffix'=>'Company Suffix',
            'page_num'=>'站点下的页面数量',
            'domain_num'=>'站点绑定的域名数量',
            'end_time'=>'站点到期时间   -1永久使用',
            'msg'=>'站点说明  关闭时候的提示文字',
            'status'=>'站点状态:0关闭,1未发布,2已发布',
            'is_enable'=>'是否开启:0代表欠费关闭的站点',
            'sort'=>'排序  会员账号等级下降 关闭排在后面的站点',
            'seo_title'=>'Seo Title',
            'seo_keywords'=>'Seo Keywords',
            'seo_description'=>'Seo Description',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'is_guide'=>'Is Guide',
            'company_short_name'=>'Company Short Name',
            'company_intro'=>'Company Intro',
            'company_logo'=>'Company Logo',
            'email'=>'Email',
            'qq'=>'Qq',
            'weibo'=>'Weibo',
            'qrcode'=>'Qrcode',
            'weibo_url'=>'Weibo Url',
            'weixin'=>'Weixin',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'cat_sid'=>'intval',
            'cat_bid'=>'intval',
            'merchant_id'=>'intval',
            'domain'=>'trim',
            'name'=>'trim',
            'thumbnail'=>'intval',
            'scope'=>'trim',
            'address'=>'trim',
            'phone'=>'trim',
            'company_name'=>'trim',
            'company_pinyin'=>'trim',
            'company_suffix'=>'trim',
            'page_num'=>'intval',
            'domain_num'=>'intval',
            'end_time'=>'trim',
            'msg'=>'',
            'status'=>'intval',
            'is_enable'=>'intval',
            'sort'=>'intval',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
            'updated_at'=>'',
            'created_at'=>'',
            'is_guide'=>'intval',
            'company_short_name'=>'trim',
            'company_intro'=>'',
            'company_logo'=>'trim',
            'email'=>'trim',
            'qq'=>'trim',
            'weibo'=>'trim',
            'qrcode'=>'trim',
            'weibo_url'=>'trim',
            'weixin'=>'trim',
        );
    }
}