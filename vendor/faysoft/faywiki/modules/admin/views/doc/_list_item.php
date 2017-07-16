<?php
use cms\helpers\PostHelper;
use cms\services\file\FileService;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $data array
 */
?>
<tr valign="top" id="post-<?php echo $data['id']?>">
    <td><?php echo HtmlHelper::inputCheckbox('ids[]', $data['id'], false, array(
        'class'=>'batch-ids',
    ));?></td>
    <?php if(in_array('id', $cols)){?>
    <td><?php echo $data['id']?></td>
    <?php }?>
    <?php if(in_array('thumbnail', $cols)){?>
    <td class="center"><?php
        if($data['thumbnail']){
            echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_THUMBNAIL, array(
                'width'=>60,
                'height'=>60,
                'spare'=>'default',
            )), FileService::getUrl($data['thumbnail']), array(
                'encode'=>false,
                'class'=>'mask ib',
                'title'=>HtmlHelper::encode($data['title']),
                'data-fancybox'=>'images',
                'data-caption'=>HtmlHelper::encode(HtmlHelper::encode($data['title'])) .
                    HtmlHelper::encode(HtmlHelper::link('<i class="fa fa-edit ml5"></i>编辑', array('faywiki/admin/doc/edit', array(
                        'id'=>$data['id'],
                    )), array(
                        'encode'=>false,
                        'title'=>false,
                    ), true)),
            ));
        }else{
            echo HtmlHelper::img($data['thumbnail'], FileService::PIC_THUMBNAIL, array(
                'width'=>60,
                'height'=>60,
                'spare'=>'default',
                'class'=>'block',
            ));
        }
    ?></td>
    <?php }?>
    <td>
        <strong><?php
            if(!$data['delete_time']){
                echo HtmlHelper::link($data['title'] ? $data['title'] : '--无标题--', array('faywiki/admin/doc/edit', array(
                    'id'=>$data['id'],
                )));
            }else{
                echo HtmlHelper::link($data['title'] ? $data['title'] : '--无标题--', 'javascript:');
            }
        ?></strong>
        <div class="row-actions separate-actions">
        <?php
            if($data['delete_time'] == 0){
                echo HtmlHelper::link('编辑', array('faywiki/admin/doc/edit', array(
                    'id'=>$data['id'],
                )), array(), true);
                echo HtmlHelper::link('移入回收站', array('faywiki/admin/doc/delete', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'fc-red',
                ), true);
            }else{
                echo HtmlHelper::link('还原', array('faywiki/admin/doc/undelete', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'undelete-post',
                ), true);
                echo HtmlHelper::link('永久删除', array('faywiki/admin/doc/remove', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'delete-post fc-red remove-link',
                ), true);
            }
        ?>
        </div>
    </td>
    <?php if(in_array('category', $cols)){?>
    <td><?php echo HtmlHelper::link($data['cat_title'], array('faywiki/admin/doc/index', array(
        'cat_id'=>$data['cat_id'],
    )));?></td>
    <?php }?>
    <?php if(in_array('status', $cols)){?>
    <td><?php echo PostHelper::getStatus($data['status'], $data['delete_time']);?></td>
    <?php }?>
    <?php if(in_array('user', $cols)){?>
    <td><?php
        echo HtmlHelper::link($data[F::form('setting')->getData('display_name', 'username')], array(
            'faywiki/admin/doc/index', array(
                'keywords_field'=>'p.user_id',
                'keywords'=>$data['user_id'],
            ),
        ));
    ?></td>
    <?php }?>
    <?php if(in_array('views', $cols)){?>
    <td><?php echo $data['views']?></td>
    <?php }?>
    <?php if(in_array('real_views', $cols)){?>
    <td><?php echo $data['real_views']?></td>
    <?php }?>
    <?php if(in_array('likes', $cols)){?>
    <td><?php echo $data['likes']?></td>
    <?php }?>
    <?php if(in_array('real_likes', $cols)){?>
    <td><?php echo $data['real_likes']?></td>
    <?php }?>
    <?php if(in_array('last_view_time', $cols)){?>
    <td>
        <abbr class="time" title="<?php echo DateHelper::format($data['last_view_time'])?>">
            <?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
                echo DateHelper::niceShort($data['last_view_time']);
            }else{
                echo DateHelper::format($data['last_view_time']);
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
</tr>