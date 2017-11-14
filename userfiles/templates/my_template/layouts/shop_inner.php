<?php
/*
  type: layout
  content_type: static
  name: Product
*/
?>
<?php include template_dir().'header.php'; ?>
<div class="container">商品详情页
  <div class="edit"  field="content" rel="content">
      <module type="pictures" rel="content" />
      <div class="edit"  field="content_body" rel="content">
        <p class="element">My product text</p>
      </div>
      <module type="shop/cart_add" />
  </div>
</div>
<?php include template_dir().'footer.php';