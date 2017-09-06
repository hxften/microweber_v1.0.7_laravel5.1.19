<?php include template_dir(). "header.php"; ?>

<section id="content" class="shop-page shop-page-inner">
  <?php include template_dir(). 'layouts' . DS."shop_top.php"; ?>
  <div class="margin-spacer-element-20"></div>
  <div class="product-inner-container">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 xsidebar-lined-border-right" data-mw=main>
          <div class="edit"  field="content" rel="content">
            <div class="mw-row">
              <div class="mw-col" style="width:60%">
                <div class="mw-col-container">
                  <module type="pictures" rel="content" template="product_gallery" />
                </div>
              </div>
              <div class="mw-col" style="width:40%">
                <div class="mw-col-container">
                  <h2 class="edit product-inner-page-title"  field="title" rel="content">Product inner page</h2>
                  <hr>
                  <div class="product-description">
                    <div class="edit"  field="content_body" rel="content">
                      <p class="element">This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative &amp; <strong style="font-weight: 600">Make Web</strong>.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="edit"  field="product_tabs" rel="content">
            <module type="tabs"  />
          </div>
        </div>
        <!------------ Sidebar -------------->
        <div class="col-sm-4 sidebar-lined-border-left">
          <?php $data_fields_values = mw()->data_fields_manager->get_values('rel_type=categories&rel_id='.category_id()); ?>
          <div class="sidebar-boxes-wrapper">
            <?php if(isset($data_fields_values['sidebar']) and trim($data_fields_values['sidebar']) == 'no_cart') { ?>
            <div class="sidebar-boxes-spacer"></div>
            <module type="menu" template="collaterals" menu-name="collaterals" />
            <div class="sidebar-boxes-spacer"></div>
            <module type="staticbox"  />
            <div class="sidebar-boxes-spacer"></div>
            <module type="staticbox" template="presence"  />
            <?php } else  { ?>
            <div class="edit"  field="product_sidebar" rel="content">
              <module type="shop/cart_add" template="sidebar" />
              <div class="sidebar-boxes-spacer"></div>
              <module type="menu" template="collaterals" menu-name="collaterals" />
              <div class="sidebar-boxes-spacer"></div>
              <module type="staticbox"  />
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include template_dir(). "footer.php"; ?>
