<?php
/**
 * @var $this \fay\core\View
 * @var $group array
 * @var $users array
 */
$this->appendCss($this->appAssets('css/group.css'));
$this->appendCss($this->assets('css/font-awesome.min.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="group-51">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/group/brand.png')?>"></div>
			<div class="layer" id="step">
				<span class="number">第五式</span>
				<span class="title">解密</span>
			</div>
			<div class="layer guangong"><img src="<?php echo $this->appAssets('images/group/guangong.png')?>"></div>
		</div>
		<div class="swiper-slide" id="group-52">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/group/brand.png')?>"></div>
			<div class="layer subtitle">
				<span class="title">解密</span>
				<span>第五式</span>
			</div>
			<div class="layer left-bottom"><img src="<?php echo $this->appAssets('images/group/lb.png')?>"></div>
			<div class="layer group-name"><h1><?php echo $group['name']?></h1></div>
			<div class="layer user-list users<?php echo count($users)?>">
			<?php foreach($users as $user){?>
				<fieldset>
					<div class="avatar"><?php
						if($user['user']['avatar']){
							echo \fay\helpers\HtmlHelper::img($user['user']['avatar']['id'], \fay\services\FileService::PIC_RESIZE, array(
								'dw'=>113,
								'dh'=>151,
							));
						}else{
							echo \fay\helpers\HtmlHelper::img($this->appAssets('images/group/avatar.png'));
						}
					?></div>
					<div class="info">
						<span class="nickname"><?php
							if($user['user']['id']){
								echo \fay\helpers\HtmlHelper::encode($user['user']['nickname']);
							}else{
								echo '未接受邀请';
							}
						?></span>
						<a href="#words-dialog" data-user-id="<?php echo $user['user']['id']?>" data-group-id="<?php echo $group['id']?>" class="show-words-link">查看</a>
					</div>
				</fieldset>
			<?php }?>
			</div>
		</div>
	</div>
</div>
<div class="hide">
	<div id="words-dialog" class="dialog">
		<div class="dialog-content"></div>
	</div>
</div>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/jquery.fancybox-1.3.4.css')?>">
<script type="text/javascript" src="<?php echo $this->assets('js/jquery.fancybox-1.3.4.pack.js')?>"></script>
<script>
$(function(){
	var step5 = {
		/**
		 * 查看对兄弟说
		 */
		'showWords': function(){
			$('.dialog .dialog-content').css({'width': document.documentElement.clientWidth * 0.7});
			$('.show-words-link').fancybox({
				'transitionIn': 'elastic',
				'transitionOut': 'elastic',
				'type': 'inline',
				'centerOnScroll': true,
				'padding': 0,
				'showCloseButton': false,
				'onStart': function(o){
					if($(o).attr('data-user-id') == '0'){
						common.toast('该用户未接受邀请，无法查看密语', 'error');
						return false;
					}
				},
				'onComplete': function(o){
					$.ajax({
						'type': 'GET',
						'url': system.url('api/word/get'),
						'data': {
							'group_id': $(o).attr('data-group-id'),
							'user_id': $(o).attr('data-user-id')
						},
						'dataType': 'json',
						'cache': false,
						'success': function(resp){
							if(resp.status){
								$('#words-dialog .dialog-content').text(resp.data);
							}else{
								common.toast(resp.message, 'error');
								$.fancybox.close();
							}
						}
					});
				}
			});
		},
		'init': function(){
			this.showWords();
		}
	};
	step5.init();
});
</script>