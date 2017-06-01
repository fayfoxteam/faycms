<?php echo F::form('setting')->open(array('admin/system/setting'))?>
    <?php echo F::form('setting')->inputHidden('_key')?>
    <div class="form-field">
        <label class="title bold">显示下列项目</label>
        <?php
        echo F::form('setting')->inputCheckbox('cols[]', 'router', array(
            'label'=>'路由',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'status', array(
            'label'=>'状态',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'category', array(
            'label'=>'分类',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'http_method', array(
            'label'=>'请求方式',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'need_login', array(
            'label'=>'是否需要登录',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'user', array(
            'label'=>'作者',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'since', array(
            'label'=>'自从',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'update_time', array(
            'label'=>'更新时间',
        ));
        echo F::form('setting')->inputCheckbox('cols[]', 'create_time', array(
            'label'=>'创建时间',
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