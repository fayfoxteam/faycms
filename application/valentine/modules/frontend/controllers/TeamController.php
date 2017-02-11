<?php
namespace valentine\modules\frontend\controllers;

use fay\common\ListView;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\NumberHelper;
use fay\models\tables\OptionsTable;
use fay\services\oauth\OAuthException;
use fay\services\oauth\OauthService;
use fay\services\OptionService;
use fay\services\wechat\core\AccessToken;
use valentine\library\FrontController;
use valentine\models\tables\ValentineUserTeamsTable;
use valentine\models\tables\ValentineVotesTable;

class TeamController extends FrontController{
	/**
	 * 创建组合
	 * @parameter string $name
	 * @parameter string $photo
	 * @parameter string $blessing
	 * @parameter int $type
	 */
	public function create(){
		//表单验证
		$this->form()->setModel(ValentineUserTeamsTable::model())->check();
		
		$data = $this->form()->getAllData();
		$data['create_time'] = $this->current_time;
		$data['votes'] = 0;
		$data['photo'] = 0;
		
		ValentineUserTeamsTable::model()->insert($data);
		
		Response::notify('success', '组合创建成功', array(
			'team', array('type'=>$data['type']),
		));
	}
	
	public function index(){
		//表单验证
		$this->form('search')->setRules(array(
			array(array('type'), 'required'),
			array(array('page', 'page_size'), 'int', array('min'=>1)),
		))->setFilters(array(
			'page'=>'intval',
			'page_size'=>'intval',
			'keywords'=>'trim',
		))->setLabels(array(
			'page'=>'页码',
			'page_size'=>'分页大小',
			'keywords'=>'关键词',
		))->check();
		
		$page = $this->form('search')->getData('page', 1);
		$page_size = $this->form('search')->getData('page_size', 20);
		$type = $this->form('search')->getData('type', '1');
		$keywords = $this->form('search')->getData('keywords');
		
		$sql = new Sql();
		$sql->from(array('ut'=>'valentine_user_teams'), '*')
			->where('type = ?', $type)
			->order('id');
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
		
		$this->view->listview = new ListView($sql, array(
			'current_page'=>$page,
			'page_size'=>$page_size,
		));
		
		$this->view->type = $type;
		
		//我投票的组合
		$this->view->vote = 0;
		if($open_id = \F::session()->get('open_id')){
			//若已获取Open Id，搜索我投票的组合，若未获取Open Id，就算了
			$vote = ValentineVotesTable::model()->fetchRow(array(
				'open_id = ?'=>$open_id,
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
		$sql = new Sql();
		$vote_count = $sql->from(array('v'=>'valentine_votes'), 'COUNT(*)')
			->joinLeft(array('t'=>'valentine_user_teams'), 'v.team_id = t.id')
			->where('t.type = ?', $type)
			->fetchRow();
		$this->view->vote_count = $vote_count['COUNT(*)'];
		
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
		$this->view->end_time = \fay\services\OptionService::get('end_time');
		
		$this->view->render();
	}
	
	/**
	 * 投票
	 * @parameter int $id
	 */
	public function vote(){
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
		$team_id = $this->form()->getData('id');
		$team = ValentineUserTeamsTable::model()->find($team_id);
		
		//插入投票记录
		ValentineVotesTable::model()->insert(array(
			'team_id'=>$team_id,
			'open_id'=>$open_id,
			'create_time'=>$this->current_time,
		));
		
		Response::notify('success', '投票成功', array(
			'team', array('type'=>$team['type'])
		));
	}
}