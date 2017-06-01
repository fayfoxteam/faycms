<?php
use apidoc\models\tables\ApisTable;
?>
<div class="box" id="box-http-method" data-name="http_method">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>HTTP请求方式</h4>
    </div>
    <div class="box-content"><?php
        $http_methods = ApisTable::getHttpMethods();
        foreach($http_methods as $k => $m){
            echo F::form()->inputRadio('http_method', $k, array(
                'wrapper'=>array(
                    'tag'=>'label',
                    'append'=>$m,
                    'wrapper'=>'p'
                )
            ), $k == ApisTable::HTTP_METHOD_GET);
        }
    ?></div>
</div>