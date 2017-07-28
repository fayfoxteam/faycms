<?php echo '<?php';?>

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
<?php if($soft_delete){?>
use Illuminate\Database\Eloquent\SoftDeletes;
<?php }?>

/**
 * <?php
if($table_comment){
    echo $table_comment;
}else{
    echo str_replace('_', ' ', ucfirst($table)), ' table model';
}
?>

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
class <?php echo $class_name?> extends Model
{
<?php if($soft_delete){?>
    use SoftDeletes;

<?php }?>
    protected $table = '<?php echo $table?>';
<?php if($guarded){?>

    protected $guarded = ['<?php echo implode("', '", $guarded)?>'];
<?php }?>
<?php if($fillable){?>

    protected $fillable = ['<?php echo implode("', '", $fillable)?>'];
<?php }?>
<?php if(!$has_timestamps){?>

    public $timestamps = false;
<?php }?>

<?php
$rules = array();

foreach($fields as $f){
    if($f['Field'] == 'email'){
        $rules[$f['Field']][] = 'email';
    }else if($f['Field'] == 'cellphone' || $f['Field'] == 'mobile'){
        $rules[$f['Field']][] = 'regex:/^1[0-9]{10}$/';
    }else if(strpos($f['Field'], 'time') !== false && strpos($f['Field'], 'times') === false && $f['Field'] != 'created_at' && $f['Field'] != 'updated_at' && $f['Field'] != 'deleted_at'){
        $rules[$f['Field']][] = 'date';
    }else if(strpos($f['Field'], 'is_') === 0){
        $rules[$f['Field']][] = 'in:0,1';
    }else if(strpos($f['Type'], 'int') === 0 && !in_array($f['Field'], array('created_at', 'updated_at', 'deleted_at', 'created_ip', 'updated_ip'))){//int
        $rules[$f['Field']][] = 'integer';
        if(strpos($f['Type'], 'unsigned') === false){
            $rules[$f['Field']][] = 'min:-2147483648';
            $rules[$f['Field']][] = 'max:2147483647';
        }else{
            $rules[$f['Field']][] = 'min:0';
            $rules[$f['Field']][] = 'max:4294967295';
        }
    }else if(strpos($f['Type'], 'mediumint') === 0){//mediumint
        $rules[$f['Field']][] = 'integer';
        if(strpos($f['Type'], 'unsigned') === false){
            $rules[$f['Field']][] = 'min:-8388608';
            $rules[$f['Field']][] = 'max:8388607';
        }else{
            $rules[$f['Field']][] = 'min:0';
            $rules[$f['Field']][] = 'max:16777215';
        }
    }else if(strpos($f['Type'], 'smallint') === 0){//smallint
        $rules[$f['Field']][] = 'integer';
        if(strpos($f['Type'], 'unsigned') === false){
            $rules[$f['Field']][] = 'min:-32768';
            $rules[$f['Field']][] = 'max:32767';
        }else{
            $rules[$f['Field']][] = 'min:0';
            $rules[$f['Field']][] = 'max:65535';
        }
    }else if(strpos($f['Type'], 'tinyint') === 0){//tinyint
        $rules[$f['Field']][] = 'integer';
        if(strpos($f['Type'], 'unsigned') === false){
            $rules[$f['Field']][] = 'min:-128';
            $rules[$f['Field']][] = 'max:127';
        }else{
            $rules[$f['Field']][] = 'min:0';
            $rules[$f['Field']][] = 'max:255';
        }
    }else if(strpos($f['Type'], 'varchar') === 0 || strpos($f['Type'], 'char') === 0){
        preg_match('/\((\d+)\)/', $f['Type'], $match);
        $rules[$f['Field']][] = 'max:'.$match[1];
    }
}?>
    public static function rules(){
        return [
<?php foreach($rules as $key => $rule){?>
            '<?php echo $key?>'=>'<?php echo implode('|', $rule)?>',
<?php }?>
<?php foreach($fields as $f){
    if(!isset($rules[$f['Field']])){?>
            '<?php echo $f['Field'] ?>'=>'',
<?php }
}?>
        ];
    }
}