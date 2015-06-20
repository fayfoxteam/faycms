#属性集
以键值对的方式，存放任意对属性，并通过设定的模板进行渲染。

**模版**

此工具模版为单行模版，例如：模版为
```php
<p><label>{$key}</label>{$value}</p>
```
则渲染结果类似
```html
<p><label>E-mail</label>admin@fayfox.com</p>
<p><label>地址</label>浙江 杭州</p>
<p><label>域名</label>Faycms.com</p>
```