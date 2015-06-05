<?php
use fay\helpers\Html;

function showCats($tree, $dep = 0){?>
	<ul class="tree">
	<?php foreach($tree as $k=>$node){?>
		<li class="leaf-container <?php if(!$k)echo 'first';?>">
			<div class="leaf">
				<span class="leaf-title node-<?php echo $node['id']?> <?php if(empty($node['children']))
						echo 'terminal';
					else
						echo 'parent';?>">
					<?php
						echo F::form()->inputCheckbox('role_cats[]', $node['id']);
						
						if(empty($node['children'])){
							echo Html::encode($node['title']);
						}else{
							echo Html::tag('strong', array(), $node['title']);
						}
					?>
					<?php if($node['alias']){?>
						<em class="fc-grey">[ <?php echo $node['alias']?> ]</em>
					<?php }?>
					<?php if(!empty($node['children'])){
						echo Html::link('全选子项', 'javascript:;', array(
							'class'=>'select-all-children',
						));
					}?>
				</span>
			</div>
			<?php if(!empty($node['children'])){
				showCats($node['children'], $dep + 1);
			}?>
		</li>
	<?php }?>
	</ul>
<?php }?>
<div class="row">
	<div class="col-12">
		<div class="form-inline tree-container">
			<?php showCats($cats)?>
		</div>
	</div>
</div>