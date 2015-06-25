<?php 
if(isset($config['data'])){
	foreach($config['data'] as $d){
		echo str_replace(array(
			'{$key}', '{$value}',
		), array(
			$d['key'], $d['value'],
		), $config['template']);
	}
}