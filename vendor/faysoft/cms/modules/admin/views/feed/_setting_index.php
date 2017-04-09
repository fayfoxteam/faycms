<?php echo F::form('setting')->open(array('cms/admin/system/setting'))?>
    <?php echo F::form('setting')->inputHidden('_key')?>
    <div class="form-field">
        <label class="title bold">显示下列项目</label>
        <?php
        echo F::form('setting')->inputCheckbox('cols[]', 'id', array(
            'label'=>'动态ID',
        ));
        if(in_array('tags', $enabled_boxes)){
            //若标签的box被移除，则不显示该列
            echo F::form('setting')->inputCheckbox('cols[]', 'tags', array(
                'label'=>'标签',
            ));
        }
        echo F::form('setting')->inputCheckbox('cols[]', 'status', array(
            'label'=>'状态',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'user', array(
            'label'=>'作者',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'comments', array(
            'label'=>'评论数',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'real_comments', array(
            'label'=>'真实评论数',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'likes', array(
            'label'=>'点赞数',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'real_likes', array(
            'label'=>'真实点赞数',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'publish_time', array(
            'label'=>'发布时间',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'update_time', array(
            'label'=>'更新时间',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'create_time', array(
            'label'=>'创建时间',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'sort', array(
            'label'=>'排序',
        ));
        ?>
    </div>
    <div class="form-field">
        <label class="title bold">显示作者</label>
        <?php
        echo F::form('setting')->inputRadio('display_name', 'username', array(
            'label'=>'用户名',
        ), true);
        echo F::form('setting')->inputRadio('display_name', 'nickname', array(
            'label'=>'昵称',
        ));
        echo F::form('setting')->inputRadio('display_name', 'realname', array(
            'label'=>'真名',
        ));
        ?>
    </div>
    <div class="form-field">
        <label class="title bold">显示时间</label>
        <?php
        echo F::form('setting')->inputRadio('display_time', 'short', array(
            'label'=>'简化时间',
        ), true);
        echo F::form('setting')->inputRadio('display_time', 'full', array(
            'label'=>'完整时间',
        ));
        ?>
    </div>
    <div class="form-field">
        <label class="title bold">分页大小</label>
        <?php echo F::form('setting')->inputNumber('page_size', array(
            'class'=>'form-control w50',
            'min'=>1,
            'max'=>999,
        ))?>
    </div>
    <div class="form-field">
        <?php echo F::form('setting')->submitLink('提交', array(
            'class'=>'btn btn-sm',
        ))?>
    </div>
<?php echo F::form('setting')->close()?>