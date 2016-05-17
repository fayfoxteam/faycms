<?php
namespace fay\helpers;

class Backtrace{
	/**
	 * 渲染一个堆栈的table
	 * @param array $backtrace 堆栈数组
	 */
	public static function render($backtrace = null){
		$base_path = dirname(BASEPATH);//除去最后的public/
		$base_path_length = strlen($base_path);
		$backtrace === null && $backtrace = array_slice(debug_backtrace(false), 1);
		echo '<table class="trace-table debug-table">',
			'<tr>',
				'<th>#</th>',
				'<th>File</th>',
				'<th>Line</th>',
				'<th>Function</th>',
			'</tr>';
		foreach($backtrace as $k=>$b){
			echo '<tr>',
				"<td>{$k}</td>",
				'<td>'.(isset($b['file']) ? substr($b['file'], $base_path_length) : '').'</td>',
				'<td>'.(isset($b['line']) ? $b['line'] : '').'</td>';
			
			if(isset($b['type'])){
				if(isset($b['class'])){
					echo "<td>{$b['class']}{$b['type']}{$b['function']}()</td>";
				}else{
					echo "<td>{$b['function']}()</td>";
				}
			}else{
				echo "<td>{$b['function']}()</td>";
			}
			
			echo '</tr>';
		}
		echo '</table>';
	}
}