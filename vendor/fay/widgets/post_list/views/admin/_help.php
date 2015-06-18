<div class="text">
	<strong>概述</strong>
	<p>该工具仅搜索可见的文章（已发布状态，且未删除，且发布时间小于当前时间）进行显示</p>
	<strong>模版层可用参数</strong>
	<ol>
		<li><code>$alias</code>：该小工具实例的别名</li>
		<li><code>$config</code>：本页面的配置信息</li>
		<li>
			<code>$posts</code>：符合条件的文章数组
			<ul>
				<li>每项包含：<code>id</code>, <code>cat_id</code>, <code>title</code>, <code>publish_time</code>,
					<code>user_id</code>, <code>is_top</code>, <code>thumbnail</code>, <code>abstract</code>,
					<code>comments</code>, <code>views</code>, <code>likes</code></li>
				<li>
					若选择附加分类、作者信息，则还对应包含<code>cat</code>, <code>user</code>字段
					<ul>
						<li>cat字段包含：<code>id</code>, <code>title</code>, <code>alias</code></li>
						<li>user字段包含：<code>id</code>, <code>username</code>, <code>nickname</code>, <code>avatar</code></li>
					</ul>
				</li>
			</ul>
		</li>
	</ol>
	<strong>页码条可用参数</strong>
	<ol>
		<li><code>$current_page</code>：当前页</li>
		<li><code>$page_size</code>：分页大小</li>
		<li><code>$empty_text</code>：无文章时的替换文本（可以包含html）</li>
		<li><code>$offset</code>：当前页记录偏移量</li>
		<li><code>$start_record</code>：当前页起始记录号</li>
		<li><code>$end_record</code>：当前页截至记录号</li>
		<li><code>$total_records</code>：总记录数</li>
		<li><code>$total_pages</code>：总页数</li>
		<li><code>$adjacents</code>：可见页码前后偏移量</li>
		<li><code>$page_key</code>：分页字段</li>
	</ol>
</div>