<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 站点扩展信息表
 * 
 * @property int $website_id Website Id
 * @property string $seo_title Seo Title
 * @property string $seo_keywords Seo Keywords
 * @property string $seo_description Seo Description
 * @property int $is_guide 是否进行过引导
 * @property string $scope 业务范围
 * @property string $company_short_name Company Short Name
 * @property string $company_intro 公司简介
 * @property int $company_logo Company Logo
 * @property string $company_name 公司名称
 * @property string $company_pinyin 公司拼音（用于绑定赠送的二级域名）
 * @property string $company_suffix 公司域名后缀（用于绑定赠送的二级域名）
 */
class GgWebsiteInfoTable extends Table{
    protected $_name = 'gg_website_info';
    protected $_primary = 'website_id';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('company_logo'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('seo_title', 'company_short_name', 'company_name', 'company_pinyin'), 'string', array('max'=>50)),
            array(array('seo_keywords', 'seo_description', 'scope'), 'string', array('max'=>255)),
            array(array('company_intro'), 'string', array('max'=>1000)),
            array(array('company_suffix'), 'string', array('max'=>30)),
            array(array('is_guide'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'website_id'=>'Website Id',
            'seo_title'=>'Seo Title',
            'seo_keywords'=>'Seo Keywords',
            'seo_description'=>'Seo Description',
            'is_guide'=>'是否进行过引导',
            'scope'=>'业务范围',
            'company_short_name'=>'Company Short Name',
            'company_intro'=>'公司简介',
            'company_logo'=>'Company Logo',
            'company_name'=>'公司名称',
            'company_pinyin'=>'公司拼音（用于绑定赠送的二级域名）',
            'company_suffix'=>'公司域名后缀（用于绑定赠送的二级域名）',
        );
    }

    public function filters(){
        return array(
            'website_id'=>'intval',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
            'is_guide'=>'intval',
            'scope'=>'trim',
            'company_short_name'=>'trim',
            'company_intro'=>'trim',
            'company_logo'=>'intval',
            'company_name'=>'trim',
            'company_pinyin'=>'trim',
            'company_suffix'=>'trim',
        );
    }
}