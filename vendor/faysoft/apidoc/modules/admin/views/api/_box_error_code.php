<?php
use fay\helpers\HtmlHelper;
?>
<div class="box" id="box-inputs" data-name="error_code">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>错误码</h4>
    </div>
    <div class="box-content">
        <div class="mb10">
            <?php echo HtmlHelper::link('添加错误码', 'javascript:', array(
                'class'=>'btn',
                'id'=>'add-error-code-link',
                'title'=>false,
            ))?>
        </div>
        <table class="list-table" id="error-code-table">
            <thead>
                <tr>
                    <th>错误码</th>
                    <th>错误描述（支持MarkDown）</th>
                    <th>解决方案（支持MarkDown）</th>
                </tr>
            </thead>
            <tbody><?php if(!empty($error_codes)){?>
            <?php foreach($error_codes as $error_code){?>
                <tr valign="top" id="error-code-<?php echo $error_code['id']?>">
                    <td>
                        <?php echo HtmlHelper::inputText(
                            "error_codes[{$error_code['id']}][code]",
                            $error_code['code'],
                            array(
                                'class'=>'form-control error-code',
                            )
                        )?>
                        <div class="row-actions"><?php
                            echo HtmlHelper::link('删除', 'javascript:', array(
                                'class'=>'fc-red remove-error-code-link',
                            ));
                        ?></div>
                    </td>
                    <td><?php echo HtmlHelper::textarea(
                        "error_codes[{$error_code['id']}][description]",
                        $error_code['description'],
                        array(
                            'class'=>'form-control autosize error-description',
                        )
                    )?></td>
                    <td><?php echo HtmlHelper::textarea(
                        "error_codes[{$error_code['id']}][solution]",
                        $error_code['solution'],
                        array(
                            'class'=>'form-control autosize error-solution',
                        )
                    )?></td>
                </tr>
            <?php }?>
            <?php }?></tbody>
        </table>
    </div>
</div>