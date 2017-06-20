<?php
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<?php if(!empty($canonical)){?>
    <link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<title><?php if(!empty($title)){
    echo $title;
}?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-3.2.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
    system.base_url = '<?php echo $this->url()?>';
    system.user_id = '<?php echo \F::app()->current_user?>';
</script>
<style>
    h1{color:#231915;font-size:20px;text-align:center;margin:30px 0 20px}
    .inbox-table{border-color:#008CD6;border-width:1px 1px 0 0;border-style:solid;width:100%}
    .inbox-table td,.inbox-table th{padding:8px 8px;border-color:#008CD6;border-style:solid;border-width:0 0 1px 1px}
    .inbox-table th{color:#008CD6}
    .wrapper{padding:0 20px 0 20px}
    .ellipsis{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .cat{width:82px}
    .status{width:32px}
</style>
</head>
<body>
<h1>读三国·借智慧</h1>
<div class="wrapper">
    <table class="inbox-table">
        <thead>
            <tr>
                <th>分类</th>
                <th>内容</th>
                <th>状态</th>
            </tr>
        </thead>
        <tbody>
        <?php $show_status = true?>
        <?php foreach($posts as $post){?>
            <tr>
                <td><div class="cat ellipsis"><?php echo HtmlHelper::encode($post['cat_title'])?></div></td>
                <td><div class="title ellipsis"><?php
                    if($post['read_date'] == date('Y-m-d')){
                        //一天内只能读一篇
                        echo HtmlHelper::link($post['title'], array('post/item2', array('id'=>$post['id'])));
                        $show_status = false;
                    }else if($show_status && !$post['read_id']){
                        echo HtmlHelper::link($post['title'], array('post/item2', array('id'=>$post['id'])));
                    }else{
                        echo HtmlHelper::encode($post['title']);
                    }
                ?></div></td>
                <td><div class="status"><?php
                    if($post['read_id']){
                        echo '<span style="color:#C7000C">已读</span>';
                    }else if($show_status){
                        echo '未读';
                        $show_status = false;
                    }
                ?></div></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
</div>
<script>
    $('.inbox-table').find('.title').css({'width': (parseInt(document.documentElement.clientWidth) - 40 - 85 - 32 - 50)+'px'})
</script>
</body>
</html>