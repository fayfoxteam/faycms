<?php
use apidoc\helpers\SampleHelper;

/**
 * @var $models array
 */
?>
<div class="panel">
    <div class="panel-header"><h2>基础数据类型</h2></div>
    <div class="panel-body">
    <?php if($models){?>
        <table>
            <thead>
                <tr>
                    <th width="30%">名称</th>
                    <th width="25%">示例值</th>
                    <th width="45%">描述</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($models as $m){?>
                <tr>
                    <td><?php echo $m['name']?></td>
                    <td><?php echo SampleHelper::render($m['sample'])?></td>
                    <td><?php echo $m['description']?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    <?php }else{?>
        <span>无</span>
    <?php }?>
    </div>
</div>