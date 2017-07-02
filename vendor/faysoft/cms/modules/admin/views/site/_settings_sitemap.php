<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

?>
<form id="sitemap-form" class="ajax-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-6">
            <div class="form-field">
                <h4>通用</h4>
            </div>
            <div class="form-field">
                <label class="title">展示文章数</label>
                <?php echo HtmlHelper::inputNumber('system:sitemap:post_count', OptionService::get('system:sitemap:post_count', 500), array(
                    'class'=>'form-control mw200',
                    'data-required'=>'required',
                    'data-rule'=>'int',
                    'data-label'=>'展示文章数',
                ))?>
            </div>
            
            <div class="form-field">
                <h4>首页</h4>
            </div>
            <div class="form-field">
                <label class="title">&lt;changefreq&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:index:changefreq',
                    array(
                        ''=>'None',
                        'always'=>'Always',
                        'hourly'=>'Hourly',
                        'daily'=>'Daily',
                        'weekly'=>'Weekly',
                        'monthly'=>'Monthly',
                        'yearly'=>'Yearly',
                        'never'=>'Never',
                    ),
                    OptionService::get('system:sitemap:index:changefreq', 'always'),
                    array(
                        'class'=>'form-control mw200',
                    )
                )?>
                <p class="fc-grey">页面内容更新频率。</p>
            </div>
            <div class="form-field">
                <label class="title">&lt;priority&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:index:priority',
                    array(
                        ''=>'None',
                        '0'=>'0',
                        '0.1'=>'0.1',
                        '0.2'=>'0.2',
                        '0.3'=>'0.3',
                        '0.4'=>'0.4',
                        '0.5'=>'0.5',
                        '0.6'=>'0.6',
                        '0.7'=>'0.7',
                        '0.8'=>'0.8',
                        '0.9'=>'0.9',
                        '1.0'=>'1.0',
                    ),
                    OptionService::get('system:sitemap:index:priority', ''),
                    array(
                        'class'=>'form-control mw100',
                    )
                )?>
                <p class="fc-grey">是用来指定此链接相对于其他链接的优先权比值，此值定于0.0 - 1.0之间。</p>
            </div>
            <div class="form-field">
                <label class="title">&lt;lastmod&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:index:lastmod',
                    array(
                        'last_post'=>'最新已发布文章更新时间',
                        'now'=>'实时',
                    ),
                    OptionService::get('system:sitemap:index:lastmod', 'now'),
                    array(
                        'class'=>'form-control mw200',
                    )
                )?>
                <p class="fc-grey">最后更新时间。</p>
            </div>
        </div>
        
        <div class="col-6">
            <div class="form-field">
                <h4>分类页</h4>
            </div>
            <div class="form-field">
                <label class="title">&lt;changefreq&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:cat:changefreq',
                    array(
                        ''=>'None',
                        'always'=>'Always',
                        'hourly'=>'Hourly',
                        'daily'=>'Daily',
                        'weekly'=>'Weekly',
                        'monthly'=>'Monthly',
                        'yearly'=>'Yearly',
                        'never'=>'Never',
                    ),
                    OptionService::get('system:sitemap:cat:changefreq', 'hourly'),
                    array(
                        'class'=>'form-control mw200',
                    )
                )?>
                <p class="fc-grey">页面内容更新频率。</p>
            </div>
            <div class="form-field">
                <label class="title">&lt;priority&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:cat:priority',
                    array(
                        ''=>'None',
                        '0'=>'0',
                        '0.1'=>'0.1',
                        '0.2'=>'0.2',
                        '0.3'=>'0.3',
                        '0.4'=>'0.4',
                        '0.5'=>'0.5',
                        '0.6'=>'0.6',
                        '0.7'=>'0.7',
                        '0.8'=>'0.8',
                        '0.9'=>'0.9',
                        '1.0'=>'1.0',
                    ),
                    OptionService::get('system:sitemap:cat:priority', ''),
                    array(
                        'class'=>'form-control mw100',
                    )
                )?>
                <p class="fc-grey">是用来指定此链接相对于其他链接的优先权比值，此值定于0.0 - 1.0之间。</p>
            </div>
            <div class="form-field">
                <label class="title">&lt;lastmod&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:cat:lastmod',
                    array(
                        'last_post'=>'当前分类最新已发布文章更新时间',
                        'now'=>'实时',
                    ),
                    OptionService::get('system:sitemap:cat:lastmod', 'last_post'),
                    array(
                        'class'=>'form-control w240',
                    )
                )?>
                <p class="fc-grey">最后更新时间。</p>
            </div>
            
            <div class="form-field">
                <h4>文章详情页</h4>
            </div>
            <div class="form-field">
                <label class="title">&lt;changefreq&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:post:changefreq',
                    array(
                        ''=>'None',
                        'always'=>'Always',
                        'hourly'=>'Hourly',
                        'daily'=>'Daily',
                        'weekly'=>'Weekly',
                        'monthly'=>'Monthly',
                        'yearly'=>'Yearly',
                        'never'=>'Never',
                    ),
                    OptionService::get('system:sitemap:post:changefreq', ''),
                    array(
                        'class'=>'form-control mw200',
                    )
                )?>
                <p class="fc-grey">页面内容更新频率。</p>
            </div>
            <div class="form-field">
                <label class="title">&lt;priority&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:post:priority',
                    array(
                        ''=>'None',
                        '0'=>'0',
                        '0.1'=>'0.1',
                        '0.2'=>'0.2',
                        '0.3'=>'0.3',
                        '0.4'=>'0.4',
                        '0.5'=>'0.5',
                        '0.6'=>'0.6',
                        '0.7'=>'0.7',
                        '0.8'=>'0.8',
                        '0.9'=>'0.9',
                        '1.0'=>'1.0',
                    ),
                    OptionService::get('system:sitemap:post:priority', ''),
                    array(
                        'class'=>'form-control mw100',
                    )
                )?>
                <p class="fc-grey">是用来指定此链接相对于其他链接的优先权比值，此值定于0.0 - 1.0之间。</p>
            </div>
            <div class="form-field">
                <label class="title">&lt;lastmod&gt;</label>
                <?php echo HtmlHelper::select(
                    'system:sitemap:post:lastmod',
                    array(
                        'update_time'=>'当前文章最后更新时间',
                        'now'=>'实时',
                    ),
                    OptionService::get('system:sitemap:post:lastmod', 'update_time'),
                    array(
                        'class'=>'form-control mw200',
                    )
                )?>
                <p class="fc-grey">最后更新时间。</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <a href="javascript:" id="sitemap-form-submit" class="btn">提交保存</a>
            </div>
        </div>
    </div>
</form>