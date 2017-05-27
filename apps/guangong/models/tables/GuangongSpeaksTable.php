<?php
namespace guangong\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Guangong speaks table model
 * 
 * @property int $id Id
 * @property string $name Name
 * @property string $photo_server_id Photo Server Id
 * @property int $photo Photo
 * @property string $words Words
 * @property int $create_time Create Time
 */
class GuangongSpeaksTable extends Table{
    protected $_name = 'guangong_speaks';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('photo'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('photo_server_id', 'words'), 'string', array('max'=>100)),
            
            array(array('photo_server_id', 'name', 'words'), 'required'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'Name',
            'photo_server_id'=>'Photo Server Id',
            'photo'=>'Photo',
            'words'=>'Words',
            'create_time'=>'Create Time',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'photo_server_id'=>'trim',
            'photo'=>'intval',
            'words'=>'trim',
        );
    }
}