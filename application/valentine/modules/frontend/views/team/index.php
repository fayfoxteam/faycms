<?php
/**
 * @var $listview \fay\common\ListView
 * @var $type int
 * @var $team_count int
 * @var $vote_count int
 * @var $end_time int
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
			<span><?php echo \fay\services\OptionService::get('visits')?></span>
		</li>
	</ul>
</div>
<div class="blockcell">
	<i class="fa fa-clock-o"></i>
	投票截止时间<?php echo \fay\helpers\DateHelper::format($end_time)?>
</div>
<div class="blockcell">
	<i class="fa fa-warning"></i>
	投票规则：每个微信对每个奖项限投1票
</div>
<div class="blockcell">
	<i class="fa fa-arrows"></i>
	奖项设置：<a href="<?php echo $this->url('team', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_COUPLE))?>">最具夫妻相</a>，
		<a href="<?php echo $this->url('team', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_ORIGINALITY))?>">最佳创意奖</a>，
		<a href="<?php echo $this->url('team', array('type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_BLESSING))?>">最赞祝福语</a>
</div>
<div class="blockcell">
	<?php
		echo F::form('search')->open(null, 'get');
		echo F::form('search')->inputText('keywords', array(
			'placeholder'=>'请输入组合名称或编号',
			'class'=>'w160',
		));
		echo F::form('search')->submitLink('搜索', array(
			'class'=>'btn btn-blue',
			'prepend'=>'<i class="fa fa-search"></i>'
		));
		echo F::form('search')->close();
	?>
</div>
<div class="blockcell">
	<a href="" class="btn btn-blue wp100 show-ranking-link"><i class="fa fa-bar-chart"></i>查看排名</a>
</div>
<div class="vote-list">
	<?php $listview->showData(array(
		'end_time'=>$end_time
	))?>
</div>
<script>
$(function(){
	$('#search-form-submit').on('click', function(){
		$('#search-form').submit();
	})
});
</script>