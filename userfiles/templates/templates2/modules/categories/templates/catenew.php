<?php

/*

type: layout

name: catenew

description: categorynew

*/
?>
<?php
    $params['ul_class'] = 'nav nav-list';
	$params['ul_class_deep'] = 'nav nav-list';
?>
 
<script>mw.require("<?php print modules_url(); ?>categories/templates.css", true); </script>
		<?php  category_tree($params);  ?>
</div>
