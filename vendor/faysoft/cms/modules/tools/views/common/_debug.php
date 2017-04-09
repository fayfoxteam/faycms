<?php
/**
 * 开发模式或debug模式下，出现在页面底部的debug数据
 */
use fay\helpers\BacktraceHelper;
use fay\helpers\StringHelper;
use fay\helpers\SqlHelper;
use fay\helpers\HtmlHelper;
use fay\core\Db;

if(Db::hasInstance()){
    $db = Db::getInstance();;
}else{
    $db = null;
}
?>
<style>
/* tab切换 */
#debug-container{clear:both;font-size:13px;line-height:20px}
#debug-container .tabbable .nav-tabs{zoom:1;clear:left;padding:0;margin:0} 
#debug-container .tabbable .nav-tabs:before, .tabbable .nav-tabs:after{content:"";display:table}
#debug-container .tabbable .nav-tabs:after{clear: both}
#debug-container .tabbable .nav-tabs li{display:block;float:left;margin-right:4px;margin-bottom:-1px;padding-top:1px;background-color:#fff;border:1px solid #ddd}
#debug-container .tabbable .nav-tabs li a{display:block;padding:3px 18px;transition:none;color:#aaa;line-height:1.5}
#debug-container .tabbable .nav-tabs li.active{font-weight:400;padding-top:0;border-bottom:0;border-top:0}
#debug-container .tabbable .nav-tabs li.active a{background-color:#fff;border-top:3px solid #D12610;color:#000}
#debug-container .tabbable .tab-content{background-color:#fff;border:1px solid #ddd;padding:10px;box-shadow:0 1px 1px rgba(0, 0, 0, 0.04)}
#debug-container .tabbable .tab-pane{position:relative}

/* 常见的普通表格式样 */
#debug-container .debug-table{width:100%;border-spacing:0;border-color:#F0F0EE;border-style:solid;border-width:1px 1px 0 0}
#debug-container .debug-table td,#debug-container .debug-table th{padding:8px;border-color:#F0F0EE;border-style:solid;border-width:0 0 1px 1px;font-size:13px}
#debug-container .debug-table th{text-align:left}
#debug-container .debug-table td.time{white-space:nowrap}

/* 字体颜色 */
#debug-container .fc-red{color:red !important}
#debug-container .fc-green{color:green !important}
#debug-container .fc-orange{color:orange !important}
#debug-container .fc-blue{color:#21759B !important}
#debug-container .fc-grey{color:grey !important}

/* debug Backtrace */
#debug-container .trace-table{width:100%}
#debug-container .trace-table th{text-align:left;padding:6px}
#debug-container .trace-table th, .trace-table td{padding-left:12px}

#debug-container .p5{padding:5px}
</style>
<div id="debug-container">
    <div class="tabbable">
        <ul class="nav-tabs">
            <li class="active"><a href="#debug-tab-1">Sql Log</a></li>
            <li><a href="#debug-tab-2">Backtrace</a></li>
            <li><a href="#debug-tab-3">Runtimes</a></li>
        </ul>
        <div class="tab-content">
            <div id="debug-tab-1" class="tab-pane p5">
                <div class="p5">
                    数据库操作:<?php echo $db ? $db->getCount() : 0?>次
                    |
                    内存使用:<?php echo round(memory_get_usage()/1024, 2)?>KB
                    |
                    执行时间:<?php echo StringHelper::money((microtime(true) - START) * 1000)?>ms
                </div>
                <table class="debug-table">
                <?php 
                    $total_db_time = 0;
                    $sqls = $db ? $db->getSqlLogs() : array();
                    foreach($sqls as $k=>$s){
                        $total_db_time += $s[2]?>
                    <tr>
                        <td><?php echo $k+1?></td>
                        <td><?php echo SqlHelper::nice(HtmlHelper::encode($s[0]), $s[1])?></td>
                        <td class="time"><?php echo StringHelper::money($s[2] * 1000)?>ms</td>
                    </tr>
                <?php }?>
                    <tr>
                        <td colspan="2" align="center">数据库耗时</td>
                        <td><?php echo StringHelper::money($total_db_time * 1000)?>ms</td>
                    </tr>
                </table>
            </div>
            <div id="debug-tab-2" class="tab-pane p5 hide" style="display:none;">
                <?php BacktraceHelper::render()?>
            </div>
            <div id="debug-tab-3" class="tab-pane p5 hide" style="display:none;">
                <?php \fay\helpers\RuntimeHelper::render();?>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('#debug-container').on('click', '.nav-tabs li', function(){
        $(this).addClass('active').siblings().removeClass('active');
        $($(this).find('a').attr('href')).show().siblings().hide();
        return false;
    });
});
</script>