<?php 
if(isset($data['data'])){
	foreach($data['data'] as $d){
		echo str_replace(array(
			'{$key}', '{$value}',
		), array(
			$d['key'], $d['value'],
		), $data['template']);
	}
}