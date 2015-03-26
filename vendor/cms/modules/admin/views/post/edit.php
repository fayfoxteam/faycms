<?php
use fay\models\tables\Posts;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
<div class="poststuff">
	<div class="post-body">
		<div class="post-body-content">
			<div class="titlediv"><?php echo F::form()->inputText('title', array(
				'id'=>'title',
				'class'=>'form-control bigtxt',
				'placeholder'=>'在此键入标题',
			));?></div>
			<div class="postarea"><?php $this->renderPartial('_content', array(
				'post'=>$post,
			))?></div>
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
					<div class="misc-pub-section">
						<strong>状态</strong>
						<?php
							if(!F::app()->post_review || F::app()->checkPermission('admin/post/review') || $post['status'] == Posts::STATUS_PUBLISH){
								//未开启审核，或者有审核权限，或者文章处于已发布状态，显示发布按钮
								echo F::form()->inputRadio('status', Posts::STATUS_PUBLISH, array('label'=>'发布'));
							}
							if(F::app()->post_review){
								//开启审核，显示待审核选项
								echo F::form()->inputRadio('status', Posts::STATUS_PENDING, array('label'=>'待审核'));
							}
							echo F::form()->inputRadio('status', Posts::STATUS_DRAFT, array('label'=>'草稿'));
						?>
					</div>
					<div class="misc-pub-section mt0">
						<strong>是否置顶？</strong>
						<?php echo F::form()->inputRadio('is_top', 1, array('label'=>'是'))?>
						<?php echo F::form()->inputRadio('is_top', 0, array('label'=>'否'))?>
					</div>
				</div>
			</div>
			<?php
				if(isset($_box_sort_settings['side'])){
					foreach($_box_sort_settings['side'] as $box){
						$k = array_search($box, $boxes_cp);
						if($k !== false){
							if(isset(F::app()->boxes[$k]['view'])){
								$this->renderPartial(F::app()->boxes[$k]['view']);
							}else{
								$this->renderPartial('_box_'.str_replace('-', '_', $box));
							}
							unset($boxes_cp[$k]);
						}
					}
				}
			?>
		</div>
		<div class="postbox-container-2 dragsort"><?php
			if(isset($_box_sort_settings['normal'])){
				foreach($_box_sort_settings['normal'] as $box){
					$k = array_search($box, $boxes_cp);
					if($k !== false){
						if(isset(F::app()->boxes[$k]['view'])){
							$this->renderPartial(F::app()->boxes[$k]['view']);
						}else{
							$this->renderPartial('_box_'.str_replace('-', '_', $box));
						}
						unset($boxes_cp[$k]);
					}
				}
			}

			//最后多出来的都放最后面
			foreach($boxes_cp as $box){
				if(isset(F::app()->boxes[$k]['view'])){
					$this->renderPartial(F::app()->boxes[$k]['view']);
				}else{
					$this->renderPartial('_box_'.str_replace('-', '_', $box));
				}
			}
		?></div>
	</div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->url()?>js/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/post.js"></script>
<script>
$(function(){
	common.dragsortKey = 'admin_post_box_sort';
	common.filebrowserImageUploadUrl = system.url("admin/file/upload", {'t':'posts'});
	common.filebrowserFlashUploadUrl = system.url("admin/file/upload", {'t':'posts'});
	post.boxes = <?php echo json_encode($enabled_boxes)?>;
	post.post_id = <?php echo $post['id']?>;
	post.init();
});
</script>