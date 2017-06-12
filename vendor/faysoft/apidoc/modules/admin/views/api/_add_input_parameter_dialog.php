<?php
use fay\helpers\HtmlHelper;
use apidoc\models\tables\ApidocInputsTable;
?>
<div class="hide">
    <div id="add-input-parameter-dialog" class="dialog">
        <div class="dialog-content w600">
            <h4>添加请求参数</h4>
            <form class="input-parameter-form" id="add-input-parameter-form">
                <table class="form-table">
                    <tr>
                        <th class="adaption">名称<em class="required">*</em></th>
                        <td><?php echo F::form('input-parameter')->inputText('name', array(
                            'class'=>'form-control',
                        ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption">类型<em class="required">*</em></th>
                        <td><?php echo F::form('input-parameter')->select('type', ApidocInputsTable::getTypes(), array(
                            'class'=>'form-control w150 ib',
                        ), ApidocInputsTable::TYPE_STRING)?></td>
                    </tr>
                    <tr>
                        <th class="adaption">是否必须<em class="required">*</em></th>
                        <td><?php
                            echo F::form('input-parameter')->inputRadio('required', 1, array(
                                'label'=>'是',
                            ));
                            echo F::form('input-parameter')->inputRadio('required', 0, array(
                                'label'=>'否',
                            ), true);
                        ?></td>
                    </tr>
                    <tr>
                        <th class="adaption">描述</th>
                        <td><?php echo F::form('input-parameter')->textarea('description', array(
                            'class'=>'form-control h60 autosize',
                        ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption">示例值</th>
                        <td><?php echo F::form('input-parameter')->textarea('sample', array(
                            'class'=>'form-control h60 autosize',
                        ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption">自从</th>
                        <td><?php echo F::form('input-parameter')->inputText('since', array(
                            'class'=>'form-control w150 ib',
                        ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption"></th>
                        <td><?php
                            echo HtmlHelper::link('添加', 'javascript:', array(
                                'class'=>'btn mr10',
                                'id'=>'add-input-parameter-form-submit',
                            ));
                            echo HtmlHelper::link('取消', 'javascript:', array(
                                'class'=>'btn btn-grey fancybox-close',
                            ));
                        ?></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>