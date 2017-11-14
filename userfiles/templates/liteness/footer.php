
<div id="footer">
  <div class="container">
    <div class="mw-ui-row">
      <div class="mw-ui-col" id="copyright">
        <div class="edit" field="footer-left" rel="global">
          <p class="element">Copyright &copy; All rights reserved</p>
        </div>
      </div>
      <div class="mw-ui-col" id="footer-menu">
        <div class="edit" field="footer-right" rel="global">
          <module type="menu"  name="footer_menu" template="small">
        </div>
      </div>
      <div class="mw-ui-col" id="powered">
        <div><?php print powered_by_link(); ?></div>
      </div>

<?php 

//include THIS_TEMPLATE_DIR . 'test.php'; 
//$copyright_url = modules_url().'footer/copyright.php';
//$copyright = file_get_contents($copyright_url);
//$text = var_export($copyright , true);
//echo $text;
//var_export($copyright);
//echo sprintf("%d", $copyright); 
//echo $copyright;

?>

    </div>
  </div>
</div>
</div>
</div>
 
</body></html>