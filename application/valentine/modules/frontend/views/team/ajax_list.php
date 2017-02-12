<?php
/**
 * @var $teams array
 * @var $data array
 * @var $end_time int
 * @var $access_token string
 * @var $vote int 我的投票
 */
foreach($teams as $data){
	$this->renderPartial('_list_item', array(
		'data'=>$data,
		'end_time'=>$end_time,
		'access_token'=>$access_token,
		'vote'=>$vote,
	));
}