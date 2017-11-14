<?php
/*
type: layout
content_type: static
name: Contact Form
position: 7
*/
?>
<!-- 联系表格布局 -->
<?php include template_dir(). "header.php"; ?>
<div class="container">
  <div class="edit" field="content" rel="content">
    <h3>Find us on the map</h3>
    <module type="google_maps" />
    <h3>Or fill our form</h3>
    <module type="contact_form" />
    <div class="edit" field="content_body" rel="content">
      <h3>Address</h3>
      <hr />
      <p>1600 Pennsylvania Avenue Northwest, Washington, DC 20500, USA</p>
    </div>
  </div>
</div>
<?php include template_dir(). "footer.php";