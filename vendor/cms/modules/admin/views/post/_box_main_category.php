<?php
use fay\helpers\Html;
use fay\models\Category;
?>
<div class="box" id="box-main-category" data-name="main-category">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>主分类</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->select('cat_id', array(
			Category::model()->getIdByAlias('_system_post')=>'--未分类--',
		)+Html::getSelectOptions($cats), array(
            'class'=>'form-control',
        ))?>
		<p class="fc-grey">修改文章主分类可能会影响附加属性（这取决于您是否设置有附加属性）</p>
	</div>
</div>