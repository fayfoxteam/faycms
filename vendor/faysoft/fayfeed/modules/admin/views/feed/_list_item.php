<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
use fayfeed\helpers\FeedHelper;
use fayfeed\services\FeedTagService;

/**
 * @var $data array
 */
?>
<tr valign="top" id="feed-<?php echo $data['id']?>">
    <td><?php echo HtmlHelper::inputCheckbox('ids[]', $data['id'], false, array(
        'class'=>'batch-ids',
    ));?></td>
    <?php if(in_array('id', $cols)){?>
    <td><?php echo $data['id']?></td>
    <?php }?>
    <td>
        <strong><?php
            echo HtmlHelper::link($data['content'], array('fayfeed/admin/feed/edit', array(
                'id'=>$data['id'],
            )));
        ?></strong>
        <div class="row-actions">
        <?php
            if($data['delete_time'] == 0){
                echo HtmlHelper::link('编辑', array('fayfeed/admin/feed/edit', array(
                    'id'=>$data['id'],
                )), array(), true);
                echo HtmlHelper::link('移入回收站', array('fayfeed/admin/feed/delete', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'fc-red',
                ), true);
            }else{
                echo HtmlHelper::link('还原', array('fayfeed/admin/feed/undelete', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'undelete-feed',
                ), true);
                echo HtmlHelper::link('永久删除', array('fayfeed/admin/feed/remove', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'delete-feed fc-red remove-link',
                ), true);
            }
        ?>
        </div>
    </td>
    <?php if(in_array('tags', $cols)){?>
    <td><?php
        $tags = FeedTagService::service()->get($data['id']);
        foreach($tags as $key => $tag){
            if($key){
                echo ', ';
            }
            echo HtmlHelper::link($tag['title'], array('fayfeed/admin/feed/index', array(
                'tag_id'=>$tag['id'],
            )));
        }
    ?></td>
    <?php }?>
    <?php if(in_array('status', $cols)){?>
    <td><?php echo FeedHelper::getStatus($data['status'], $data['delete_time']);?></td>
    <?php }?>
    <?php if(in_array('user', $cols)){?>
    <td><?php
        echo HtmlHelper::link($data[F::form('setting')->getData('display_name', 'nickname')], array(
            'fayfeed/admin/feed/index', array(
                'keywords_field'=>'p.user_id',
                'keywords'=>$data['user_id'],
            ),
        ));
    ?></td>
    <?php }?>
    <?php if(in_array('comments', $cols)){?>
    <td><?php echo $data['comments']?></td>
    <?php }?>
    <?php if(in_array('real_comments', $cols)){?>
    <td><?php echo $data['real_comments']?></td>
    <?php }?>
    <?php if(in_array('likes', $cols)){?>
    <td><?php echo $data['likes']?></td>
    <?php }?>
    <?php if(in_array('real_likes', $cols)){?>
    <td><?php echo $data['real_likes']?></td>
    <?php }?>
    <?php if(in_array('publish_time', $cols)){?>
    <td>
        <abbr class="time" title="<?php echo DateHelper::format($data['publish_time'])?>">
            <?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
                echo DateHelper::niceShort($data['publish_time']);
            }else{
                echo DateHelper::format($data['publish_time']);
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
    <?php if(in_array('sort', $cols)){?>
    <td>
        <abbr class="time" title="<?php echo DateHelper::format($data['sort'])?>">
            <?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
                echo DateHelper::niceShort($data['sort']);
            }else{
                echo DateHelper::format($data['sort']);
            }?>
        </abbr>
    </td>
    <?php }?>
</tr>