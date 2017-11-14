 <?php

/*

  type: layout
  content_type: static
  name: Home
  position: 11
  description: Home layout

*/

?>
<?php include template_dir(). "header.php"; ?>
<!-- 
有.edit上课的时候，我们说，我们要的元素，是在包含标记，可编辑。
有了这些信息，Microweber就会知道，在实时编辑模式中，我们希望有对内容的绝对控制权。
编号，REL和场也应包括在为了使字段独特和防止碰撞和允许输出错误不同编辑字段。
-->
哈哈哈哈哈

<module type="testimonials" />
<div class="container edit" id="home-layout"  rel="page" field="content">
  <div class="row clearfix">
    <div class="col-md-12 column">
      <div class="jumbotron">
        <h1> Hello, world! </h1>
        <p> This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique. </p>
        <p> <a class="btn btn-primary btn-large" href="#">Learn more</a> </p>
      </div>
    </div>
  </div>
  <div class="mw-row clearfix">
    <div class="mw-col" style="width:33.33%">
      <div class="mw-col-container">
        <div class="element">

          <h2> Heading </h2>
          <p> Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p> <a class="btn" href="#">View details</a> </p>
        </div>
      </div>
    </div>
    <div class="mw-col" style="width:33.33%">
      <div class="mw-col-container">
        <div class="element">
          <h2> Heading </h2>
          <p> Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p> <a class="btn" href="#">View details</a> </p>
        </div>
      </div>
    </div>
    <div class="mw-col" style="width:33.33%">
      <div class="mw-col-container">
        <div class="element">
          <h2> Heading </h2>
          <p> Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p> <a class="btn" href="#">View details</a> </p>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="element"> <br>
      <br>
      <h3 align="center" class="symbol">Powerful &nbsp;&amp;&nbsp; User Friendly &nbsp;Content Management System &nbsp;of &nbsp;New Generation</h3>
      <h4 align="center">with rich PHP and JavaScript API</h4>
        <br>
    </div>
  </div>
  <div class="container">
    <div class="mw-row">
      <div class="mw-col" style="width:33.33%">
        <div class="mw-col-container">
          <div class="element">
            <hr class="visible-desktop column-hr">
          </div>
        </div>
      </div>
      <div class="mw-col" style="width:33.33%">
        <div class="mw-col-container">
          <h2 align="center">
            <?php _e("Latest Posts"); ?>
          </h2>
        </div>
      </div>
      <div class="mw-col" style="width:33.33%">
        <div class="mw-col-container">
          <div class="element">
            <hr class="visible-desktop column-hr">
          </div>
        </div>
      </div>
    </div>

    <module
          data-type="posts"
          data-limit="3"
          id="home-posts"
          data-description-length="100"
          data-show="thumbnail,title,created_at,read_more,description"
          data-template="columns" />
  </div>
  <div class="container">
    <div class="mw-row">
      <div class="mw-col" style="width:33.33%">
        <div class="mw-col-container">
          <div class="element">
            <hr class="visible-desktop column-hr">
          </div>
        </div>
      </div>
      <div class="mw-col" style="width:33.33%">
        <div class="mw-col-container">
          <h2 align="center">
            <?php _e("Latest Products"); ?>
          </h2>
        </div>
      </div>
      <div class="mw-col" style="width:33.33%">
        <div class="mw-col-container">
          <div class="element">
            <hr class="visible-desktop column-hr">
          </div>
        </div>
      </div>
    </div>
    <module
          data-type="shop/products"
          data-limit="3"
          id="home-products"

            />
  </div>
</div>
<?php include template_dir().  "footer.php"; ?>