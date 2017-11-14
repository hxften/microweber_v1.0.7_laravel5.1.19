<?php
/*
 type: layout
content_type: static
name: Clean
position: 2
description: Default
*/
?>

<!-- 作为一个备用布局 没有其他布局可以使用 -->
<?php include template_dir(). "header.php"; ?>

<div id="content"> 
  <div class="container edit"  field="content" rel="content">
    <p>This is my text</p>
  </div>
</div>
<?php include template_dir(). "footer.php"; ?>