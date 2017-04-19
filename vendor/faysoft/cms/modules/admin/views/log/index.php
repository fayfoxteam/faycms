<?php
use cms\helpers\ListTableHelper;
use fay\helpers\HtmlHelper;
use cms\models\tables\LogsTable;

/**
 * @var $iplocation \IpLocation
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
    <div class="col-7">
        <?php echo F::form('search')->open(null, 'get', array(
            'class'=>'form-inline',
        ))?>
            <div class="mb5">
                Code：<?php echo F::form('search')->inputText('code', array(
                    'class'=>'form-control',
                ));?>
                |
                <?php echo F::form('search')->select('type', array(
                    ''=>'--类型--',
                    LogsTable::TYPE_NORMAL=>'正常',
                    LogsTable::TYPE_ERROR=>'错误',
                    LogsTable::TYPE_WARMING=>'警告',
                ), array(
                    'class'=>'form-control',
                ))?>
                <a href="javascript:;" class="btn btn-sm" id="search-form-submit">查询</a>
            </div>
        <?php echo F::form('search')->close()?>
    </div>
    <div class="col-5">
        <?php $listview->showPager()?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="list-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>类型</th>
                    <th>Data</th>
                    <th>用户</th>
                    <th class="wp15"><?php echo ListTableHelper::getSortLink('create_time', '生成时间')?></th>
                    <th class="wp15">IP</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Code</th>
                    <th>类型</th>
                    <th>Data</th>
                    <th>用户</th>
                    <th><?php echo ListTableHelper::getSortLink('create_time', '生成时间')?></th>
                    <th>IP</th>
                </tr>
            </tfoot>
            <tbody>
                <?php $listview->showData(array(
                    'iplocation'=>$iplocation,
                ))?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?php $listview->showPager()?>
    </div>
</div>
<div class="hide">
    <div id="log-detail-dialog" class="dialog">
        <div class="dialog-content w600">
            <h4>日志</h4>
            <table class="form-table">
                <tr>
                    <th class="adaption">Code</th>
                    <td><?php echo HtmlHelper::inputText('', '', array(
                        'class'=>'form-control',
                        'id'=>'ld-code',
                    ))?></td>
                </tr>
                <tr>
                    <th valign="top" class="adaption">Data</th>
                    <td><?php echo HtmlHelper::textarea('', '', array(
                        'class'=>'form-control h90 autosize',
                        'rows'=>5,
                        'id'=>'ld-data',
                    ))?></td>
                </tr>
                <tr>
                    <th class="adaption">Create Time</th>
                    <td>
                        <?php echo HtmlHelper::inputText('', '', array(
                            'class'=>'form-control',
                            'id'=>'ld-create_time',
                        ))?>
                    </td>
                </tr>
                <tr>
                    <th class="adaption">User</th>
                    <td>
                        <?php echo HtmlHelper::inputText('', '', array(
                            'class'=>'form-control',
                            'id'=>'ld-username',
                        ))?>
                    </td>
                </tr>
                <tr>
                    <th class="adaption">User Agent</th>
                    <td>
                        <?php echo HtmlHelper::textarea('', '', array(
                            'class'=>'form-control autosize',
                            'id'=>'ld-user_agent',
                        ))?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script>
$(function(){
    system.getCss(system.assets('js/fancybox-3.0/dist/jquery.fancybox.min.css'), function(){
        system.getScript(system.assets('js/fancybox-3.0/dist/jquery.fancybox.min.js'), function(){
            $(".quick-view").fancybox({
                'padding':0,
                'titleShow':false,
                'centerOnScroll':true,
                'onComplete':function(o){
                    $("#log-detail-dialog").block({
                        'zindex': 120000
                    });
                    $.ajax({
                        type: "GET",
                        url: system.url("cms/admin/log/get"),
                        data: {"id":$(o).attr('data-id')},
                        dataType: "json",
                        cache: false,
                        success: function(resp){
                            $("#log-detail-dialog").unblock();
                            if(resp.status){
                                $("#ld-code").val(resp.data.code);
                                $("#ld-data").val(resp.data.data);
                                $("#ld-user_agent").val(resp.data.user_agent);
                                autosize.update($("#ld-data"));
                                autosize.update($("#ld-user_agent"));
                                $("#ld-create_time").val(system.date(resp.data.create_time));
                                if(resp.data.user_id == 0){
                                    $("#ld-username").val('系统');
                                }else{
                                    $("#ld-username").val(resp.data.username);
                                }
                            }else{
                                common.alert(resp.message);
                            }
                        }
                    });
                },
                'onClosed':function(){
                    $("#log-detail-dialog").unblock();
                }
            });
        });
    });
});
</script>