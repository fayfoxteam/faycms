<div class="box" id="box-need-login" data-name="need_login">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>是否需要登录</h4>
    </div>
    <div class="box-content"><?php
        echo F::form()->inputRadio('need_login', 1, array(
            'wrapper'=>array(
                'tag'=>'label',
                'append'=>'是',
                'wrapper'=>'p'
            )
        ));
        echo F::form()->inputRadio('need_login', 0, array(
            'wrapper'=>array(
                'tag'=>'label',
                'append'=>'否',
                'wrapper'=>'p'
            )
        ), true);
    ?></div>
</div>