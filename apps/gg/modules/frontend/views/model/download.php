<?php echo '<?php';?>

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
<?php if($soft_delete){?>
use Illuminate\Database\Eloquent\SoftDeletes;
<?php }?>

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