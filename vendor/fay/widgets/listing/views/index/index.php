<?php 
if(isset($data['values'])){
	foreach($data['values'] as $v){
		echo str_replace('{$value}', $v, $data['template']);
	}
}