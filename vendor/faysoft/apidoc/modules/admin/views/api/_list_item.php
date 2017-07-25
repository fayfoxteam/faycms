<?php
use apidoc\helpers\ApiHelper;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $data array
 * @var $http_methods array
 */
?>
<tr valign="top">
    <td>
        <strong><?php echo HtmlHelper::encode($data['title'])?></strong>
        <div class="row-actions separate-actions"><?php
            echo HtmlHelper::link('编辑', array('apidoc/admin/api/edit', array(
                'id'=>$data['id'],
            )), array(), true),
            HtmlHelper::link('永久删除', array('apidoc/admin/api/remove', array(
                'id'=>$data['id'],
            )), array(
                'class'=>'fc-red remove-link',
            ), true);
        ?></div>
    </td>
    <?php if(in_array('router', $cols)){?>
        <td><?php echo HtmlHelper::encode($data['router'])?></td>
    <?php }?>
    <?php if(in_array('status', $cols)){?>
        <td><?php echo ApiHelper::getStatus($data['status'])?></td>
    <?php }?>
    <?php if(in_array('app', $cols)){?>
        <td><?php echo HtmlHelper::encode($data['app_name'])?></td>
    <?php }?>
    <?php if(in_array('category', $cols)){?>
        <td><?php echo HtmlHelper::encode($data['cat_title'])?></td>
    <?php }?>
    <?php if(in_array('http_method', $cols)){?>
        <td><?php echo $http_methods[$data['http_method']]?></td>
    <?php }?>
    <?php if(in_array('need_login', $cols)){?>
        <td><?php echo $data['need_login'] ? '是' : '否'?></td>
    <?php }?>
    <?php if(in_array('user', $cols)){?>
        <td><?php
            echo HtmlHelper::link($data[F::form('setting')->getData('display_name', 'nickname')], array(
                'apidoc/admin/api/index', array(
                    'keywords_field'=>'user_id',
                    'keywords'=>$data['user_id'],
                ),
            ));
        ?></td>
    <?php }?>
    <?php if(in_array('since', $cols)){?>
        <td><?php echo HtmlHelper::encode($data['since'])?></td>
    <?php }?>
    <?php if(in_array('create_time', $cols)){?>
        <td>
            <abbr class="time" title="<?php echo DateHelper::format($data['create_time'])?>">
                <?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
                    echo DateHelper::niceShort($data['create_time']);
                }else{
                    echo DateHelper::format($data['create_time']);
                }?>
            </abbr>
        </td>
    <?php }?>
    <?php if(in_array('update_time', $cols)){?>
        <td>
            <abbr class="time" title="<?php echo DateHelper::format($data['update_time'])?>">
                <?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
                    echo DateHelper::niceShort($data['update_time']);
                }else{
                    echo DateHelper::format($data['update_time']);
                }?>
            </abbr>
        </td>
    <?php }?>
</tr>