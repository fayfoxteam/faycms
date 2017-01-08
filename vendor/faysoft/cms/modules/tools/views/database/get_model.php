<?php echo '<?php';?>

namespace <?php echo $namespace?>;

use fay\core\db\Table;

/**
 * <?php echo str_replace('_', ' ', ucfirst($table_name))?> table model
 * <?php foreach($fields as $f){?>

 * @property <?php
	if(strpos($f['Type'], 'int') === 0 || strpos($f['Type'], 'mediumint') === 0 ||
		strpos($f['Type'], 'smallint') === 0 || strpos($f['Type'], 'tinyint') === 0){
		echo 'int';
	}else if(strpos($f['Type'], 'decimal') === 0 || strpos($f['Type'], 'float') === 0){
		echo 'float';
	}else{
		echo 'string';
	}
	echo " \${$f['Field']} ";
	
	if(!empty($f['Comment'])){
		echo $f['Comment'];
	}else{
		echo ucwords(str_replace('_', ' ', $f['Field']));
	}
}?>

 */
class <?php echo $class_name?>Table extends Table{
	protected $_name = '<?php echo $table_name?>';
<?php if(count($primary) > 1){?>
	protected $_primary = array('<?php echo implode("', '", $primary)?>');
<?php }else if(count($primary) == 1 && current($primary) != 'id'){?>
	protected $_primary = '<?php echo current($primary)?>';
<?php }else if(count($primary) == 0){?>
	protected $_primary = null;
<?php }?>
	
	/**
	 * @param string $class_name
	 * @return <?php echo $class_name?>Table

	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
<?php
$int = array();
$int_unsigned = array();
$mediumint = array();
$mediumint_unsigned = array();
$smallint = array();
$smallint_unsigned = array();
$tinyint = array();
$tinyint_unsigned = array();
$varchar = array();
$decimal = array();
$length = array();
$float = array();
$email = array();
$mobile = array();
$datetime = array();
$range_0_1 = array();

foreach($fields as $f){
	if($f['Field'] == 'email'){
		$email[] = $f['Field'];
		
	}else if($f['Field'] == 'cellphone' || $f['Field'] == 'mobile'){
		$mobile[] = $f['Field'];
	}else if(strpos($f['Field'], 'time') !== false && strpos($f['Field'], 'times') === false && $f['Field'] != 'create_time' && $f['Field'] != 'last_modified_time'){
		$datetime[] = $f['Field'];
	}else if(strpos($f['Field'], 'is_') === 0 || $f['Field'] == 'deleted'){
		$range_0_1[] = $f['Field'];
	}else if(strpos($f['Type'], 'int') === 0 && !in_array($f['Field'], array('create_time', 'last_modified_time'))){//int
		if(strpos($f['Type'], 'unsigned') === false){
			$int[] = $f['Field'];
		}else{
			$int_unsigned[] = $f['Field'];
		}
	}else if(strpos($f['Type'], 'mediumint') === 0){//mediumint
		if(strpos($f['Type'], 'unsigned') === false){
			$mediumint[] = $f['Field'];
		}else{
			$mediumint_unsigned[] = $f['Field'];
		}
	}else if(strpos($f['Type'], 'smallint') === 0){//smallint
		if(strpos($f['Type'], 'unsigned') === false){
			$smallint[] = $f['Field'];
		}else{
			$smallint_unsigned[] = $f['Field'];
		}
	}else if(strpos($f['Type'], 'tinyint') === 0){//tinyint
		if(strpos($f['Type'], 'unsigned') === false){
			$tinyint[] = $f['Field'];
		}else{
			$tinyint_unsigned[] = $f['Field'];
		}
	}else if(strpos($f['Type'], 'varchar') === 0 || strpos($f['Type'], 'char') === 0){
		preg_match('/\((\d+)\)/', $f['Type'], $match);
		$length[$match[1]][] = $f['Field'];
	}else if(strpos($f['Type'], 'decimal') === 0 || strpos($f['Type'], 'float') === 0){
		preg_match('/\((\d+),(\d+)\)/', $f['Type'], $match);
		if(isset($match[1]) && isset($match[2])){
			$float[$match[1].'_'.$match[2]][] = $f['Field'];
		}
	}
}?>
		return array(
<?php if(!empty($int)){?>
			array(array('<?php echo implode("', '", $int)?>'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
<?php }?>
<?php if(!empty($int_unsigned)){?>
			array(array('<?php echo implode("', '", $int_unsigned)?>'), 'int', array('min'=>0, 'max'=>4294967295)),
<?php }?>
<?php if(!empty($mediumint)){?>
			array(array('<?php echo implode("', '", $mediumint)?>'), 'int', array('min'=>-8388608, 'max'=>8388607)),
<?php }?>
<?php if(!empty($mediumint_unsigned)){?>
			array(array('<?php echo implode("', '", $mediumint_unsigned)?>'), 'int', array('min'=>0, 'max'=>16777215)),
<?php }?>
<?php if(!empty($smallint)){?>
			array(array('<?php echo implode("', '", $smallint)?>'), 'int', array('min'=>-32768, 'max'=>32767)),
<?php }?>
<?php if(!empty($smallint_unsigned)){?>
			array(array('<?php echo implode("', '", $smallint_unsigned)?>'), 'int', array('min'=>0, 'max'=>65535)),
<?php }?>
<?php if(!empty($tinyint)){?>
			array(array('<?php echo implode("', '", $tinyint)?>'), 'int', array('min'=>-128, 'max'=>127)),
<?php }?>
<?php if(!empty($tinyint_unsigned)){?>
			array(array('<?php echo implode("', '", $tinyint_unsigned)?>'), 'int', array('min'=>0, 'max'=>255)),
<?php }?>
<?php if(!empty($length)){?>
<?php foreach($length as $k => $v){?>
			array(array('<?php echo implode("', '", $v)?>'), 'string', array('max'=><?php echo $k?>)),
<?php }?>
<?php }?>
<?php if(!empty($float)){?>
<?php foreach($float as $k => $v){?>
<?php $min_max = explode('_', $k)?>
			array(array('<?php echo implode("', '", $v)?>'), 'float', array('length'=><?php echo $min_max[0]?>, 'decimal'=><?php echo $min_max[1]?>)),
<?php }?>
<?php }?>
<?php if(!empty($range_0_1)){?>
			array(array('<?php echo implode("', '", $range_0_1)?>'), 'range', array('range'=>array(0, 1))),
<?php }?>
<?php if(!empty($datetime)){?>
			array(array('<?php echo implode("', '", $datetime)?>'), 'datetime'),
<?php }?>
<?php if(!empty($mobile)){?>
			array(array('<?php echo implode("', '", $mobile)?>'), 'mobile'),
<?php }?>
		);
	}

	public function labels(){
		return array(
<?php foreach($fields as $f){?>
<?php if(!empty($f['Comment'])){?>
			'<?php echo $f['Field']?>'=>'<?php echo $f['Comment']?>',
<?php }else{?>
			'<?php echo $f['Field']?>'=>'<?php echo ucwords(str_replace('_', ' ', $f['Field']))?>',
<?php }?>
<?php }?>
		);
	}

	public function filters(){
		return array(
<?php foreach($fields as $f){
		if(in_array($f['Field'], array('create_time', 'ip_int', 'last_modified_time', 'left_value', 'right_value'))){
			//这些字段肯定不会让用户来输入
			continue;
		}
		//即便不需要filter，也要放个空字段，否则无法model无法自动匹配数据
		$filter = array();
		if(strpos($f['Type'], 'int') === 0 || strpos($f['Type'], 'mediumint') === 0 ||
				strpos($f['Type'], 'smallint') === 0 || strpos($f['Type'], 'tinyint') === 0){
			if(strpos($f['Field'], '_time') === false){
				$filter[] = 'intval';
			}else if(!in_array($f['Field'], array('create_time', 'last_modified_time'))){//这两个字段肯定是服务器自己生的
				$filter[] = 'trim';
			}
		}
		if(strpos($f['Type'], 'varchar') === 0 || strpos($f['Type'], 'char') === 0){
			preg_match('/\(([\s\S]*?)\)/', $f['Type'], $match);
			$length[$match[1]][] = $f['Field'];
			$filter[] = 'trim';
		}
		if(strpos($f['Type'], 'decimal') === 0 || strpos($f['Type'], 'float') === 0){
			$filter[] = 'floatval';
		}
		if(strpos($f['Type'], 'text') === 0){
			//不做任何过滤
		}
?>
			'<?php echo $f['Field']?>'=>'<?php echo implode('|', $filter)?>',
<?php }?>
		);
	}
}