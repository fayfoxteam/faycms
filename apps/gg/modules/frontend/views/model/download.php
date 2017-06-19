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
<?php if(!$has_timestamps){?>

    public $timestamps = false;
<?php }?>
}