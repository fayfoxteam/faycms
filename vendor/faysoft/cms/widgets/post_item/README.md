#显示一篇文章
根据传入ID或固定显示一篇文章。

- 若根据传入ID显示文章：  
文章不存在会抛出一个`fay\core\HttpException`异常；  
文章存在则会设置`\F::app()->layout->title`，`\F::app()->layout->keywords`，`\F::app()->layout->description`信息。
- 若固定显示一篇文章，所选文章不存在，则返回空；存在也不会去设置`\F::app()->layout`信息。

**模版层可用参数**

- `$post`：包含符合条件的文章相关信息的数组
* 每项post字段包含：`id`, `cat_id`, `title`, `publish_time`, `user_id`, `is_top`, `thumbnail`, `abstract`, `comments`, `views`, `likes`, `format_publish_time`。
  * 若附加字段勾选了分类详情，则还对应包含`cat`字段。`cat`字段包含：`id`, `title`, `alias`
  * 若附加字段勾选了作者信息，则还对应包含`user`字段。`user`字段包含：`id`, `username`, `nickname`, `avatar`
  * 若附加字段勾选了计数，则对应包含`meta`字段。`meta`字段包含：`comments`, `views`, `likes`
  * 若附加字段勾选了附件，则对应包含`files`字段。`files`字段包含：`id`, `url`, `thumbnail`, `is_image`, `description`
  * 若附加字段勾选了属性，则对应包含`props`字段。`props`字段包含：`id`, `title`, `type`, `required`, `element`, `alias`, `options`, `value`
  * 若附加字段勾选了标签，则还对应包含`tags`字段。`tags`字段包含：`id`, `title`
  * 若附加字段勾选了导航，则还对应包含`nav`字段。`nav`字段包含：`id`, `title`
  * 若附加字段勾选了附加分类，则还对应包含`categories`字段。`categories`字段包含：`id`, `title`, `alias`