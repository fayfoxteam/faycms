<?php
return array(
	array(
		//错误日志
		'class'=>'fay\log\FileTarget',
		'levels'=>array('error', 'warning'),
		'categories'=>array('error'),
		'options'=>array(
			'logFile'=>APPLICATION_PATH . 'runtimes/logs/error.' . date('Y-m-d') . '.log'
		)
	)
);