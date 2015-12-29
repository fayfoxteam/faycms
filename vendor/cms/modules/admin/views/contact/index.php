<?php
?>
<style>
.contact-item{margin-bottom:30px;background-color:#fff;padding:20px;}
.contact-item .ci-header{color:#979898;}
.contact-item h3{color:#2c2e2f;font-size:23px;font-weight:normal;margin-bottom:10px;}
.contact-item .ci-options{float:right;}
.contact-item .ci-header{margin-bottom:20px;}
.contact-item .ci-header span{margin-left:11px;}
.contact-item .ci-header span:first-child{margin-left:0;}
.contact-item .ci-header .fa{margin-right:5px;}
.contact-item .ci-content{color:#7d7f7f;margin-bottom:20px;}
.contact-item .ci-reply{border:1px solid #e4e4e4;padding:15px 20px;color:#979898;}
.contact-item .ci-reply strong{color:#575858;}
</style>
<div class="row">
	<div class="col-12">
		<ul class="contact-list">
			<?php $listview->showData(array(
				'settings'=>F::form('setting')->getAllData(),
			));?>
		</ul>
		<?php $listview->showPager();?>
	</div>
</div>