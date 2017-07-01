# 采集模块
此模块用于与火车头采集器配合，实现采集信息入库。

此模块采用faycms后台登录机制，登录faycms后台后方可访问。
> 暂不支持权限管理

火车头采集器web发布配置文件在类库根目录: `vendor/faysoft/faycollect/Faycms.wpm`。  
火车头软件使用教程参见火车头官网。
> 程序本身暂不支持采集

## 相关接口
### /faycollect/admin/post/cats
获取可用分类。返回一个select标签结构（火车头解析这个比解析json容易）

### /faycollect/admin/post/create
发布文章
- `title`：标题（不严格限制长度，但超过500字符会被截断）
- `content`: 正文
- `thumbnail`：缩略图
- `publish_time`：发布时间（会尽可能去转换为时间戳，无法转换或为空，则默认为当前时间）
- `cat_id`：分类ID
- `status`：文章状态，默认为“已发布”
- `auto_thumbnail`：若非0，则尝试获取正文中第一张图片作为缩略图（缩略图必然会下载到本地）。默认为1
- `remote`：若非0，则尝试将正文中所有图片下载到本地（下载失败则保留原图片路径）。默认为1
- `tags`：标签（支持：空格、中文逗号、英文逗号、竖线分割方式）
- `abstract`: 摘要
- `seo_title`: SEO标题
- `seo_keywords`: SEO关键词
- `seo_description`: SEO描述