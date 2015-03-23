<?php
use fay\helpers\Html;
use fay\models\tables\Goods;
use fay\models\tables\CatProps;

echo F::form()->open(null, 'post', array('id'=>'create-goods-form'));
?>
<input type="hidden" name="status" id="status" />
<div class="col-2-2">
	<div class="col-2-2-body-sidebar dragsort">
		<div class="box">
			<div class="box-title">
				<a class="tools toggle" title="点击以切换"></a>
				<h4>操作</h4>
			</div>
			<div class="box-content">
				<div>
					<a href="javascript:;" class="btn-1" id="create-goods-form-submit">更新</a>
					<?php echo Html::link('查看', array('goods/item', array('id'=>$goods['id'])), array(
						'target'=>'_blank',
						'class'=>'btn-2',
					))?>
				</div>
				<div class="misc-pub-section">
					<strong>状态</strong>
					<?php echo F::form()->inputRadio('status', Goods::STATUS_ONSALE, array(
						'label'=>'出售中',
					), true)?>
					<?php echo F::form()->inputRadio('status', Goods::STATUS_INSTOCK, array(
						'label'=>'放入仓库',
					))?>
				</div>
			</div>
		</div>
		<?php if(in_array('sku', $boxes)){?>
		<div class="box">
			<div class="box-title">
				<a class="tools toggle" title="点击以切换"></a>
				<h4>SKU</h4>
			</div>
			<div class="box-content">
				<div class="pl10">
					<label for="sku-price"><strong>价格</strong></label>
					<?php echo F::form()->inputText('price', array(
						'id'=>'sku-price',
						'class'=>'text-short',
					))?>
					<span class="color-grey">单位：元</span>
				</div>
				<div class="misc-pub-section">
					<label for="sku-total-num"><strong>库存</strong></label>
					<?php echo F::form()->inputText('num', array(
						'id'=>'sku-total-num',
						'class'=>'text-short',
					))?>
				</div>
				<div class="misc-pub-section mt0">
					<label><strong>货号</strong></label>
					<?php echo F::form()->inputText('sn', array(
					))?>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if(in_array('guide', $boxes)){?>
		<div class="box">
			<div class="box-title">
				<a class="tools toggle" title="点击以切换"></a>
				<h4>导购</h4>
			</div>
			<div class="box-content">
				<div class="pl10">
					<strong>排序</strong>
					<?php echo F::form()->inputText('sort', array(
						'class'=>'text-short',
					), 100)?>
					<span class="color-grey">数字越小越靠前</span>
				</div>
				<div class="misc-pub-section">
					<strong>推荐</strong>
					<?php echo F::form()->inputCheckbox('is_new', 1, array(
						'label'=>'新品',
					))?>
					<?php echo F::form()->inputCheckbox('is_hot', 1, array(
						'label'=>'热销',
					))?>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if(in_array('shipping', $boxes)){?>
		<div class="box">
			<div class="box-title">
				<a class="tools toggle" title="点击以切换"></a>
				<h4>物流参数</h4>
			</div>
			<div class="box-content">
				<div class="pl10">
					<strong>重量</strong>
					<?php echo F::form()->inputText('weight', array(
						'class'=>'text-short',
					))?>
					<span class="color-grey">单位：kg</span>
				</div>
				<div class="misc-pub-section">
					<strong>体积</strong>
					<?php echo F::form()->inputText('size', array(
						'class'=>'text-short',
					))?>
					<span class="color-grey">单位：立方米</span>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if(in_array('publish-time', $boxes)){?>
		<div class="box" id="box-publish-time">
			<div class="box-title">
				<a class="tools toggle" title="点击以切换"></a>
				<h4>发布时间</h4>
			</div>
			<div class="box-content">
				<?php echo F::form()->inputText('publish_time', array('class'=>'timepicker'))?>
				<div class="color-grey">默认为当前时间</div>
			</div>
		</div>
		<?php }?>
		<?php if(in_array('thumbnail', $boxes)){?>
		<div class="box">
			<div class="box-title">
				<a class="tools toggle" title="点击以切换"></a>
				<h4>缩略图</h4>
			</div>
			<div class="box-content">
				<?php echo F::form()->inputHidden('thumbnail', array('id'=>'thumbnail-id'))?>
				<div id="container"><a href="javascript:;" id="upload_thumbnail">设置缩略图</a></div>
				<?php if((F::form()->getData('thumbnail'))){
					$img_path = $this->url('admin/file/pic', array('t'=>2, 'f'=>F::form()->getData('thumbnail')));
				}else{
					$img_path = '';
				}?>
				<img src="<?php echo $img_path?>" <?php if(empty($img_path))echo 'style="display:none"';?> id="thumbnail-preview" />
			</div>
		</div>
		<?php }?>
		<?php if(in_array('seo', $boxes)){?>
		<div class="box">
			<div class="box-title">
				<a class="tools toggle" title="点击以切换"></a>
				<h4>SEO优化</h4>
			</div>
			<div class="box-content">
				<label for="seo-title">标题（title）</label>
				<?php echo F::form()->inputText('seo_title', array('id'=>'seo-title', 'class'=>'full-width'))?>
				<label for="seo-keyword">关键词（keywords）</label>
				<?php echo F::form()->inputText('seo_keywords', array('id'=>'seo-keywords', 'class'=>'full-width'))?>
				<label for="seo-description">描述（description）</label>
				<?php echo F::form()->textarea('seo_description', array('id'=>'seo-description', 'class'=>'full-width'))?>
			</div>
		</div>
		<?php }?>
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
			<div class="postarea">
				<div class="tabs">
					<div class="tab-nav">
						<ul>
							<li><a href="#tab-1" class="sel">属性</a></li>
							<li><a href="#tab-2">SKU</a></li>
							<li><a href="#tab-3">描述</a></li>
						</ul>
						<div class="clear"></div>
					</div>
					<div class="tab-content">
						<div id="tab-1" class="tab-item p5">
						<?php foreach($props as $p){
							if($p['is_sale_prop'])continue;?>
							<div class="form-field">
								<label class="title">
									<?php echo Html::encode($p['title'])?>
									<?php if($p['required']){?>
										<em class="color-red">(必选)</em>
									<?php }?>
								</label>
								<?php if($p['type'] == CatProps::TYPE_CHECK){//多选?>
								<div class="goods-prop-box">
									<ul class="goods-prop-list">
									<?php foreach($p['prop_values'] as $pv){?>
										<li>
										<?php 
											$alias = isset($goods['props'][$p['id']]['values'][$pv['id']]) ? $goods['props'][$p['id']]['values'][$pv['id']] : $pv['title'];
											$checked = isset($goods['props'][$p['id']]['values'][$pv['id']]);
											echo Html::inputCheckbox("cp[{$p['id']}][]", $pv['id'], $checked, array(
												'data-rule'=>'int',
												'data-label'=>$p['title'].'属性',
												'data-required'=>$p['required'] ? 'required' : false,
												'id'=>"cp-{$p['id']}-{$pv['id']}",
											));?>
										<label for="<?php echo "cp-{$p['id']}-{$pv['id']}"?>"
											class="<?php if($checked)echo 'hide'?>"><?php echo $pv['title']?></label>
										<?php 
											echo Html::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $alias, array(
												'class'=>'text-short '.($checked ? '' : 'hide'),
											));
										?>
										</li>
									<?php }?>
									</ul>
									<div class="clear"></div>
								</div>
								<?php 
								}else if($p['type'] == CatProps::TYPE_OPTIONAL){//单选
									$selected = isset($goods['props'][$p['id']]) ? array_keys($goods['props'][$p['id']]['values']) : array();
									echo Html::select("cp[{$p['id']}]", Html::getSelectOptions($p['prop_values'], 'id', 'title'), $selected);
								}else if($p['type'] == CatProps::TYPE_INPUT){//手工录入
									$value = isset($goods['props'][$p['id']]['values'][0]) ? $goods['props'][$p['id']]['values'][0] : '';
									echo Html::inputText("cp_alias[{$p['id']}][0]", $value, array(
										'data-rule'=>'string',
										'data-params'=>'{max:255}',
										'data-label'=>$p['title'].'属性',
										'data-required'=>$p['required'] ? 'required' : false,
									));
									echo Html::inputHidden("cp[{$p['id']}]", 0);
								}else if($p['type'] == CatProps::TYPE_BOOLEAN){//布尔?>
								<div class="goods-prop-box">
									<ul class="goods-prop-list">
									<?php foreach($p['prop_values'] as $pv){
										$alias = isset($goods['props'][$p['id']]['values'][$pv['id']]) ? $goods['props'][$p['id']]['values'][$pv['id']] : ($pv['title'] ? '是' : '否');
										$checked = isset($goods['props'][$p['id']]['values'][$pv['id']]);
										?>
										<li>
										<?php 
											echo Html::inputRadio("cp[{$p['id']}][]", $pv['id'], $checked, array(
												'id'=>"cp-{$p['id']}-{$pv['id']}",
											));?>
											<label for="<?php echo "cp-{$p['id']}-{$pv['id']}"?>"
												class="<?php if($checked)echo 'hide'?>"><?php echo $pv['title'] ? '是' : '否'?></label>
										<?php 
											echo Html::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $alias, array(
												'class'=>'text-short '.($checked ? '' : 'hide'),
											));
										?>
										</li>
									<?php }?>
									</ul>
									<div class="clear"></div>
								</div>
								<?php }?>
							</div>
						<?php }?>
						</div>
						<div id="tab-2" class="tab-item p5">
						<?php foreach($props as $p){
							if(!$p['is_sale_prop'])continue;?>
							<div class="sku-group form-field" data-name="<?php echo $p['title']?>" data-pid="<?php echo $p['id']?>">
								<label class="sku-label title"><?php echo $p['title']?>：</label>
								<div class="sku-box">
									<ul class="sku-list goods-prop-list">
									<?php foreach($p['prop_values'] as $pv){
										$alias = isset($goods['props'][$p['id']]['values'][$pv['id']]) ? $goods['props'][$p['id']]['values'][$pv['id']] : $pv['title'];
										$checked = isset($goods['props'][$p['id']]['values'][$pv['id']]);?>
										<li class="sku-item">
											<?php echo Html::inputCheckbox("cp_sale[{$p['id']}][]", $pv['id'], $checked, array(
												'id'=>"cp_sale_{$p['id']}_{$pv['id']}",
												'data-rule'=>'string',
												'data-params'=>'{max:255}',
												'data-label'=>$p['title'].'属性',
												'data-required'=>$p['required'] ? 'required' : false,
											))?>
											<label for="<?php echo "cp_sale_{$p['id']}_{$pv['id']}"?>"
												class="<?php if($checked)echo 'hide'?>"><?php echo $pv['title']?></label>
											<?php echo Html::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $alias, array(
												'class'=>'text-short '.($checked ? '' : 'hide'),
											))?>
										</li>
									<?php }?>
									</ul>
									<div class="clear"></div>
								</div>
							</div>
						<?php }?>
						
							<div id="sku-table-container"></div>
						</div>
						<div id="tab-3" class="tab-item">
							<?php echo F::form()->textarea('description', array(
								'id'=>'tab-3-textarea',
								'class'=>'lazy-kindeditor',
							))?>
						</div>
					</div>
				</div>
			</div>
			<div class="mt20 dragsort">
				<div class="box">
					<div class="box-title">
						<a class="tools toggle" title="点击以切换"></a>
						<h4>画廊</h4>
					</div>
					<div class="box-content">
						<div id="upload-photo-container">
							<a href="javascript:;" id="upload-photo-link" class="btn-1">上传图片</a>
						</div>
						<div class="photo-list">
						<?php foreach($goods['files'] as $p){?>
							<div class="photo-item">
								<input type="hidden" value="<?php echo $p['file_id']?>" name="photos[]" />
								<a class="photo-rm" pid="<?php echo $p['file_id']?>" href="javascript:;"></a>
								<span class="photo-thumb">
									<a class="photo-thumb-link fancybox-image" href="<?php echo $this->url('admin/file/pic', array('t'=>1,'f'=>$p['file_id']))?>">
										<img src="<?php echo $this->url('admin/file/pic', array('t'=>2,'f'=>$p['file_id']))?>" />
									</a>
								</span>
								<div class="photo-desc-container">
									<textarea class="photo-desc" maxlength="240" placeholder="照片描述" name="desc[<?php echo $p['file_id']?>]"><?php echo $p['desc']?></textarea>
								</div>
								<div class="clear"></div>
							</div>
						<?php }?>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->url()?>js/kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/browserplus-min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/goods.js"></script>
<script>
common.validformParams.tiptype = 2;//validform报错方式
common.validformParams.tipSweep = true;//表单提交时候才触发验证

$(function(){
	app.skus = <?php echo json_encode($goods['skus'])?>;
	app.init();
	
});
</script>