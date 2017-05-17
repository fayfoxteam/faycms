<?php
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 * @var $content string
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php if(!empty($canonical)){?>
        <link rel="canonical" href="<?php echo $canonical?>" />
    <?php }?>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta content="<?php if(isset($keywords))echo HtmlHelper::encode($keywords);?>" name="keywords" />
    <meta content="<?php if(isset($description))echo HtmlHelper::encode($description);?>" name="description" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
    <?php echo $this->getCss()?>
    <script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
    <script>
        system.base_url = '<?php echo $this->url()?>';
    </script>
</head>
<body>
<div class="container" style="margin-top:50px">
    <div class="row">
        <div class="col-md-12">
            <form id="form">
                <table>
                    <tr>
                        <th>访客数</th>
                        <td><input type="text" name="visits"></td>
                    </tr>
                    <tr>
                        <th>访客增长比</th>
                        <td><input type="text" name="visit_percent">%</td>
                    </tr>
                    <tr>
                        <th>下单买家增长比</th>
                        <td><input type="text" name="buyer_percent">%</td>
                    </tr>
                    <tr>
                        <th>下单转化率</th>
                        <td><input type="text" name="conversion">%</td>
                    </tr>
                    <tr>
                        <th>转化率增长比</th>
                        <td><input type="text" name="conversion_percent">%</td>
                    </tr>
                    <tr>
                        <th>开始时间</th>
                        <td><input type="text" name="start_time" onfocus="WdatePicker()"></td>
                    </tr>
                    <tr>
                        <th>结束时间</th>
                        <td><input type="text" name="end_time" onfocus="WdatePicker()"></td>
                    </tr>
                    <tr>
                        <th>店铺名称</th>
                        <td><input type="text" name="store_name"></td>
                    </tr>
                    <tr>
                        <th>是否添加水印</th>
                        <td><label><input type="checkbox" name="watermark" value="1" checked="checked">是</label></td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <input type="submit" value="预览">
                            <input type="button" value="下载" id="download-btn">
                        </th>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="row" style="margin-top:30px">
        <div class="col-md-12">
            <img src="<?php echo $this->url('index/pic')?>" id="preview-img">
        </div>
    </div>
</div>
<script src="<?php echo $this->assets('js/My97DatePicker/WdatePicker.js')?>"></script>
<script>
    $('#form').on('submit', function(){
        $('#preview-img').attr('src', '<?php echo $this->url('index/pic')?>?'+$(this).serialize());
        return false;
    });
    
    $('#download-btn').on('click', function(){
        window.location.href = '<?php echo $this->url('index/pic')?>?download=1&'+$('#form').serialize();
    });
</script>
</body>
</html>