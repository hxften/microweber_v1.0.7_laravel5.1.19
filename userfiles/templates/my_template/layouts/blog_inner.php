<?php
/*
  type: layout
  content_type: static
  name: Post
  description: Post layout

*/
?>
<!-- 一个博客的布局也应该有一个内页显示单一的职位 -->
<?php include template_dir().'header.php'; ?>
<div class="container">博客详情页
    <h1 class="post-title edit" field="title" rel="content">My post titlewwwww</h1>
    <div class="blog-post edit" field="content" rel="content">
        <p>My post content</p>
    </div>
    <div class="blog-comments edit" field="post-comments" rel="content">
        <module type="comments" />
    </div>
    <div class="blog-sidebar edit" field="sidebar" rel="inherit">
        <h2>My sidebar</h2>
        <module type="categories" />
    </div>
</div>
<?php include template_dir().'footer.php';