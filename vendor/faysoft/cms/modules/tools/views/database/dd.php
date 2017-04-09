<?php
use fay\helpers\HtmlHelper;
?>
<div class="row">
    <div class="col-9">
        <div class="col-2-2-body-content">
            <div class="mb10">
                <h3>Design</h3>
                <table class="list-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Null</th>
                            <th>Key</th>
                            <th>Default</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($fields as $f){?>
                        <tr>
                            <td><?php echo $f['Field']?></td>
                            <td><?php echo $f['Type']?></td>
                            <td><?php echo $f['Null']?></td>
                            <td><?php echo $f['Key']?></td>
                            <td><?php if($f['Default'] === ''){
                                echo 'Empty String';
                            }else if($f['Default'] === null){
                                echo 'NULL';
                            }else{
                                echo $f['Default'];
                            }?></td>
                            <td><?php echo $labels[$f['Field']]?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <h3>DDL</h3>
            <?php
                //将前缀替换为占位符
                $create_table = str_replace('CREATE TABLE `'.$prefix, 'CREATE TABLE `{{$prefix}}', $ddl['Create Table']);
                //将表编码替换为占位符
                $create_table = preg_replace('/ DEFAULT CHARSET=\w+/', ' DEFAULT CHARSET={{$charset}}', $create_table);
                echo HtmlHelper::textarea('code', $create_table, array(
                    'style'=>'font-family:Consolas,Monaco,monospace',
                    'id'=>'code',
                    'class'=>'form-control autosize',
                ));
            ?>
        </div>
    </div>
    <div class="col-3">
        <h3>Tables</h3>
        <ul class="table-list">
        <?php foreach($tables as $t){
            $t_name = preg_replace("/^{$prefix}(.*)/", '$1', array_shift($t), 1);
            if(strpos($t_name, '_') &&
                in_array(substr($t_name, 0, strpos($t_name, '_')), $apps) &&
                substr($t_name, 0, strpos($t_name, '_')) != APPLICATION){
                continue;
            }?>
            <li class="<?php if($t_name == $current_table)echo 'disc';?>">
            <?php if($t_name == $current_table){
                echo $t_name;
            }else{
                echo HtmlHelper::link($t_name, array('cms/tools/database/dd', array(
                    't'=>$t_name,
                )));
            }?>
            <span class="fr">
                <?php echo HtmlHelper::link('model', array('cms/tools/database/model', array(
                    't'=>$t_name,
                )))?>
            </span>
            </li>
        <?php }?>
        </ul>
    </div>
</div>
<script>
$(function(){

});
</script>