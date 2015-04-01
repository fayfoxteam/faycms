<?php 
 use fay\models\Option;
 ?>
 
 <footer>
        <div class="footer_navigation">
            <div class="container_12">
                <div class="grid_3">
                     <h3>联系我们</h3>
                    <ul class="f_contact">
                        <li><?php echo Option::get('address')?></li>
                        <li><?php echo Option::get('phone')?></li>
                        <li><?php echo Option::get('email')?></li>
                    </ul><!-- .f_contact -->
                </div><!-- .grid_3 -->

                <div class="grid_3">
                    <h3>公司信息</h3>
                    <nav class="f_menu">
                        <ul>
                            <li><a href="#">关于我们</a></li>
                            <li><a href="#">关于产品</a></li>
                            <li><a href="#">公司地址</a></li>
                            <li><a href="#">支付方式</a></li>
                        </ul>
                    </nav><!-- .private -->
                </div><!-- .grid_3 -->

                <div class="grid_3">
                    <h3>顾客服务</h3>
                    <nav class="f_menu">
                        <ul>
                            <li><a href="#">联系方式</a></li>
                            <li><a href="#">反馈</a></li>
                            <li><a href="#">Faq</a></li>
                            <li><a href="#">网站地图</a></li>
                        </ul>
                    </nav><!-- .private -->
                </div><!-- .grid_3 -->

                <div class="grid_3">
                    <h3>反馈建议</h3>
                    <nav class="f_menu">
                        <ul>
                            <li><a href="#">联系我们</a></li>
                            <li><a href="#">反馈历史</a></li>
                            <li><a href="#">期望列表</a></li>
                            <li><a href="#">新闻中心</a></li>
                        </ul>
                    </nav><!-- .private -->
                </div><!-- .grid_3 -->

 
            </div><!-- .container_12 -->
        </div><!-- .footer_navigation -->

        <div class="footer_info">
            <div class="container_12">
                <div class="grid_6">
                    <p class="copyright">© Designed by <a href="http://whis.fayfox.com/" target="_blank" title="whis">whis...</a></p>
                </div><!-- .grid_6 -->

                <div class="grid_6">
                    <div class="soc">
                        <a class="google" href="#"></a>
                        <a class="twitter" href="#"></a>
                        <a class="facebook" href="#"></a>
                    </div><!-- .soc -->
                </div><!-- .grid_6 -->

                <div class="clear"></div>
            </div><!-- .container_12 -->
        </div><!-- .footer_info -->
    </footer>