<?php
use fay\helpers\HtmlHelper;
?>
<div class="team-desc">
    <p class="indent-2em">有多少教科书就有多少种关于团队的解释，这里把团队定义为：
        团队是由员工和管理层组成的一个共同体，
        该共同体合理利用每一个成员的知识和技能协同工作，
        解决问题，达到共同的目标。</p>
    <p class="indent-2em">管理学家斯蒂芬·P·罗宾斯认为：团队就是由两个或者两个以上的，
        相互作用，相互依赖的个体，为了特定目标而按照一定规则结合在一起的组织。</p>
</div>
<ul id="team-list">
<?php $i = 0;
foreach($team_members as $m){
    $i++;?>
    <li class="<?php if($i % 4 == 0)echo 'last';?>">
        <a href="<?php echo $this->url('team/'.$m['id'])?>">
            <?php echo HtmlHelper::img($m['thumbnail'], 4, array(
                'width'=>150,
                'height'=>150,
                'dw'=>150,
                'dh'=>150,
            ))?>
            <p><?php echo $m['title']?></p>
        </a>
    </li>
<?php }?>
</ul>