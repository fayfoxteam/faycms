<section class="section" id="section-product-link">
    <div class="bg" style="background-image:url(<?php echo \cms\services\file\FileService::getUrl($widget->config['file_id'])?>)">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="title-group">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="product-link-container">
                        <a href="<?php echo $this->url('post')?>" class="btn btn-transparent">PRODUCT LIST</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>