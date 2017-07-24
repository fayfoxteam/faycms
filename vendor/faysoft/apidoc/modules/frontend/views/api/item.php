<?php
use apidoc\helpers\ApiHelper;
use apidoc\helpers\SampleHelper;
use apidoc\helpers\TrackHelper;
use fay\helpers\HtmlHelper;
use Michelf\MarkdownExtra;

/**
 * @var $api array
 * @var $common_inputs array
 */
?>
<?php if($api['api']['description']){?>
<div class="panel panel-headerless">
    <div class="panel-body"><?php
        echo MarkdownExtra::defaultTransform($api['api']['description']);
    ?></div>
</div>
<?php }?>
<div class="panel">
    <div class="panel-header">
        <h2><i class="fa fa-caret-down"></i>请求说明</h2>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-2 title">HTTP请求方式</label>
            <div class="col-10 pt7"><?php echo ApiHelper::getHttpMethod($api['api']['http_method']);?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">是否需要登录</label>
            <div class="col-10 pt7"><?php
                echo $api['api']['need_login'] ? '<span class="required">是</span>' : '否';
            ?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">状态</label>
            <div class="col-10 pt7"><?php echo ApiHelper::getStatus($api['api']['status'])?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">自从</label>
            <div class="col-10 pt7"><?php echo $api['api']['since']?></div>
        </div>
    </div>
</div>
<?php if($api['inputs']){?>
<div class="panel">
    <div class="panel-header closed">
        <h2><i class="fa fa-caret-down"></i>公共请求参数</h2>
    </div>
    <div class="panel-body hide">
        <table>
            <thead>
            <tr>
                <th width="22%">名称</th>
                <th width="15%">类型</th>
                <th width="10%">是否必须</th>
                <th width="12%">示例值</th>
                <th width="36%">描述</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($common_inputs as $input){?>
                <tr>
                    <td><?php echo HtmlHelper::encode($input['name'])?></td>
                    <td><?php echo ApiHelper::getInputType($input['type'])?></td>
                    <td><?php echo ApiHelper::getRequired($input['required'])?></td>
                    <td><?php echo SampleHelper::render($input['sample'])?></td>
                    <td><?php echo MarkdownExtra::defaultTransform($input['description'])?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<?php }?>
<div class="panel">
    <div class="panel-header">
        <h2><i class="fa fa-caret-down"></i>请求参数</h2>
    </div>
    <div class="panel-body">
    <?php if($api['inputs']){?>
        <table>
            <thead>
                <tr>
                    <th width="22%">名称</th>
                    <th width="15%">类型</th>
                    <th width="10%">是否必须</th>
                    <th width="12%">示例值</th>
                    <th width="36%">描述</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($api['inputs'] as $input){?>
                <tr>
                    <td><?php echo HtmlHelper::encode($input['name'])?></td>
                    <td><?php echo ApiHelper::getInputType($input['type'])?></td>
                    <td><?php echo ApiHelper::getRequired($input['required'])?></td>
                    <td><?php echo SampleHelper::render($input['sample'])?></td>
                    <td><?php echo MarkdownExtra::defaultTransform($input['description'])?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    <?php }else{?>
        <span>无</span>
    <?php }?>
    </div>
</div>
<div class="panel">
    <div class="panel-header">
        <h2><i class="fa fa-caret-down"></i>响应参数</h2>
    </div>
    <div class="panel-body">
    <?php if($api['outputs']){?>
        <table>
            <thead>
                <tr>
                    <th width="25%">名称</th>
                    <th width="20%">类型</th>
                    <th width="15%">示例值</th>
                    <th width="40%">描述</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($api['outputs'] as $output){?>
                <tr>
                    <td><?php echo HtmlHelper::encode($output['name'])?></td>
                    <td><?php
                        if($output['model_id'] >= 1000){
                            //对象类型特殊处理
                            echo HtmlHelper::link(
                                $output['model_name'],
                                TrackHelper::assembleTrackId(\apidoc\helpers\LinkHelper::generateModelLink($output['model_id']))
                            );
                        }else{
                            echo HtmlHelper::encode($output['model_name']);
                        }
                        if($output['is_array']){
                            echo ' []';
                        }
                    ?></td>
                    <td><?php echo SampleHelper::render($output['sample'])?></td>
                    <td><?php echo MarkdownExtra::defaultTransform($output['description'])?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    <?php }else{?>
        <span>无</span>
    <?php }?>
    </div>
</div>
<div class="panel">
    <div class="panel-header">
        <h2><i class="fa fa-caret-down"></i>错误码</h2>
    </div>
    <div class="panel-body">
    <?php if($api['error_codes']){?>
        <table>
            <thead>
            <tr>
                <th width="30%">错误码</th>
                <th width="30%">错误描述</th>
                <th width="40%">解决方案</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($api['error_codes'] as $error_code){?>
                <tr>
                    <td><?php echo HtmlHelper::encode($error_code['code'])?></td>
                    <td><?php echo MarkdownExtra::defaultTransform($error_code['description'])?></td>
                    <td><?php echo MarkdownExtra::defaultTransform($error_code['solution'])?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    <?php }else{?>
        <span>无</span>
    <?php }?>
    </div>
</div>
<div class="panel">
    <div class="panel-header">
        <h2><i class="fa fa-caret-down"></i>响应示例</h2>
    </div>
    <div class="panel-body">
        <?php if($api['api']['sample_response']){?>
            <pre id="sample_response" class="jsonview"><?php
                echo SampleHelper::render($api['api']['sample_response']);
                ?></pre>
        <?php }else{?>
            <span>无</span>
        <?php }?>
    </div>
</div>