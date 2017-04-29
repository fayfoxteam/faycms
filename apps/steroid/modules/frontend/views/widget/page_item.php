<?php
/**
 * @var $page
 */
\F::app()->layout->assign(array(
    'title'=>$page['title'],
    'subtitle'=>isset($page['abstract']) ? $page['abstract'] : '',
    'header_bg'=>!empty($page['thumbnail']['id']) ? $page['thumbnail']['url'] : '',
));
?>
<main class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="post-content">
                <?php echo $page['content']?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 center">
            <div class="separator-container">
                <div class="separator"></div>
            </div>
            <a href="<?php echo $this->url()?>#section-contact" class="btn btn-blue">CONTACT US</a>
        </div>
    </div>
    <div class="row">
        <nav class="cf post-nav"></nav>
    </div>
</main>