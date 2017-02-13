<?php
/**
 * @var $teams array
 * @var $type int
 * @var $team_count int
 * @var $vote_count int
 * @var $end_time int
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
	<i class="fa fa-home"></i>
	主办：浙江平安产险工会、互联网创新部
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
	奖项设置：<a href="<?php echo $this->url('team/vote-result', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_COUPLE), false)?>">最牛组合名</a>，
		<a href="<?php echo $this->url('team/vote-result', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_ORIGINALITY), false)?>">最佳创意照</a>，
		<a href="<?php echo $this->url('team/vote-result', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_BLESSING), false)?>">最美祝福语</a>
</div>
<div class="blockcell">
	<a href="<?php echo $this->url('team/list', array(
		'type'=>$type
	), false)?>" class="btn btn-blue wp100 show-ranking-link"><i class="fa fa-list"></i>去投票</a>
</div>
<div class="vote-result">
	<div class="result-list">
	<?php if(!$teams){echo '暂无组合参加';}?>
	<?php
		$all_votes = array_sum(\fay\helpers\ArrayHelper::column($teams, 'votes'));
		$all_votes || $all_votes = 1;//这个值仅被用于计算百分比，若为0则会出现除以0的报错
	?>
	<?php foreach($teams as $t){?>
		<article>
			<span class="name"><?php
				echo $t['id'], '.', \fay\helpers\HtmlHelper::encode($t['name']);
				if($vote == $t['id']){
					echo '（已选）';
				}
			?></span>
			<span class="votes"><?php echo $t['votes']?>票</span>
			<span class="percent"><?php echo intval($t['votes'] * 100 / $all_votes)?>%</span>
			<span class="percent-bar">
				<span class="percent-bar-progress" style="width:<?php echo intval($t['votes'] * 100 / $all_votes)?>%"></span>
			</span>
		</article>
	<?php }?>
	</div>
</div>