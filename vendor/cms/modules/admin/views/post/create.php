<?php
use fay\models\Option;
use fay\models\tables\Users;
use fay\models\tables\Posts;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
<?php echo F::form()->inputHidden('cat_id')?>
<div class="poststuff">
	<div class="post-body">
		<div class="post-body-content">
			<div class="post-title-env"><?php echo F::form()->inputText('title', array(
				'id'=>'title',
				'class'=>'form-control bigtxt',
				'placeholder'=>'在此键入标题',
			))?></div>
			<div class="postarea cf"><?php $this->renderPartial('_content')?></div>
		</div>
		<div class="postbox-container-1 dragsort" id="side">
			<div class="box" id="box-operation">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h3>操作</h3>
				</div>
				<div class="box-content">
					<div>
						<?php echo F::form()->submitLink('提交', array(
							'class'=>'btn',
						))?>
					</div>
					<div class="misc-pub-section">
						<strong>状态：</strong>
						<?php
							$options = array(Posts::STATUS_DRAFT=>'草稿');
							$default = '';
							if(F::app()->post_review){
								//开启审核，显示待审核选项。若没有审核权限，默认为待审核
								$options[Posts::STATUS_PENDING] = '待审核';
								if(F::app()->checkPermission('admin/post/review')){
									$options[Posts::STATUS_REVIEWED] = '通过审核';
								}
								$default = Posts::STATUS_PENDING;
							}
							if(!F::app()->post_review || F::app()->checkPermission('admin/post/publish')){
								//未开启审核，或者有审核权限，显示发布按钮，并默认为“立即发布”
								$options[Posts::STATUS_PUBLISHED] = '已发布';
								$default = Posts::STATUS_PUBLISHED;
							}
							echo F::form()->select('status', $options, array(
								'class'=>'form-control mw100 mt5 ib',
							), $default);
						?>
					</div>
					<div class="misc-pub-section mt0">
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
		<div class="postbox-container-2 dragsort" id="normal"><?php 
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
		?></div>
	</div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->assets('js/plupload.full.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/post.js')?>"></script>
<script>
$(function(){
	common.dragsortKey = 'admin_post_box_sort';
	common.filebrowserImageUploadUrl = system.url('admin/file/img-upload', {'t':'posts'});
	common.filebrowserFlashUploadUrl = system.url("admin/file/upload", {'t':'posts'});
	post.boxes = <?php echo json_encode($enabled_boxes)?>;
	<?php if(F::session()->get('role') != Users::ROLE_SUPERADMIN && Option::get('system:role_cats')){?>
		post.roleCats = <?php echo json_encode(F::session()->get('role_cats'))?>;
	<?php }?>
	post.init();
});
</script>