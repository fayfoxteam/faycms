<?php
/**
 * @var $listview \fay\common\ListView
 */
use cms\services\prop\PropService;

?>
<?php echo F::form('search')->open(null, 'get', array(
    'class'=>'form-inline',
))?>
    <div class="mb5"><?php
        $usages = array();
        foreach(PropService::$usage_type_map as $usage_type => $usage_type_class){
            $usages[$usage_type] = PropService::service()->getUsageModel($usage_type)->getUsageName();
        }
        echo F::form('search')->inputHidden('id'),
        F::form('search')->select('search_usage_type', array(''=>'--用途--') + $usages, array(
            'class'=>'form-control',
        )),
        F::form('search')->submitLink('搜索', array(
            'class'=>'btn btn-sm ml5',    
        ));
    ?></div>
<?php echo F::form('search')->close()?>
<table class="list-table props">
    <thead>
    <tr>
        <th>属性名称</th>
        <th class="w90">表单元素</th>
        <th class="w150">用途</th>
        <th class="w90">必选</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>属性名称</th>
        <th>表单元素</th>
        <th>用途</th>
        <th>必选</th>
    </tr>
    </tfoot>
    <tbody>
    <?php $listview->showData()?>
    </tbody>
</table>
<?php $listview->showPager();?>