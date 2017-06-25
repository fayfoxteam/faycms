<?php
/**
 * @var $this \fay\core\View
 * @var $type int
 */
?>
<div style="height:100%;background:url(<?php echo $this->appAssets('images/forum/jianyan-bg.jpg')?>);background-size:100% 100%">
    <header class="page-header">
        <div class="header-content">
            <a href="<?php echo $this->url('bingjian', array(
                'type'=>$type,
            ))?>" class="top-return-link">&lt;</a>
            <span class="header-logo"><img src="<?php echo $this->appAssets('images/forum/logo.png')?>"></span>
            <span class="header-title">兵谏</span>
            <span class="header-subtitle">敢于兵谏乃真勇士！</span>
        </div>
    </header>
    <div class="main-content">
        <form id="message-form" method="post" action="<?php echo $this->url('api/message/create')?>">
            <input type="hidden" name="redirect" value="<?php echo $this->url('bingjian/user', array(
                'type'=>$type,
                'user_id'=>\F::app()->current_user
            ))?>">
            <input type="hidden" name="type" value="<?php echo $type?>">
            <table align="center" width="100%">
                <tr>
                    <th>识&nbsp;别&nbsp;号</th>
                    <td><input type="text" name="mobile"></td>
                </tr>
                <tr>
                    <th>军团代号</th>
                    <td><input type="text" name="daihao"></td>
                </tr>
                <tr>
                    <th>标&nbsp;&nbsp;&nbsp;&nbsp;题</th>
                    <td><input type="text" name="title"></td>
                </tr>
                <tr>
                    <td colspan="2"><textarea name="content" placeholder="东汉至三国时代之战争状态、战斗编成、攻防器械、战斗兵器、兵服饰物、演练方式等战争全要素，不论您有何种研究和心得，抑或何种智谋和兵略，请直言相谏！"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center" style="padding-top:30px"><a href="javascript:" class="btn btn-red" id="message-form-submit">提&nbsp;&nbsp;交</a></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
    $('#message-form').on('submit', function(){
        if($(this).find('[name="mobile"]').val() == ''){
            common.toast('识别号不能为空', 'error');
            return false;
        }else if($(this).find('[name="mobile"]').val() == '<?php echo $user['user']['mobile']?>'){
            $(this).find('[name="daihao"]').val('<?php echo \guangong\helpers\UserHelper::getCode(\F::app()->current_user)?>');
        }else{
            common.toast('识别号错误', 'error');
            return false;
        }
        
        if($(this).find('[name="title"]').val() == ''){
            common.toast('标题不能为空', 'error');
            return false;
        }
        if($(this).find('[name="content"]').val() == ''){
            common.toast('内容不能为空', 'error');
            return false;
        }
    }).on('blur', '[name="mobile"]', function(){
        if($(this).val() == '<?php echo $user['user']['mobile']?>'){
            $('#message-form').find('[name="daihao"]').val('<?php echo \guangong\helpers\UserHelper::getCode(\F::app()->current_user)?>')
        }else{
            common.toast('识别号错误', 'error');
        }
    });
</script>