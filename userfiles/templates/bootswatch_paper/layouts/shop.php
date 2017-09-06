<?php

/*
  content_type: dynamic
  name: Shop
  file information

*/

?>
<?php include template_dir(). "header.php"; ?>

<section id="content">商品列表页
  <div class="container">
    <div class="row" id="shop-products-conteiner">
      <div class="col-sm-12 edit"  field="content" rel="page">
        <p class="p0 element">This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative & Make Web.</p>
      </div>
    </div>
    <div class="row" id="shop-products-conteiner">
      <div class="col-sm-8 edit"  field="content2" rel="page">
        <module type="shop/products"  limit="18" description-length="70"    />
      </div>
      <div class="col-sm-4">
        <?php include template_dir(). 'layouts' . DS."shop_sidebar.php"; ?>
      </div>
    </div>
  </div>
</section>

<?php include template_dir(). "footer.php"; ?>