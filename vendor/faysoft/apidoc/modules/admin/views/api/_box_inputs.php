<?php
use fay\helpers\HtmlHelper;
use apidoc\models\tables\InputsTable;

$type_map = InputsTable::getTypes();
?>
<div class="box" id="box-inputs" data-name="inputs">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>请求参数</h4>
    </div>
    <div class="box-content">
        <div class="mb10">
            <?php echo HtmlHelper::link('添加请求参数', '#add-input-parameter-dialog', array(
                'class'=>'btn',
                'id'=>'add-input-parameter-link',
                'title'=>false,
            ))?>
        </div>
        <table class="list-table" id="input-parameter-table">
            <thead>
                <tr>
                    <th>名称</th>
                    <th>类型</th>
                    <th>必选</th>
                    <th>自从</th>
                    <th>描述</th>
                </tr>
            </thead>
            <tbody><?php if(!empty($inputs)){?>
            <?php foreach($inputs as $input){?>
                <tr valign="top" id="input-<?php echo $input['id']?>">
                    <td>
                        <?php
                            echo HtmlHelper::inputHidden("inputs[{$input['id']}][name]", $input['name'], array(
                                'class'=>'input-name',
                            ));
                            echo HtmlHelper::inputHidden("inputs[{$input['id']}][type]", $input['type'], array(
                                'class'=>'input-type',
                            ));
                            echo HtmlHelper::inputHidden("inputs[{$input['id']}][required]", $input['required'], array(
                                'class'=>'input-required',
                            ));
                            echo HtmlHelper::inputHidden("inputs[{$input['id']}][description]", $input['description'], array(
                                'class'=>'input-description',
                            ));
                            echo HtmlHelper::inputHidden("inputs[{$input['id']}][sample]", $input['sample'], array(
                                'class'=>'input-sample',
                            ));
                            echo HtmlHelper::inputHidden("inputs[{$input['id']}][since]", $input['since'], array(
                                'class'=>'input-since',
                            ));
                        ?>
                        <strong><?php echo HtmlHelper::encode($input['name'])?></strong>
                        <div class="row-actions"><?php
                            echo HtmlHelper::link('编辑', '#edit-input-parameter-dialog', array(
                                'class'=>'edit-input-parameter-link',
                            ));
                            echo HtmlHelper::link('删除', 'javascript:', array(
                                'class'=>'fc-red remove-input-parameter-link',
                            ));
                        ?></div>
                    </td>
                    <td><?php echo $type_map[$input['type']]?></td>
                    <td><?php echo $input['required'] ? '<span class="fc-green">是</span>' : '否'?></td>
                    <td><?php echo HtmlHelper::encode($input['since'])?></td>
                    <td><?php echo HtmlHelper::encode($input['description'])?></td>
                </tr>
            <?php }?>
            <?php }?></tbody>
        </table>
    </div>
</div>
<script>
$(function(){
    api.inputTypeMap = <?php echo json_encode($type_map)?>;
    api.validInputParameter(<?php echo json_encode(F::form('input-parameter')->getJsRules())?>, <?php echo json_encode(F::form('input-parameter')->getLabels())?>);
});
</script>