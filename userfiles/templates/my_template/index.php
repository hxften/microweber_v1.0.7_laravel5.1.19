<?php
/*
  type: layout
  content_type: static
  name: Home
  description: Landing page
*/
?>
<?php include template_dir().'header.php'; ?>
<!-- rel="content" - 改变每一页或交
rel="global" - 改变整个网站
rel="page" - 改变每一页和子页面
rel="post" - 变动每一个岗位
rel="inherit" - 改变了每一个主要的页面，但不能是子页面和帖子
rel="your_custom_rel" 你可以定义自己的范围 -->

<div class="container">
    <div class="edit" field="content" rel="content">
        <h2>Welcome to my homepage!</h2>
        <p>
          This is my awesome Microweber template.<br />
          You can edit this text since its container has an "edit" class.
        </p>
        <p>Just click here and start typing!</p>
    </div>
</div>
<?php include template_dir().'footer.php';