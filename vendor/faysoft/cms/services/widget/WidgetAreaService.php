<?php
namespace cms\services\widget;

use cms\models\tables\WidgetAreasTable;
use cms\models\tables\WidgetAreasWidgetsTable;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\NumberHelper;

class WidgetAreaService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    /**
     * 根据ID或别名获取小工具域
     * @param int|string $key
     * @param string|array $fields
     * @return array
     */
    public function get($key, $fields = '*'){
        if(NumberHelper::isInt($key)){
            $widget = WidgetAreasTable::model()->find($key, $fields);
        }else if(is_string($key)){
            $widget = WidgetAreasTable::model()->fetchRow(array(
                'alias = ?'=>$key,
            ), $fields);
        }else{
            throw new \InvalidArgumentException('指定小工具标识['.json_encode($key).'无法识别');
        }
        
        if(!$widget){
            throw new \UnexpectedValueException("指定小工具域ID或别名[{$key}]不存在");
        }
        
        return $widget;
    }

    /**
     * 获取所有小工具域
     * @param string|array $fields
     * @return array
     */
    public function getAll($fields = '*'){
        return WidgetAreasTable::model()->fetchAll(array(), $fields, 'sort, id');
    }

    /**
     * 获取一个小工具域下的所有小工具记录
     * @param int|string $id
     * @param bool $only_enabled
     * @return array
     */
    public function getWidgets($id, $only_enabled = true){
        $widget_area = $this->get($id, 'id');

        $sql = new Sql();
        return $sql->from(array('waw'=>'widget_areas_widgets'), '')
            ->joinLeft(array('w'=>'widgets'), 'waw.widget_id = w.id', '*')
            ->where('waw.widget_area_id = ?', $widget_area['id'])
            ->where(array(
                'waw.widget_area_id = ?'=>$widget_area['id'],
                'enabled = ?'=>$only_enabled ? 1 : false,
            ))
            ->order('waw.sort')
            ->fetchAll();
    }

    /**
     * 创建小工具
     * @param array $data
     * @return int
     */
    public function create($data){
        if(empty($data['alias'])){
            throw new \InvalidArgumentException('小工具域别名不能为空');
        }
        
        return WidgetAreasTable::model()->insert($data, true);
    }

    /**
     * @param int|string $id $id 小工具域id或别名
     * @param array $data
     * @return int
     */
    public function update($id, $data){
        if(empty($data['alias'])){
            throw new \InvalidArgumentException('小工具域别名不能为空');
        }
        
        $widget_area = $this->get($id, 'id');

        return WidgetAreasTable::model()->update($data, $widget_area['id'], true);
    }

    /**
     * 物理删除一个小工具域
     * @param int|string $id 小工具域id或别名
     * @return int
     */
    public function remove($id){
        $widget_area = $this->get($id, 'id');
        
        return WidgetAreasTable::model()->delete($widget_area['id']);
    }

    /**
     * 关联小工具
     * @param int $id 小工具域id或别名
     * @param array $widgets 小工具ID一维数组
     */
    public function relateWidget($id, $widgets){
        $widget_area = $this->get($id, 'id');
        
        //删除已删除的widget关联
        WidgetAreasWidgetsTable::model()->delete(array(
            'widget_area_id = ?'=>$widget_area['id'],
            'widget_id NOT IN (?)'=>$widgets,
        ));
        
        //获取已存在的widget关联
        $old_widgets = WidgetAreasWidgetsTable::model()->fetchCol('widget_id', array(
            'widget_area_id = ?'=>$widget_area['id'],
        ));
        
        //插入/更新widget关联
        $i = 1;
        foreach($widgets as $widget_id){
            if(!$widget_id){
                //可能会传入0这样的id，用以清空小工具域
                continue;
            }
            if(in_array($widget_id, $old_widgets)){
                WidgetAreasWidgetsTable::model()->update(array(
                    'sort'=>$i,
                ), array(
                    'widget_area_id = ?'=>$id,
                    'widget_id = ?'=>$widget_id,
                ));
            }else{
                WidgetAreasWidgetsTable::model()->insert(array(
                    'widget_area_id'=>$id,
                    'widget_id'=>$widget_id,
                    'sort'=>$i,
                ));
            }
            
            $i++;
        }
    }
}