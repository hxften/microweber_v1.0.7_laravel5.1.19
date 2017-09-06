<?php

/*

type: layout
content_type: dynamic
name: Shop
is_shop: y
description: shop layout
position: 4
*/


?>
<?php
if(url_param('section') and get_category_id_from_url()){
	include template_dir(). 'layouts' . DS."shop_category.php";	
return ;
}

 ?>
<?php include template_dir(). "header.php"; ?>

<section id="content">
  <?php include template_dir(). 'layouts' . DS."shop_top.php"; ?>
  <div class="container">
    <div class="row" id="shop-products-conteiner">
      <div class="col-sm-12 edit"  field="content" rel="page">
        <module type="shop/products"  limit="18" description-length="70"    />
      </div>
    </div>
  </div>
</section>
<?php 
	  
	  /*
	  
	  
	  
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <?php   include template_dir(). 'layouts' . DS."shop_sidebar.php"; ?>
    </div>
    <div class="col-md-9">
      <module type="pictures" template="bootstrap_carousel" />
      <module type="shop/products"   limit="18" description-length="70" hide-paging="n"   />
     
	  
	  <div class="row">
        <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail"> <img src="http://placehold.it/320x150" alt="">
            <div class="caption">
              <h4 class="pull-right">$24.99</h4>
              <h4><a href="#">First Product</a> </h4>
              <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
            </div>
            <div class="ratings">
              <p class="pull-right">15 reviews</p>
              <p> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> </p>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail"> <img src="http://placehold.it/320x150" alt="">
            <div class="caption">
              <h4 class="pull-right">$64.99</h4>
              <h4><a href="#">Second Product</a> </h4>
              <p>This is a short description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="ratings">
              <p class="pull-right">12 reviews</p>
              <p> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star-empty"></span> </p>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail"> <img src="http://placehold.it/320x150" alt="">
            <div class="caption">
              <h4 class="pull-right">$74.99</h4>
              <h4><a href="#">Third Product</a> </h4>
              <p>This is a short description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="ratings">
              <p class="pull-right">31 reviews</p>
              <p> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star-empty"></span> </p>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail"> <img src="http://placehold.it/320x150" alt="">
            <div class="caption">
              <h4 class="pull-right">$84.99</h4>
              <h4><a href="#">Fourth Product</a> </h4>
              <p>This is a short description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="ratings">
              <p class="pull-right">6 reviews</p>
              <p> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star-empty"></span> <span class="glyphicon glyphicon-star-empty"></span> </p>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail"> <img src="http://placehold.it/320x150" alt="">
            <div class="caption">
              <h4 class="pull-right">$94.99</h4>
              <h4><a href="#">Fifth Product</a> </h4>
              <p>This is a short description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="ratings">
              <p class="pull-right">18 reviews</p>
              <p> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star"></span> <span class="glyphicon glyphicon-star-empty"></span> </p>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-lg-4 col-md-4">
          <h4><a href="#">Like this template?</a> </h4>
          <p>If you like this template, then check out <a target="_blank" href="http://maxoffsky.com/code-blog/laravel-shop-tutorial-1-building-a-review-system/">this tutorial</a> on how to build a working review system for your online store!</p>
          <a class="btn btn-primary" target="_blank" href="http://maxoffsky.com/code-blog/laravel-shop-tutorial-1-building-a-review-system/">View Tutorial</a> </div>
      </div>
	 
    </div>
  </div>
</div>

 
	  */
	  
	  ?>
<?php include template_dir(). "footer.php"; ?>
