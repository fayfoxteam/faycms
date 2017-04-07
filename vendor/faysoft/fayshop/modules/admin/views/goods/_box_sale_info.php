<div class="box" id="box-sale-info" data-name="sale_info">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>销售信息</h4>
	</div>
	<div class="box-content">
		<div class="misc-pub-section b0">
			<label for="sku-price"><strong>价格</strong></label>
			<?php echo F::form()->inputText('price', array(
				'id'=>'sku-price',
				'class'=>'form-control w70 ib',
			))?>
			<span class="fc-grey">单位：元</span>
			<p class="fc-grey">若存在sku，则售价必须在sku表的最低售价与最高售价之间</p>
		</div>
		<div class="misc-pub-section">
			<label for="sku-total-num"><strong>库存</strong></label>
			<?php echo F::form()->inputText('num', array(
				'id'=>'sku-total-num',
				'class'=>'form-control w70 ib',
			))?>
		</div>
		<div class="misc-pub-section">
			<label><strong>货号</strong></label>
			<?php echo F::form()->inputText('sn', array(
				'class'=>'form-control mw150 ib',
			))?>
		</div>
	</div>
</div>