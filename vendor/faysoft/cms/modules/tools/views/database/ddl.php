<?php
use fay\helpers\HtmlHelper;

echo HtmlHelper::textarea('code', $ddls, array(
	'style'=>'background:none repeat scroll 0 0 #F9F9F9;font-family:Consolas,Monaco,monospace;width:97%;',
	'id'=>'code',
	'class'=>'autosize',
));