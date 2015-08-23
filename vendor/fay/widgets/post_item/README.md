#显示一篇文章
根据传入ID或固定显示一篇文章。

- 若根据传入ID显示文章：  
文章不存在会抛出一个`fay\core\HttpException`异常；  
文章存在则会设置`\F::app()->layout->title`，`\F::app()->layout->keywords`，`\F::app()->layout->description`信息。
- 若固定显示一篇文章，所选文章不存在，则返回空；存在也不会去设置`\F::app()->layout`信息。

**模版层可用参数**

- `$alias`：该小工具实例的别名
- `$config`：本页面的配置信息
- `$post`：符合条件的文章数组