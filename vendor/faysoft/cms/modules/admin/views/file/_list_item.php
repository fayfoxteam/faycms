<?php
use cms\services\CategoryService;
use cms\services\file\FileService;
use cms\services\file\QiniuService;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $data array
 * @var $display_name string
 */

$full_file_path = FileService::getUrl($data);
?>
<tr valign="top" id="file-<?php echo $data['id']?>" data-qiniu="<?php echo $data['qiniu']?>">
    <td><?php echo HtmlHelper::inputCheckbox('ids[]', $data['id'], false, array(
        'class'=>'batch-ids',
    ));?></td>
    <td class="center">
    <?php if($data['is_image']){?>
        <?php echo HtmlHelper::link(HtmlHelper::img($data['id'], FileService::PIC_THUMBNAIL, array(
            'width'=>60,
            'height'=>60,
        )), $full_file_path, array(
            'encode'=>false,
            'title'=>$data['client_name'],
            'class'=>'mask ib',
            'data-fancybox'=>'images',
            'data-caption'=>$data['client_name'] .
                HtmlHelper::encode(HtmlHelper::link('<i class="fa fa-download ml5"></i>下载', array('cms/admin/file/download', array(
                    'id'=>$data['id'],
                )), array(
                    'encode'=>false,
                    'title'=>false,
                ), true)),
        ))?>
    <?php }else{?>
        <img src="<?php echo FileService::getThumbnailUrl($data)?>" width="60" height="60" />
    <?php }?>
    </td>
    <td>
        <strong>
            <?php echo HtmlHelper::link($data['client_name'], $full_file_path, array(
                'class'=>'row-title',
                'data-fancybox'=>null,
                'data-type'=>'image',
            ))?>
        </strong>
        <div class="row-actions separate-actions">
        <?php
            if($data['is_image'] == 1){
                echo HtmlHelper::link('查看', $full_file_path, array(
                    'class'=>'file-image',
                    'target'=>'_blank',
                ));
            }
            echo HtmlHelper::link('物理删除', array('cms/admin/file/remove', array(
                'id'=>$data['id'],
            )), array(
                'class'=>'delete-file fc-red',
                'data-id'=>$data['id'],
            ));
            echo HtmlHelper::link('下载', array('cms/admin/file/download', array(
                'id'=>$data['id'],
            )), array(
                'class'=>'download-file',
            ), true);
        ?>
        </div>
    </td>
    <?php if(in_array('qiniu', $cols)){?>
    <td>
        <div class="qiniu-status qiniu-uploaded <?php if(!$data['qiniu']){echo 'hide';}?>">
            <span class="fc-green">已上传</span>
            <div class="row-actions separate-actions"><?php
                echo HtmlHelper::link('查看', QiniuService::service()->getUrl($data), array(
                    'target'=>'_blank',
                    'class'=>'show-qiniu-file',
                ));
                echo HtmlHelper::link('删除', array('cms/admin/qiniu/delete', array(
                    'id'=>$data['id'],
                )), array(
                    'data-id'=>$data['id'],
                    'class'=>'qiniu-delete fc-red',
                    'title'=>'从七牛删除，本地图片会保留',
                ), true);
            ?></div>
        </div>
        <div class="qiniu-status qiniu-not-upload <?php if($data['qiniu']){echo 'hide';}?>">
            <span class="fc-orange">未上传</span>
            <div class="row-actions separate-actions"><?php
                echo HtmlHelper::link('上传', array('cms/admin/qiniu/put', array(
                    'id'=>$data['id'],
                )), array(
                    'data-id'=>$data['id'],
                    'class'=>'qiniu-put',
                ), true);
            ?></div>
        </div>
        <div class="loading hide">
            <img src="<?php echo $this->assets('images/throbber.gif')?>" />操作中...
        </div>
    </td>
    <?php }?>
    <?php if(in_array('file_type', $cols)){?>
    <td><?php echo $data['file_type'] ? $data['file_type'] : '未知'?></td>
    <?php }?>
    <?php if(in_array('file_path', $cols)){?>
    <td><?php echo $data['file_path'], $data['raw_name'], $data['file_ext']?></td>
    <?php }?>
    <?php if(in_array('file_size', $cols)){?>
    <td><?php echo number_format($data['file_size']/1024, 2, '.', ',')?>KB</td>
    <?php }?>
    <?php if(in_array('user', $cols)){?>
    <td><?php echo $data[$display_name]?></td>
    <?php }?>
    <?php if(in_array('cat', $cols)){?>
    <td>
        <?php
         $cat = CategoryService::service()->get($data['cat_id'],'title');
        echo $cat['title'];
        ?>
    </td>
    <?php }?>
    <?php if(in_array('downloads', $cols)){?>
    <td><?php echo $data['downloads']?></td>
    <?php }?>
    <?php if(in_array('upload_time', $cols)){?>
    <td><abbr class="time" title="<?php echo DateHelper::format($data['upload_time'])?>">
        <?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
            echo DateHelper::niceShort($data['upload_time']);
        }else{
            echo DateHelper::format($data['upload_time']);
        }?>
    </abbr></td>
    <?php }?>
</tr>