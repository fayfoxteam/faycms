#标签文章列表（带分页）
根据标签（tag）显示一个带分页条的文章列表。
> 该工具仅搜索可见的文章（已发布状态，且未删除，且发布时间小于当前时间）进行显示

**模版层可用参数**

- `$alias`：该小工具实例的别名
- `$config`：本页面的配置信息
- `$posts`：符合条件的文章数组
  * 每项post字段包含：`id`, `cat_id`, `title`, `publish_time`, `user_id`, `is_top`, `thumbnail`, `abstract`, `comments`, `views`, `likes`, `format_publish_time`
  * 若附加字段选了分类详情，则还对应包含`cat`字段。`cat`字段包含：`id`, `title`, `alias`三项。
  * 若附加字段选了作者信息，则还对应包含`user`字段。`user`字段包含：`id`, `username`, `nickname`, `avatar`四项。
  * 若附加字段选了计数，则对应包含`meta`字段。`meta`字段包含：`comments`, `views`, `likes`三项

**页码条可用参数**

- `$current_page`：当前页
- `$page_size`：分页大小
- `$empty_text`：无文章时的替换文本（可以包含html）
- `$offset`：当前页记录偏移量
- `$start_record`：当前页起始记录号
- `$end_record`：当前页截至记录号
- `$total_records`：总记录数
- `$total_pages`：总页数
- `$adjacents`：可见页码前后偏移量
- `$page_key`：分页字段