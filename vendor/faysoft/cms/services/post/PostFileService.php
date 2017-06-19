<?php
namespace cms\services\post;

use cms\services\file\FileService;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;

class PostFileService extends Service{
    /**
     * 默认返回字段
     */
    public static $default_fields = array('id', 'description', 'is_image', 'url');
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 获取文章附件
     * @param int $post_id 文章ID
     * @param string $fields 附件字段（posts_files表字段）
     * @return array 返回包含文章附件信息的二维数组
     */
    public function get($post_id, $fields = null){
        $fields || $fields = self::$default_fields;
        
        $sql = new Sql();
        $file_rows = $sql->from(array('pf'=>'posts_files'), 'post_id,description')
            ->joinLeft(array('f'=>'files'), 'pf.file_id = f.id', '*')
            ->where('post_id = ?', $post_id)
            ->order('pf.post_id, pf.sort')
            ->fetchAll();
        $files = array_values(FileService::mget($file_rows, array(), $fields));
        
        return $files;
    }
    
    /**
     * 批量获取文章附件
     * @param array $post_ids 文章ID构成的二维数组
     * @param string $fields 附件字段
     * @return array 返回以文章ID为key的三维数组
     */
    public function mget($post_ids, $fields = null){
        if(!$post_ids){
            return array();
        }
        $fields || $fields = self::$default_fields;
        
        $sql = new Sql();
        $file_rows = $sql->from(array('pf'=>'posts_files'), 'post_id,description')
            ->joinLeft(array('f'=>'files'), 'pf.file_id = f.id', '*')
            ->where('post_id IN (?)', $post_ids)
            ->order('pf.post_id, pf.sort')
            ->fetchAll();
        $files = FileService::mget($file_rows, array(), $fields);
        
        $return = array_fill_keys($post_ids, array());
        foreach($file_rows as $fr){
            $return[$fr['post_id']][] = $files[$fr['id']];
        }
        
        return $return;
    }
    
    /**
     * 将files信息装配到$posts中
     * @param array $posts 包含文章信息的三维数组
     *   若包含$posts.post.id字段，则以此字段作为文章ID
     *   若不包含$posts.post.id，则以$posts的键作为文章ID
     * @param null|string $fields 字段（posts_files表字段）
     */
    public function assemble(&$posts, $fields = null){
        if(empty($fields)){
            //若传入$fields为空，则返回默认字段
            $fields = self::$default_fields;
        }
        
        //获取所有文章ID
        $post_ids = array();
        foreach($posts as $k => $p){
            if(isset($p['post']['id'])){
                $post_ids[] = $p['post']['id'];
            }else{
                $post_ids[] = $k;
            }
        }
        
        $files_map = $this->mget($post_ids, $fields);
        
        foreach($posts as $k => $p){
            if(isset($p['post']['id'])){
                $post_id = $p['post']['id'];
            }else{
                $post_id = $k;
            }
            
            $p['files'] = $files_map[$post_id];
            
            $posts[$k] = $p;
        }
    }
}