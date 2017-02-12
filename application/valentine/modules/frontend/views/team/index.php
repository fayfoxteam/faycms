<?php
/**
 * @var $listview \fay\common\ListView
 * @var $type int
 * @var $team_count int
 * @var $vote_count int
 * @var $end_time int
 * @var $access_token string
 * @var $vote int
 */

$this->appendCss($this->assets('css/font-awesome.min.css'));
?>
<div class="vote-title">
	<h1><?php echo \fay\services\OptionService::get('site:sitename'), ' - ',
		\valentine\helpers\TeamHelper::getTypeTitle($type)?></h1>
</div>
<div class="vote-info">
	<ul>
		<li>
			<label>参与组合</label>
			<span><?php echo $team_count?></span>
		</li>
		<li class="left-border">
			<label>累计投票</label>
			<span><?php echo $vote_count?></span>
		</li>
		<li class="left-border">
			<label>访问次数</label>
			<span><?php echo \fay\services\OptionService::get('visits', 0)?></span>
		</li>
	</ul>
</div>
<div class="blockcell">
	<i class="fa fa-clock-o"></i>
	投票截止时间：<?php echo \fay\helpers\DateHelper::format($end_time)?>
</div>
<div class="blockcell">
	<i class="fa fa-warning"></i>
	投票规则：每个微信对每个奖项限投1票
</div>
<div class="blockcell">
	<i class="fa fa-sitemap"></i>
	奖项设置：<a href="<?php echo $this->url('team', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_COUPLE), false)?>">最具夫妻相</a>，
		<a href="<?php echo $this->url('team', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_ORIGINALITY), false)?>">最佳创意奖</a>，
		<a href="<?php echo $this->url('team', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_BLESSING), false)?>">最赞祝福语</a>
</div>
<div class="blockcell">
	<?php
		echo F::form('search')->open(null, 'get');
		echo \fay\helpers\HtmlHelper::inputHidden('type', $type);
		echo F::form('search')->inputText('keywords', array(
			'placeholder'=>'请输入组合名称或编号',
			'class'=>'inputxt w160',
		));
		echo F::form('search')->submitLink('搜索', array(
			'class'=>'btn btn-blue',
			'prepend'=>'<i class="fa fa-search"></i>'
		));
		echo F::form('search')->close();
	?>
</div>
<div class="blockcell">
	<a href="<?php echo $this->url('team/vote-result', array(
		'type'=>$type
	))?>" class="btn btn-blue wp100 show-ranking-link"><i class="fa fa-bar-chart"></i>查看排名</a>
</div>
<div class="vote-list">
	<?php $listview->showData(array(
		'end_time'=>$end_time,
		'access_token'=>$access_token,
		'vote'=>$vote,
	))?>
</div>
<script>
$(function(){
	$('#search-form-submit').on('click', function(){
		$('#search-form').submit();
	});
	
	$('.vote-link').on('click', function(){
		if($(this).hasClass('btn-grey')){
			//已经投过或者活动已过期，不能再投了
			return false;
		}
		
		$.ajax({
			'type': 'POST',
			'url': system.url('team/vote'),
			'data': {'id': $(this).attr('data-id')},
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				if(resp.status){
					common.toast('投票成功', 'success');
					$('.vote-link').addClass('btn-grey');
				}else{
					common.toast(resp.message ? resp.message : '投票失败', 'error');
				}
			}
		});
	});
});
</script>