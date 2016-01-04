<?php
use fay\helpers\Html;
?>
<div id="skypes">
	<a href="javascript:;" class="close"></a>
	<div class="wrap">
		<ul><?php
			foreach($data as $d){
				echo Html::link($d['key'], 'skype:'.$d['value'], array(
					'wrapper'=>'li',
					'prepend'=>array(
						'tag'=>'img',
						'src'=>$this->appStatic('images/skype.png'),
					)
				));
			}
		?></ul>
	</div>
</div>