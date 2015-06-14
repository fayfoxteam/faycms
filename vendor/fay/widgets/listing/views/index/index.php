<?php 
if(isset($config['values'])){
	foreach($config['values'] as $v){
		echo str_replace('{$value}', $v, $config['template']);
	}
}