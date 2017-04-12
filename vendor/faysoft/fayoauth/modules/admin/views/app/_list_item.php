<?php
use fay\helpers\HtmlHelper;
?>
<tr valign="top">
    <td>
        <strong><?php echo HtmlHelper::encode($data['name'])?></strong>
        <div class="row-actions">
            <a href="<?php echo $this->url('fayoauth/admin/app/edit', array('id'=>$data['id']) + F::input()->get())?>">编辑</a>
            <a href="<?php echo $this->url('fayoauth/admin/app/delete', array('id'=>$data['id']) + F::input()->get())?>" class="fc-red remove-link">删除</a>
        </div>
    </td>
    <td><?php echo isset(\fayoauth\models\tables\OauthAppsTable::$codes[$data['code']]) ? \fayoauth\models\tables\OauthAppsTable::$codes[$data['code']] : '其它'?></td>
    <td><?php echo $data['alias']?></td>
    <td><?php
        if($data['enabled']){
            echo '<span class="fc-green">是</span>';
        }else{
            echo '<span class="fc-orange">否</span>';
        }
        ?></td>
</tr>