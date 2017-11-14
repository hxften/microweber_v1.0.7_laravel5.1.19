<?php

/*

type: layout

name: T1_top_menu

description:

*/

?>

<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>style.css", true);</script>

  <script>
  $(document).ready(function(){
    $(".nav-tabs li").each(function(){
      if($(this).hasClass('active')){
        $(this).append('<div class="myTabSel"></div>');
      };
    });
    });
</script>
  <?php
  $mt =  menu_tree($menu_filter);

 //echo htmlspecialchars($mt);die();
  if($mt!=false){
    print ($mt);
  }
  else {
    echo '<ul role="menu" class="nav nav-tabs myTab menu_18 menu-root menu-item-id-45 menu-item-parent-18" ><li class="test2 depth-0" data-item-id="45" ><a itemprop="url" data-item-id="45" class="menu_element_link menu-root menu-item-id-45 menu-item-parent-18 depth-0 " href="http://kime.cms.com/2016-Dec-10-094202">关于我们</a></li><li class="test2 depth-0" data-item-id="44" ><a itemprop="url" data-item-id="44" class="menu_element_link first-child child-0 menu-item-id-44 menu-item-parent-18 depth-0 " href="http://kime.cms.com/2016-Dec-10-094137">行业新闻</a></li><li class="test2 depth-0" data-item-id="42" ><a itemprop="url" data-item-id="42" class="menu_element_link first-child child-0 menu-item-id-42 menu-item-parent-18 depth-0 " href="http://kime.cms.com/2016-Dec-10-094029">服务优势</a></li><li class="test2 depth-0" data-item-id="43" ><a itemprop="url" data-item-id="43" class="menu_element_link first-child child-0 menu-item-id-43 menu-item-parent-18 depth-0 " href="http://kime.cms.com/2016-Dec-10-094052">公司实力</a></li><li class="test2 active depth-0" data-item-id="41" ><a itemprop="url" data-item-id="41" class="menu_element_link active first-child child-0 menu-item-id-41 menu-item-parent-18 depth-0 " href="http://kime.cms.com/2016-Dec-10-093952">首页</a></li></ul>';
  }
  ?>

