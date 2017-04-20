# 指定文章列表
后台选择一些文章列表显示
> 该工具会自动排除未发布状态文章（草稿状态，已删除，发布时间小于当前时间或不在工具内设置的有效期内）

**模版层可用参数**

- `$alias`：该小工具实例的别名
- `$config`：本页面的配置信息
- `$posts`：符合条件的文章数组
  * 每项post字段包含：`id`, `cat_id`, `title`, `publish_time`, `user_id`, `is_top`, `thumbnail`, `abstract`, `comments`, `views`, `likes`, `format_publish_time`
  * 若附加字段选了分类详情，则还对应包含`cat`字段。`cat`字段包含：`id`, `title`, `alias`三项。
  * 若附加字段选了作者信息，则还对应包含`user`字段。`user`字段包含：`id`, `username`, `nickname`, `avatar`四项。
  * 若附加字段选了计数，则对应包含`meta`字段。`meta`字段包含：`comments`, `views`, `likes`三项