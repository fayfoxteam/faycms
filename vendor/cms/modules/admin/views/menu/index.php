<?php
use fay\helpers\Html;

function showCats($tree, $dep = 0){?>
	<ul class="cat-list">
	<?php foreach($tree as $k=>$node){?>
		<li class="cat-item <?php if(!$k)echo 'first';?>">
			<div class="cat-item-container">
				<span class="fr options">
					<span class="w100 block fl">
					排序：<?php echo Html::inputText('sort[]', $node['sort'], array(
						'size'=>3,
						'maxlength'=>3,
						'data-id'=>$node['id'],
						'class'=>"edit-sort w30 node-sort-{$node['id']}",
					))?>
					</span>
					<?php 
					if(F::app()->checkPermission('admin/menu/create')){
						echo Html::link('添加子节点', '#create-cat-dialog', array(
							'class'=>'create-cat-link',
							'data-title'=>Html::encode($node['title']),
							'data-id'=>$node['id'],
						));
					}
					if(F::app()->checkPermission('admin/menu/edit')){
						echo Html::link('编辑', '#edit-cat-dialog', array(
							'class'=>'edit-cat-link',
							'data-id'=>$node['id'],
						));
					}
					if(F::app()->checkPermission('admin/menu/remove')){
						echo Html::link('删除', array('admin/menu/remove', array(
							'id'=>$node['id'],
						)), array(
							'class'=>'remove-link color-red',
							'title'=>'删除该节点，其子节点将被挂载到其父节点',
						));
						echo Html::link('删除全部', array('admin/menu/remove-all', array(
							'id'=>$node['id'],
						)), array(
							'class'=>'remove-link color-red',
							'title'=>'删除该节点及其所有子节点',
						));
					}
					?>
				</span>
				<span class="cat-item-title node-<?php echo $node['id']?> <?php if(empty($node['children']))
						echo 'terminal';
					else
						echo 'parent';?>">
					<?php if(empty($node['children'])){?>
						<?php echo Html::encode($node['title'])?>
					<?php }else{?>
						<strong><?php echo Html::encode($node['title'])?></strong>
					<?php }?>
					<?php if($node['alias']){?>
						<em class="color-grey">[ <?php echo $node['alias']?> ]</em>
					<?php }?>
					<em class="color-grey"><?php echo $node['link']?></em>
				</span>
			</div>
			<?php if(!empty($node['children'])){
				showCats($node['children'], $dep + 1);
			}?>
		</li>
	<?php }?>
	</ul>
<?php }?>
<div class="col-1">
	<div class="cat-list-container">
		<?php showCats($menus)?>
	</div>
	<div class="clear"></div>
</div>

<?php include '_common.php';?>