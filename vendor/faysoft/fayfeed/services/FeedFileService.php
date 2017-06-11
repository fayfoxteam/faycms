<?php
namespace fayfeed\services;

use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fayfeed\models\tables\FeedsFilesTable;
use cms\services\file\FileService;

class FeedFileService extends Service{
    /**
     * 默认返回字段
     */
    public static $default_fields = array('file_id', 'description');
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 获取动态附件
     * @param int $feed_id 动态ID
     * @param string $fields 附件字段（feeds_files表字段）
     * @return array 返回包含动态附件信息的二维数组
     */
    public function get($feed_id, $fields = null){
        $fields || $fields = self::$default_fields;

        $sql = new Sql();
        $file_rows = $sql->from(array('ff'=>FeedsFilesTable::model()->getTableName()), 'post_id,description')
            ->joinLeft(array('f'=>'files'), 'ff.file_id = f.id', '*')
            ->where('feed_id = ?', $feed_id)
            ->order('ff.post_id, ff.sort')
            ->fetchAll();
        $files = array_values(FileService::mget($file_rows, array(), $fields));

        return $files;
    }
    
    /**
     * 批量获取动态附件
     * @param array $feed_ids 动态ID构成的二维数组
     * @param string $fields 附件字段（feeds_files表字段）
     * @return array 返回以动态ID为key的三维数组
     */
    public function mget($feed_ids, $fields = null){
        if(!$feed_ids){
            return array();
        }
        $fields || $fields = self::$default_fields;

        $sql = new Sql();
        $file_rows = $sql->from(array('ff'=>FeedsFilesTable::model()->getTableName()), 'feed_id,description')
            ->joinLeft(array('f'=>'files'), 'ff.file_id = f.id', '*')
            ->where('feed_id IN (?)', $feed_ids)
            ->order('ff.feed_id, ff.sort')
            ->fetchAll();
        $files = FileService::mget($file_rows, array(), $fields);

        $return = array_fill_keys($feed_ids, array());
        foreach($file_rows as $fr){
            $return[$fr['feed_id']][] = $files[$fr['id']];
        }

        return $return;
    }
}