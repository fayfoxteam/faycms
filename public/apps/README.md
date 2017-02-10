注意：因为系统是多application的设计模式，所以该目录下还需再创建一个与application同名的文件夹，把定制application用到的文件放这里
例如一个application叫demo，文档结构示例如下：
```
- demo/css/style.css
- demo/js/custom.js
- demo/images/test.jpg
```
在view层中调用方式如下：
```php
<link type="text/css" rel="stylesheet" href="<?php echo $this->appAssets('css/style.css')?>" />
```