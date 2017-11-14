<?php

/*

type: layout

name: international

description: internationalnew

*/
?>
<?php



$tn = $tn_size;

if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 140;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
?>


<script>mw.moduleCSS("<?php print modules_url(); ?>posts/css/style.css");</script>

<?php if (!empty($data)) {?>


  <?php foreach ($data as $key => $item): ?>

	<?php if($key == 0){ ?>
			<div class="news-title">
			<a href="<?php print $item['url'] ?>" target="_blank">
				<?php print mb_substr($item['title'],0,20) ?>
			</a>
			</div>
			<div class="news-desc-content">
            	<div class="news-photo">
                	<a href="">
                    	<img src="<?php empty($item['image'])? print thumbnail(TEMPLATE_URL.'/images/news1.jpg', $tn[0], $tn[1]) : print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt=""  />
                	</a>
            	</div>
            	<div class="news-des"><?php print $item['description'] ?></div>
            </div>


    <?php }else{?>
	   <div class="news-list"><h5><a class="link media-heading" href="<?php print $item['url'] ?>" target="_blank"><?php print mb_substr($item['title'],0,20) ?></a></h5></div>
      <?php }?>
  <?php endforeach; ?>

<?php } ?>








