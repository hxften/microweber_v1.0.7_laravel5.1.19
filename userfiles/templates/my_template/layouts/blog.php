<?php
/*
  type: layout
  content_type: dynamic
  name: Blog
*/
?>
<!-- 博客页面布局 -->
<?php include template_dir().'header.php'; ?>
<div class="container">博客列表页
    <div class="blog-content edit" field="content" rel="content">
        <h2>Recent Posts:</h2>
        <module type="posts" />
    </div>
    <div class="blog-sidebar edit" field="sidebar" rel="inherit">
        <h2>Categories</h2>
        <module type="categories" />
    </div>
</div>
<?php include template_dir().'footer.php';