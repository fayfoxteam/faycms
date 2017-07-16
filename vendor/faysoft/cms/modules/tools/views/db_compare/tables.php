<?php
use fay\helpers\HtmlHelper;

/**
 * @var $db_config array
 * @var $all_tables array
 * @var $left_tables array
 * @var $right_tables array
 * @var $diff_tables array
 */
?>
<div class="row">
    <div class="col-6">
        <table class="list-table" id="left-db-tables">
            <thead><tr>
                <th class="bold"><?php echo $db_config['left']['host'], '/', $db_config['left']['dbname']?></th>
            </tr></thead>
            <tbody>
            <?php foreach($all_tables as $t){?>
            <?php if(in_array($t, $left_tables)){?>
            <tr class="lt-<?php echo $t;?>">
                <td class="<?php echo in_array($t, $right_tables) ? (in_array($t, $diff_tables) ? 'bl-red' : 'pl11') : 'bl-yellow';?>">
                    <span class="fr row-actions separate-actions pt0"><?php
                        if(in_array($t, $right_tables)){
                            echo HtmlHelper::link('<i class="fa fa-exchange"></i>', array('cms/tools/db-compare/table', array(
                                'name'=>$t,
                            )), array(
                                'title'=>'Table Compare',
                                'encode'=>false,
                            ));
                            echo HtmlHelper::link('<i class="fa fa-arrow-right"></i>', '#data-transfer-dialog', array(
                                'data-name'=>$t,
                                'data-db'=>'left',
                                'class'=>'data-transfer',
                                'title'=>'Data Transfer',
                                'encode'=>false,
                            ));
                        }
                        echo HtmlHelper::link('<i class="fa fa-eye"></i>', '#ddl-dialog', array(
                            'data-name'=>$t,
                            'data-db'=>'left',
                            'class'=>'show-ddl',
                            'title'=>'DLL',
                            'encode'=>false,
                        ));
                    ?></span>
                    <strong><?php echo $t?></strong>
                </td>
            </tr>
            <?php }else{?>
                <tr><td>&nbsp;</td></tr>
            <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="col-6">
        <table class="list-table">
            <thead><tr>
                <th class="bold"><?php echo $db_config['right']['host'], '/', $db_config['right']['dbname']?></th>
            </tr></thead>
            <tbody>
            <?php foreach($all_tables as $t){?>
            <?php if(in_array($t, $right_tables)){?>
                <tr class="rt-<?php echo $t;?>">
                    <td class="<?php echo in_array($t, $left_tables) ? (in_array($t, $diff_tables) ? 'bl-red' : 'pl11') : 'bl-yellow';?>">
                        <span class="fr row-actions separate-actions pt0"><?php
                            if(in_array($t, $left_tables)){
                                echo HtmlHelper::link('<i class="fa fa-exchange"></i>', array('cms/tools/db-compare/table', array(
                                    'name'=>$t,
                                )), array(
                                    'title'=>'Table Compare',
                                    'encode'=>false,
                                ));
                                echo HtmlHelper::link('<i class="fa fa-arrow-left"></i>', '#data-transfer-dialog', array(
                                    'data-name'=>$t,
                                    'data-db'=>'right',
                                    'class'=>'data-transfer',
                                    'title'=>'Data Transfer',
                                    'encode'=>false,
                                ));
                            }
                            echo HtmlHelper::link('<i class="fa fa-eye"></i>', '#ddl-dialog', array(
                                'data-name'=>$t,
                                'data-db'=>'right',
                                'class'=>'show-ddl',
                                'title'=>'DLL',
                                'encode'=>false,
                            ));
                        ?></span>
                        <strong><?php echo $t?></strong>
                    </td>
                </tr>
            <?php }else{?>
                <tr><td>&nbsp;</td></tr>
            <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<div class="hide">
    <div id="ddl-dialog" class="dialog">
        <div class="dialog-content w800">
            <h4></h4>
            <div class="mb5">
                <h3>Fields</h3>
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
                    <tbody></tbody>
                </table>
            </div>
            <h3>DDL</h3>
            <?php echo HtmlHelper::textarea('code', '', array(
                'style'=>'font-family:Consolas,Monaco,monospace',
                'id'=>'ddl-code',
                'class'=>'form-control autosize',
            ))?>
        </div>
    </div>
</div>
<div class="hide">
    <div id="data-transfer-dialog" class="dialog">
        <div class="dialog-content w800">
            <h4><span id="db-table-name"></span> (<span class="db-table-from"></span> &gt; <span class="db-table-to"></span>)</h4>
            <form id="transfer-form">
                <input type="hidden" name="from" />
                <input type="hidden" name="name" />
                <div class="mb5">
                    <h3>Common Fields</h3>
                    <p id="db-table-common-fields"></p>
                </div>
                <div class="mb5" id="db-table-from-separate-fields-container">
                    <h3><span class="db-table-from"></span> Separate Fields</h3>
                    <p id="db-table-from-separate-fields"></p>
                </div>
                <div class="mb5" id="db-table-to-separate-fields-container">
                    <h3><span class="db-table-to"></span> Separate Fields</h3>
                    <p id="db-table-to-separate-fields"></p>
                </div>
                <div class="mb5" id="db-table-to-separate-fields-container">
                    <h3>TRUNCATE</h3>
                    <label><input type="radio" name="truncate" value="1" checked="checked" />YES</label>
                    <label><input type="radio" name="truncate" value="0" />NO</label>
                </div>
                <div class="mb5">
                    <a href="javascript:" id="start-transfer" class="btn">Start Transfer</a>
                    <span class="fc-red">This process may be longer, please do not close the browser or jump page.</span>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
var db_config = {
    'left':{
        'host':'<?php echo $db_config['left']['host']?>',
        'dbname':'<?php echo $db_config['left']['dbname']?>'
    },
    'right':{
        'host':'<?php echo $db_config['right']['host']?>',
        'dbname':'<?php echo $db_config['right']['dbname']?>'
    }
};

$(function(){
    $(document).on('mouseenter', '.list-table tbody tr', function(){
        var index = $(this).index();
        $('.list-table').each(function(){
            $(this).find('tbody tr:eq('+index+')').addClass('hover').siblings().removeClass('hover');
        });
    }).on('mouseleave', '.list-table tbody tr', function(){
        var index = $(this).index();
        $('.list-table').each(function(){
            $(this).find('tbody tr:eq('+index+')').removeClass('hover');
        });
    }).on('click', '#start-transfer', function(){
        //数据迁移
        $('#data-transfer-dialog').block({
            'zindex': 120000
        });
        $.ajax({
            'type': 'GET',
            'url': system.url('cms/tools/db-compare/transfer'),
            'data': $('#transfer-form').serialize(),
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                $('#data-transfer-dialog').unblock();
                if(resp.status){
                    $.fancybox.close();
                    alert(resp.message);
                }else{
                    alert(resp.message);
                }
            }
        });
    });

    //数据传输
    common.loadFancybox(function(){
        $(".data-transfer").fancybox({
            'caption': function(){return ''},
            'onComplete':function(instance, slide){
                var $transferForm = $('#transfer-form');
                if(slide.opts.$orig.attr('data-db') == 'left'){
                    $transferForm.find('[name="from"]').val('left');
                    $('.db-table-from').text(db_config.left.host + '/' + db_config.left.dbname);
                    $('.db-table-to').text(db_config.right.host + '/' + db_config.right.dbname);
                }else{
                    $transferForm.find('[name="from"]').val('right');
                    $('.db-table-from').text(db_config.right.host + '/' + db_config.right.dbname);
                    $('.db-table-to').text(db_config.left.host + '/' + db_config.left.dbname);
                }
                $('#db-table-name').text(slide.opts.$orig.attr('data-name'));
                $transferForm.find('[name="name"]').val(slide.opts.$orig.attr('data-name'));
                
                $('#data-transfer-dialog').block({
                    'zindex': 120000
                });
                
                $.ajax({
                    'type': 'GET',
                    'url': system.url('cms/tools/db-compare/get-fields'),
                    'data': {
                        'name':slide.opts.$orig.attr('data-name')
                    },
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        $('#data-transfer-dialog').unblock();
                        if(resp.status){
                            var $dbTableFromSeparateFieldsContainer = $('#db-table-from-separate-fields-container');
                            var $dbTableToSeparateFieldsContainer = $('#db-table-to-separate-fields-container');
                            var $dbTableFromSeparateFields = $('#db-table-from-separate-fields');
                            var $dbTableToSeparateFields = $('#db-table-to-separate-fields');
                            if(slide.opts.$orig.attr('data-db') == 'left'){
                                //点了左侧的传输
                                if(resp.data.left.length){
                                    $dbTableFromSeparateFieldsContainer.show();
                                    $dbTableFromSeparateFields.html(resp.data.left.join(', '));
                                }else{
                                    $dbTableFromSeparateFieldsContainer.hide();
                                }
                                if(resp.data.right.length){
                                    $dbTableToSeparateFieldsContainer.show();
                                    $dbTableToSeparateFields.html(resp.data.right.join(', '));
                                }else{
                                    $dbTableToSeparateFieldsContainer.hide();
                                }
                            }else{
                                //点了右侧的传输
                                if(resp.data.right.length){
                                    $dbTableFromSeparateFieldsContainer.show();
                                    $dbTableFromSeparateFields.html(resp.data.right.join(', '));
                                }else{
                                    $dbTableFromSeparateFieldsContainer.hide();
                                }
                                if(resp.data.left.length){
                                    $dbTableToSeparateFieldsContainer.show();
                                    $dbTableToSeparateFields.html(resp.data.left.join(', '));
                                }else{
                                    $dbTableToSeparateFieldsContainer.hide();
                                }
                            }

                            $('#db-table-common-fields').html('');
                            $.each(resp.data.common, function(i, data){
                                $('#db-table-common-fields').append(['<p><label>',
                                    '<input type="checkbox" name="fields[]" value="', data,'" checked="checked" />',
                                    data,
                                '</label></p>'].join(''));
                            });
                        }
                    }
                });
            },
            'onClosed':function(){
                $('#data-transfer-dialog').unblock();
            }
        });
    });
    
    //dll弹窗
    common.loadFancybox(function(){
        var $dllDialog = $('#ddl-dialog');
        $(".show-ddl").fancybox({
            'caption': function(){return ''},
            'onComplete': function(instance, slide){
                $dllDialog.find('h4').text(slide.opts.$orig.attr('data-name'));
                $dllDialog.block({
                    'zindex': 120000
                });
                $.ajax({
                    'type': 'GET',
                    'url': system.url('cms/tools/db-compare/ddl'),
                    'data': {
                        'name': slide.opts.$orig.attr('data-name'),
                        'db': slide.opts.$orig.attr('data-db')
                    },
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        $dllDialog.unblock();
                        if(resp.status){
                            var $tbody = $dllDialog.find('.list-table tbody');
                            $tbody.html('');
                            $.each(resp.data.fields, function(i, data){
                                $tbody.append(['<tr>',
                                    '<td>', data.Field, '</td>',
                                    '<td>', data.Type, '</td>',
                                    '<td>', data.Null, '</td>',
                                    '<td>', data.Key, '</td>',
                                    '<td>', data.Default === null ? 'NULL' : (data.Default === '' ? 'Empty String' : data.Default), '</td>',
                                    '<td>', data.Comment, '</td>',
                                '</tr>'].join(''));
                            });
                            var $dllCode = $('#ddl-code');
                            $dllCode.val(resp.data.ddl);
                            autosize.update($dllCode);
                            $tbody.find('tr:even').addClass('alternate');
                        }
                    }
                });
            },
            'onClosed':function(){
                $dllDialog.unblock();
            }
        });
    });
});
</script>