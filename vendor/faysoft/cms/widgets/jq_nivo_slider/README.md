#轮播图 - nivo.slider
调用jquery nivo.slider插件显示轮播图效果（适用于固定大小容器中进行轮播的场景）。

**注意**

- 该widget调用了`jquery.nivo.slider.pack.js`，侧边栏参数为插件配置参数，详细可自行查看插件官方文档。
- 该widget使用`$this->appendCss`引入css文件，故layout中必须有`echo $this->getCss()`语句用于输出css文件。
- 如果你看不懂上面两点在说什么，请改用images工具用于保存/编辑图片，自行选择轮播图插件。