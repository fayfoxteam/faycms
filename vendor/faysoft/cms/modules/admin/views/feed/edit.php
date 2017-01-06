<?php
use cms\helpers\FeedHelper;
use fay\helpers\Html;
use fay\models\tables\Feeds;
use fay\services\UserService;
use fay\services\FileService;
use fay\helpers\Date;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
<div class="poststuff">
	<div class="post-body">
		<div class="post-body-content">
			<div class="mb30 cf user-info"><?php
				$user = UserService::service()->get($feed['user_id'], 'nickname,id,avatar,admin,roles.title');
				
				$user_detail_link = $user['user']['admin'] ? array('admin/operator/item', array(
					'id'=>$user['user']['id'],
				)) : array('admin/user/item', array(
					'id'=>$user['user']['id'],
				));
				//头像
				echo Html::link(Html::img($user['user']['avatar']['thumbnail'], FileService::PIC_THUMBNAIL, array(
					'spare'=>'avatar',
				)), $user_detail_link, array(
					'encode'=>false,
					'title'=>Html::encode($user['user']['nickname']),
					'class'=>'user-avatar',
				));
			?>
				<div class="user-details">
					<p><?php
						//昵称
						echo Html::link($user['user']['nickname'], $user_detail_link, array(
							'class'=>'user-nickname',
						));
						
						//角色
						foreach($user['roles'] as $r){
							echo Html::tag('sup', array(
								'class'=>'bg-yellow title-sup ml5',
							), Html::encode($r['title']));
						}
					?></p>
					<p class="feed-meta">
						<span>创建于：<?php
							echo Html::tag('time', array(), Date::niceShort($feed['create_time']));
						?></span>
						<span class="pl11">最近更新于：<?php
							echo Html::tag('time', array(), Date::niceShort($feed['last_modified_time']));
						?></span>
					</p>
					<p class="feed-interaction">
						<span>
							<i class="fa fa-heart-o"></i>
							点赞
							<span class="fc-grey">(<?php echo $feed['likes']?>)</span>
						</span>
						<span class="pl11">
							<i class="fa fa-comment-o"></i>
							评论
							<span class="fc-grey">(<?php echo $feed['comments']?>)</span>
						</span>
						<span class="pl11">
							<i class="fa fa-star-o"></i>
							收藏
							<span class="fc-grey">(<?php echo $feed['favorites']?>)</span>
						</span>
					</p>
				</div>
			</div>
			<div class="mb30"><?php echo F::form()->textarea('content', array(
				'class'=>'h200 form-control autosize',
			));?></div>
		</div>
		<div class="postbox-container-1 dragsort" id="side">
			<div class="box" id="box-operation">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h3>操作</h3>
				</div>
				<div class="box-content">
					<div>
						<?php echo F::form()->submitLink('更新', array(
							'class'=>'btn',
						))?>
					</div>
					<div class="misc-pub-section mt6">
						<strong>当前状态：</strong>
						<span id="crt-status"><?php echo FeedHelper::getStatus(F::form()->getData('status'), 0, false)?></span>
						<a href="javascript:;" id="edit-status-link" class="ml5">编辑</a>
						<?php echo F::form()->inputHidden('status')?>
						<div class="hide" id="edit-status-container"><?php
							echo Html::select('', array(
								Feeds::STATUS_DRAFT => '草稿',
								Feeds::STATUS_PENDING => '待审核',
								Feeds::STATUS_APPROVED => '通过审核',
								Feeds::STATUS_UNAPPROVED => '未通过审核',
							), F::form()->getData('status'), array(
								'class'=>'form-control mw110 mt5 ib',
								'id'=>'edit-status-selector'
							));
							echo Html::link('确定', 'javascript:;', array(
								'class'=>'btn btn-grey btn-sm ml5',
								'id'=>'set-status-editing',
							));
							echo Html::link('取消', 'javascript:;', array(
								'class'=>'ml5',
								'id'=>'cancel-status-editing',
							));
						?><p class="fc-grey mt5">点击“确定”并提交修改后生效</p></div>
					</div>
					<div class="misc-pub-section">
						<strong>是否置顶？</strong>
						<?php echo F::form()->inputRadio('is_top', 1, array('label'=>'是'))?>
						<?php echo F::form()->inputRadio('is_top', 0, array('label'=>'否'), true)?>
					</div>
				</div>
			</div>
			<?php
				if(isset($_box_sort_settings['side'])){
					foreach($_box_sort_settings['side'] as $box){
						$k = array_search($box, $boxes_cp);
						if($k !== false){
							if(isset(F::app()->boxes[$k]['view'])){
								$this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
							}else{
								$this->renderPartial('_box_'.$box, $this->getViewData());
							}
							unset($boxes_cp[$k]);
						}
					}
				}
			?>
		</div>
		<div class="postbox-container-2 dragsort" id="normal"><?php
			if(isset($_box_sort_settings['normal'])){
				foreach($_box_sort_settings['normal'] as $box){
					$k = array_search($box, $boxes_cp);
					if($k !== false){
						if(isset(F::app()->boxes[$k]['view'])){
							$this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
						}else{
							$this->renderPartial('_box_'.$box, $this->getViewData());
						}
						unset($boxes_cp[$k]);
					}
				}
			}

			//最后多出来的都放最后面
			foreach($boxes_cp as $box){
				if(isset(F::app()->boxes[$k]['view'])){
					$this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
				}else{
					$this->renderPartial('_box_'.$box, $this->getViewData());
				}
			}
		?></div>
	</div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/feed.js')?>"></script>
<script>
$(function(){
	common.dragsortKey = 'admin_post_box_sort';
	common.filebrowserImageUploadUrl = system.url('admin/file/img-upload', {'cat':'post'});
	common.filebrowserFlashUploadUrl = system.url('admin/file/upload', {'cat':'post'});
	feed.boxes = <?php echo json_encode($enabled_boxes)?>;
	feed.feed_id = <?php echo \F::form()->getData('id')?>;
	feed.init();
});
</script>