#导航菜单
默认以ul, li的方式渲染一个导航树（带层级关系）。

**模版层可用参数**

- `$alias`：该小工具实例的别名 
- `$config`：本页面的配置信息
- `$menus`：menus表结果集
  * `title`：菜单项标题
  * `sub_title`：二级标题（不一定用到）
  * `alias`：别名，做菜单项高亮的时候或许会用到
  * `target`：打开方式（即a标签的target属性）
  * `link`：链接地址
  * `css_class`：CSS class属性
  * `children`： 子节点