<?php
echo F::form()->textarea('description', array(
    'id'=>'wmd-input',
    'class'=>'h350',
    'style'=>'width:49%;',
    'wrapper'=>array(
        'tag'=>'div',
        'id'=>'markdown-container',
    ),
    'after'=>'<div class="clear"></div>',
));