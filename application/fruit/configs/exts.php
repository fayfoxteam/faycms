<?php
/**
 * 所有数组项均会被转为正则表达式进行匹配，转换规则
 *     / => \/
 *     * => .*
 */
return array(
	'/'=>array('product', 'post'),
);