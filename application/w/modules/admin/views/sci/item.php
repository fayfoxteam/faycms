<div class="row">
	<div class="col-12">
        <table class="list-table">
            <thead>
            <tr>
                <th>基本信息</th>
                <th></th>
            </tr>
            </thead>
            <tfoot>
                <tr>

                </tr>
            </tfoot>
            <tbody>
            <tr>
                <td>ID</td>
                <td><?php echo $data['id'];?></td>
            </tr>
            <tr>
                <td>期刊名字</td>
                <td><?php echo $data['name'];?></td>
            </tr>
            <tr>
                <td>期刊简称</td>
                <td><?php echo $data['short_name'];?></td>
            </tr>
            <tr>
                <td>是否有效</td>
                <td><?php echo (trim($data['status']) == 'line-through') ? '是' : '否';?></td>
            </tr>
            <tr>
                <td>备注信息</td>
                <td><?php echo $data['remark'];?></td>
            </tr>
            <tr>
                <td>期刊ISSN</td>
                <td><?php echo $data['issn_id'];?></td>
            </tr>
            <tr>
                <td>2014-2015最新影响因子</td>
                <td><?php echo $data['factor'];?></td>
            </tr>
            <tr>
                <td>期刊官方网站</td>
                <td><?php echo $data['office_site'];?></td>
            </tr>
            <tr>
                <td>期刊投稿网址</td>
                <td><?php echo $data['submit_site'];?></td>
            </tr>
            <tr>
                <td>通讯方式</td>
                <td><?php echo $data['communication'];?></td>
            </tr>
            <tr>
                <td>涉及的研究方向</td>
                <td><?php echo $data['research_dir'];?></td>
            </tr>
            <tr>
                <td>出版国家</td>
                <td><?php echo $data['country'];?></td>
            </tr>
            <tr>
                <td>出版周期</td>
                <td><?php echo $data['cycle'];?></td>
            </tr>
            <tr>
                <td>出版年份</td>
                <td><?php echo $data['years'];?></td>
            </tr>
            <tr>
                <td>年文章数</td>
                <td><?php echo $data['article_num'];?></td>
            </tr>
            <tr>
                <td>中科院SCI期刊分区（ 大学学科）</td>
                <td>
                    <?php
                    if($data['university1']){
                        echo $data['university1'].':'. $data['university1_1'];
                    }?>
                </td>
            </tr>
            <tr>
                <td>中科院SCI期刊分区（ 小类学科）</td>
                <td>
                    <?php
                    if($data['primary1']){
                        ?>
                        <div class="clear">
                            <div class="fl">
                                <?php  echo $data['primary1'];?>
                            </div>
                            <div class="fr">
                                <?php  echo $data['primary1_1'];?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    if($data['primary2']){
                        ?>
                        <div class="clear">
                            <div class="fl">
                                <?php  echo $data['primary2'];?>
                            </div>
                            <div class="fr">
                                <?php  echo $data['primary2_1'];?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <?php
                    if($data['primary3']){
                        ?>
                        <div class="clear">
                            <div class="fl">
                                <?php  echo $data['primary3'];?>
                            </div>
                            <div class="fr">
                                <?php  echo $data['primary3_1'];?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <?php
                    if($data['primary4']){
                        ?>
                        <div class="clear">
                            <div class="fl">
                                <?php  echo $data['primary4'];?>
                            </div>
                            <div class="fr">
                                <?php  echo $data['primary4_1'];?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>（ Top期刊）</td>
                <td><?php  echo $data['top'];?></td>
            </tr>
            <tr>
                <td>PubMed Central (PMC)链接</td>
                <td><?php  echo $data['pmc'];?></td>
            </tr>
            <tr>
                <td>平均审稿速度（网友分享经验）</td>
                <td><?php  echo $data['audit_speed'];?></td>
            </tr>
            <tr>
                <td>平均录用比例（网友分享经验）</td>
                <td><?php  echo $data['hiring_ratio'];?></td>
            </tr>
            </tbody>
        </table>
	</div>
</div>