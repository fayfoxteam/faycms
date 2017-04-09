<div class="row">
    <div class="col-12">
        <div class="tabbable">
            <ul class="nav-tabs">
                <li class="active"><a href="#oauth-weixin">微信登录</a></li>
                <li><a href="#oauth-qq">QQ登录</a></li>
            </ul>
            <div class="tab-content">
                <div id="oauth-weixin" class="tab-pane p5">
                    <?php $this->renderPartial('_oauth_weixin')?>
                </div>
                <div id="oauth-qq" class="tab-pane p5 hide">
                    <?php $this->renderPartial('_oauth_qq')?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->renderPartial('_form_js')?>