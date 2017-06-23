<?php
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
?>
<tr valign="top" id="user-<?php echo $data['id']?>">
    <td><?php echo HtmlHelper::link(HtmlHelper::img($data['avatar'], FileService::PIC_THUMBNAIL, array(
        'width'=>40,
        'height'=>40,
        'class'=>'circle',
        'spare'=>'avatar',
    )), array('cms/admin/user/item', array(
        'id'=>$data['id'],
    )), array(
        'title'=>false,
        'encode'=>false,
    ))?></td>
    
    <td>
        <strong>
            <?php if($data['nickname'])
                echo HtmlHelper::encode($data['nickname']);
            else
                echo '&nbsp;';?>
        </strong>
        <div class="row-actions">
            <?php
                echo HtmlHelper::link('查看', array('cms/admin/user/item', array(
                    'id'=>$data['id'],
                )), array(), true);
                echo HtmlHelper::link('编辑', array('cms/admin/user/edit', array(
                    'id'=>$data['id'],
                )), array(), true);
            ?>
        </div>
    </td>

    <td><?php echo HtmlHelper::encode($data['mobile'])?></td>
    
    <td><?php echo \fay\helpers\StringHelper::money($user_extra[$data['id']]['military'] / 100)?></td>

    <td><?php echo date('Y年m月d日', $user_extra[$data['id']]['sign_up_time'])?></td>
    
    <td>
        <abbr class="time" title="<?php echo DateHelper::format($data['reg_time'])?>">
            <?php echo DateHelper::niceShort($data['reg_time'])?>
        </abbr>
    </td>
    
    <td>
        <abbr class="time" title="<?php echo DateHelper::format($data['last_login_time'])?>">
            <?php echo DateHelper::niceShort($data['last_login_time'])?>
        </abbr>
    </td>
</tr>