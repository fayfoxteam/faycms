<?php
namespace fay\helpers;

/**
 * 记录系统运行到各个节点的时间，最后在debug中输出
 */
class Runtime{
	private static $runtimes = array();
	
	public static function append($file, $line, $note){
		self::$runtimes[] = array(
			'time'=>microtime(true),
			'memory'=>memory_get_usage(),
			'location'=>$file . ':' . $line,
			'note'=>$note,
		);
	}
	
	public static function render(){
		$base_path = dirname(BASEPATH);//除去最后的public/
		$base_path_length = strlen($base_path);
		
		echo '<table class="trace-table debug-table">',
		'<tr>',
			'<th>#</th>',
			'<th>Time</th>',
			'<th>Memory</th>',
			'<th>Note</th>',
			'<th>Location</th>',
		'</tr>';
		foreach(self::$runtimes as $k=>$r){
			echo '<tr>',
				"<td>{$k}</td>",
				'<td>', number_format(($r['time'] - START) * 1000, 4), 'ms</td>',
				'<td>', number_format($r['memory'] / 1024, 2), 'KB</td>',
				"<td>{$r['note']}</td>",
				'<td>', substr($r['location'], $base_path_length), '</td>',
			'</tr>';
			
		}
		echo '</table>';
	}
}