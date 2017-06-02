<?php
/**
 * @var $listview \fay\common\ListView
 * @var $iplocation IpLocation
 */
?>
<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array(
            'class'=>'form-inline',
        ))?>
            <div class="mb5">
                访问页面
                <?php
                    echo F::form('search')->inputText('url', array(
                        'class'=>'form-control w200',
                        'after'=>' | ',
                    ));
                    
                    $spiders = F::app()->config->get('*', 'spiders');
                    asort($spiders);
                    $options = array(''=>'--搜索引擎--');
                    foreach($spiders as $s){
                        $options[$s] = $s;
                    }
                    echo F::form('search')->select('spider', $options, array(
                        'class'=>'form-control',
                    ));
                ?>
            </div>
            <div>
                访问时间
                <?php echo F::form('search')->inputText('start_time', array(
                    'data-rule'=>'datetime',
                    'data-label'=>'时间',
                    'class'=>'form-control datetimepicker',
                ));?>
                -
                <?php echo F::form('search')->inputText('end_time', array(
                    'data-rule'=>'datetime',
                    'data-label'=>'时间',
                    'class'=>'form-control datetimepicker',
                ));?>
                <?php echo F::form('search')->submitLink('查询', array(
                    'class'=>'btn btn-sm',
                ))?>
            </div>
        <?php echo F::form('search')->close()?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?php $listview->showPager();?>
        <table class="list-table">
            <thead>
                <tr>
                    <th>搜索引擎</th>
                    <th>访问地址</th>
                    <th>user agent</th>
                    <th>来源城市</th>
                    <th>访问时间</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>搜索引擎</th>
                    <th>访问地址</th>
                    <th>user agent</th>
                    <th>来源城市</th>
                    <th>访问时间</th>
                </tr>
            </tfoot>
            <tbody>
            <?php $listview->showData(array(
                'iplocation'=>$iplocation
            ));?>
            </tbody>
        </table>
        <?php $listview->showPager();?>
    </div>
</div>