<?php
use fay\models\tables\Posts;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
	<?php echo F::form()->inputHidden('cat_id')?>
	<div class="col-2-2">
		<div class="col-2-2-body-sidebar dragsort" id="side">
			<div class="box" id="box-operation">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div>
						<?php echo F::form()->submitLink('提交', array(
							'class'=>'btn',
						))?>
					</div>
					<div class="misc-pub-section">
						<strong>状态</strong>
						<?php
							if(!F::app()->post_review || F::app()->checkPermission('admin/post/review')){
								//未开启审核，或者有审核权限，显示发布按钮，并默认为“立即发布”
								echo F::form()->inputRadio('status', Posts::STATUS_PUBLISH, array('label'=>'发布'), true);
							}
							if(F::app()->post_review){
								//开启审核，显示待审核选项。若没有审核权限，默认为待审核
								echo F::form()->inputRadio('status', Posts::STATUS_PENDING, array('label'=>'待审核'), F::app()->checkPermission('admin/post/review') ? false : true);
							}
							echo F::form()->inputRadio('status', Posts::STATUS_DRAFT, array('label'=>'草稿'));
						?>
					</div>
					<div class="misc-pub-section mt0">
						<strong>是否置顶？</strong>
						<?php echo F::form()->inputRadio('is_top', 1, array('label'=>'是'))?>
						<?php echo F::form()->inputRadio('is_top', 0, array('label'=>'否'), true)?>
					</div>
				</div>
			</div>
			<?php if(isset($_box_sort_settings['side'])){
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
			}?>
		</div>
		<div class="col-2-2-body">
			<div class="col-2-2-body-content">
				<div class="titlediv">
					<label class="title-prompt-text" for="title">在此键入标题</label>
					<?php echo F::form()->inputText('title', array(
						'id'=>'title',
						'class'=>'bigtxt',
					))?>
				</div>
				<div class="postarea cf"><?php $this->renderPartial('_content')?></div>
				<div class="mt20 dragsort" id="normal">
				<?php 
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
					foreach($boxes_cp as $k=>$box){
						if(isset(F::app()->boxes[$k]['view'])){
							$this->renderPartial(F::app()->boxes[$k]['view']);
						}else{
							$this->renderPartial('_box_'.str_replace('-', '_', $box));
						}
					}
				?>
				</div>
			</div>
			<div class="clear"></div>
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
	post.init();
});
</script>