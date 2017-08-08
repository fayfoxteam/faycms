<?php
namespace valentine\modules\frontend\controllers;

use fay\core\Request;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\NumberHelper;
use cms\models\tables\OptionsTable;
use cms\services\file\FileService;
use fayoauth\services\OAuthException;
use fayoauth\services\OauthService;
use cms\services\OptionService;
use cms\services\wechat\core\AccessToken;
use cms\services\wechat\jssdk\JsSDK;
use valentine\library\FrontController;
use valentine\models\tables\ValentineUserTeamsTable;
use valentine\models\tables\ValentineVotesTable;

class TeamController extends FrontController{
    public function index(){
        $this->layout->body_class = 'index';
        
        $app_config = OptionService::getGroup('oauth:weixin');
        
        $js_sdk = new JsSDK($app_config['app_id'], $app_config['app_secret']);
        
        $this->view->assign(array(
            'js_sdk_config'=>$js_sdk->getConfig(array('chooseImage', 'uploadImage', 'downloadImage')),
        ))->render();
    }
    
    /**
     * 创建组合
     * @parameter string $name
     * @parameter string $photo
     * @parameter string $blessing
     * @parameter int $type
     */
    public function create(){
        $end_time = OptionService::get('end_time');
        if($end_time && $end_time < $this->current_time){
            Response::notify('error', '活动已截止');
        }
        
        //表单验证
        $this->form()->setModel(ValentineUserTeamsTable::model())->check();
        
        $data = $this->form()->getAllData();
        $data['create_time'] = $this->current_time;
        $data['votes'] = 0;
        $data['photo'] = 0;
        
        ValentineUserTeamsTable::model()->insert($data);
        
        Response::notify('success', '组合创建成功', array(
            'team/list', array('type'=>$data['type']), false
        ));
    }
    
    public function listAction(){
        //先获取一下OpenId，后面投票的时候有用
        $this->getOpenId();
        
        //表单验证
        $this->form('search')->setRules(array(
            array(array('type'), 'required'),
            array(array('last_id'), 'int'),
            array(array('page', 'page_size'), 'int', array('min'=>1)),
        ))->setFilters(array(
            'type'=>'intval',
            'last_id'=>'intval',
            'page'=>'intval',
            'page_size'=>'intval',
            'keywords'=>'trim',
        ))->setLabels(array(
            'last_id'=>'ID号',
            'type'=>'奖项类型',
            'page'=>'页码',
            'page_size'=>'分页大小',
            'keywords'=>'关键词',
        ))->check();
        
        $last_id = $this->form('search')->getData('last_id', 0);
        $page_size = $this->form('search')->getData('page_size', 10);
        $type = $this->form('search')->getData('type', '1');
        $keywords = $this->form('search')->getData('keywords');
        
        if($this->input->get('code')){
            //若是微信登录跳转后的页面，再跳一次，分享出去的链接有问题
            $this->response->redirect('team/list', array(
                'type'=>$type,
            ));
        }
        
        $sql = new Sql();
        $sql->from(array('ut'=>'valentine_user_teams'), '*')
            ->where('type = ?', $type)
            ->order('id')
            ->limit($page_size)
        ;
        if($last_id){
            $sql->where('id > ?', $last_id);
        }
        if($keywords){
            if(NumberHelper::isInt($keywords)){
                $sql->orWhere(array(
                    'id = ?'=>$keywords,
                    'name LIKE ?'=>"%{$keywords}%",
                ));
            }else{
                $sql->where('name LIKE ?', "%{$keywords}%");
            }
        }
        $this->view->teams = $sql->fetchAll();
        
        $this->view->type = $type;
        
        //我投票的组合
        $this->view->vote = 0;
        if($open_id = \F::session()->get('open_id')){
            //若已获取Open Id，搜索我投票的组合，若未获取Open Id，就算了
            $vote = ValentineVotesTable::model()->fetchRow(array(
                'open_id = ?'=>$open_id,
                'type = ?'=>$type,
            ));
            
            if($vote){
                $this->view->vote = $vote['team_id'];
            }
        }
        
        //组合数
        $team_count = ValentineUserTeamsTable::model()->fetchRow(array(
            'type = ?'=>$type
        ), 'COUNT(*)');
        $this->view->team_count = $team_count['COUNT(*)'];
        
        //累计投票
        $vote_count = ValentineUserTeamsTable::model()->fetchRow(array(
            'type = ?'=>$type
        ), 'SUM(votes)');
        $this->view->vote_count = $vote_count['SUM(votes)'] ?: 0;
        
        //获取Access Token
        $key = 'oauth:weixin';
        $config = OptionService::getGroup($key);
        if(!$config){
            throw new OAuthException("{{$key}} Oauth参数未设置");
        }
        
        if(empty($config['enabled'])){
            throw new OAuthException("{{$key}} Oauth登录已禁用");
        }
        $access_token = new AccessToken($config['app_id'], $config['app_secret']);
        $this->view->access_token = $access_token->getToken();
        
        //访问量+1
        OptionsTable::model()->incr(array(
            "option_name = 'visits'"
        ), 'option_value', 1);
        
        //活动结束时间
        $this->view->end_time = OptionService::get('end_time');
        
        if(Request::isAjax()){
            $this->view->renderPartial('ajax_list', $this->view->getViewData());
        }else{
            return $this->view->render();
        }
    }
    
    /**
     * 投票
     * @parameter int $id
     */
    public function vote(){
        $end_time = OptionService::get('end_time');
        if($end_time && $end_time < $this->current_time){
            Response::notify('error', '活动已截止');
        }
        
        //表单验证
        $this->form()->setRules(array(
            array(array('id'), 'required'),
            array(array('id'), 'int', array('min'=>1)),
            array(array('id'), 'exist', array(
                'table'=>'valentine_user_teams',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'组合ID',
        ))->check();
        
        $open_id = $this->getOpenId();
        $team_id = $this->form()->getData('id');
        $team = ValentineUserTeamsTable::model()->find($team_id);
        
        if(ValentineVotesTable::model()->fetchRow(array(
            'open_id = ?'=>$open_id,
            'type = ' . $team['type']
        ))){
            Response::notify('error', '您已投过该奖项，单用户只能投一次', array(
                'team/list', array('type'=>$team['type']), false
            ));
        }
        
        //插入投票记录
        ValentineVotesTable::model()->insert(array(
            'team_id'=>$team_id,
            'open_id'=>$open_id,
            'type'=>$team['type'],
            'create_time'=>$this->current_time,
        ));
        //组合得票数+1
        ValentineUserTeamsTable::model()->incr($team_id, 'votes', 1);
        
        Response::notify('success', '投票成功', array(
            'team/list', array('type'=>$team['type']), false
        ));
    }
    
    /**
     * 投票结果
     */
    public function voteResult(){
        //表单验证
        $this->form('search')->setRules(array(
            array(array('type'), 'required'),
        ))->setFilters(array(
            'type'=>'intval',
        ))->setLabels(array(
            'type'=>'奖项类型',
        ))->check();
        
        $type = $this->form()->getData('type');
        $this->view->type = $type;
        
        //组合数
        $team_count = ValentineUserTeamsTable::model()->fetchRow(array(
            'type = ?'=>$type
        ), 'COUNT(*)');
        $this->view->team_count = $team_count['COUNT(*)'];
        
        //累计投票
        $vote_count = ValentineUserTeamsTable::model()->fetchRow(array(
            'type = ?'=>$type
        ), 'SUM(votes)');
        $this->view->vote_count = $vote_count['SUM(votes)'] ?: 0;
        
        $this->view->teams = ValentineUserTeamsTable::model()->fetchAll(array(
            'type = ?'=>$type,
        ), '*', 'votes DESC,id ASC');
        
        //活动结束时间
        $this->view->end_time = OptionService::get('end_time');
        
        //我投票的组合
        $this->view->vote = 0;
        if($open_id = \F::session()->get('open_id')){
            //若已获取Open Id，搜索我投票的组合，若未获取Open Id，就算了
            $vote = ValentineVotesTable::model()->fetchRow(array(
                'open_id = ?'=>$open_id,
                'type = ?'=>$type,
            ));
            
            if($vote){
                $this->view->vote = $vote['team_id'];
            }
        }
        
        return $this->view->render();
    }
    
    /**
     * 获取OpenId，若session中不存在，则跳转到微信获取
     */
    private function getOpenId(){
        if(!$open_id = \F::session()->get('open_id')){
            //去微信授权
            $key = 'oauth:weixin';
            $config = OptionService::getGroup($key);
            if(!$config){
                throw new OAuthException("{{$key}} Oauth参数未设置");
            }
            
            if(empty($config['enabled'])){
                throw new OAuthException("{{$key}} Oauth登录已禁用");
            }
            $oauth = OauthService::getInstance(
                'weixin',
                $config['app_id'],
                $config['app_secret']
            );
            
            $open_id = $oauth->getOpenId();
            \F::session()->set('open_id', $open_id);
        }
        
        return $open_id;
    }
    
    public function download(){
        $app_config = OptionService::getGroup('oauth:weixin');
        
        $signature = JsSDK::signature(Request::getCurrentUrl(), $app_config['app_id'], $app_config['app_secret']);
        
        $this->view->assign(array(
            'signature'=>$signature,
        ))->render();
    }
    
    /**
     * 从微信服务器下载到本地
     */
    public function downloadToLocal(){
        $team = ValentineUserTeamsTable::model()->fetchRow('photo = 0');
        
        //获取Access Token
        $key = 'oauth:weixin';
        $config = OptionService::getGroup($key);
        if(!$config){
            throw new OAuthException("{{$key}} Oauth参数未设置");
        }
        
        if(empty($config['enabled'])){
            throw new OAuthException("{{$key}} Oauth登录已禁用");
        }
        $access_token = new AccessToken($config['app_id'], $config['app_secret']);
        
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token->getToken()}&media_id={$team['photo_server_id']}";
        $file = FileService::service()->uploadFromUrl($url);
        if($file['status']){
            ValentineUserTeamsTable::model()->update(array(
                'photo'=>$file['data']['id']
            ), $team['id']);
            
            echo $team['id'];
        }else{
            dump($team);
        }
    }
}