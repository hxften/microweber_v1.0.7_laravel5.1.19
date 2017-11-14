<?php

/*

type: layout

name: coolife

description: coolifenew

*/
?>
<?php



$tn = $tn_size;

if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 120;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
?>


<script>mw.moduleCSS("<?php print modules_url(); ?>posts/css/style.css");</script>

<?php if (!empty($data)) {?>
<div class="news-side-img-content">
  <?php foreach ($data as $key => $item): ?>

		<?php if($key == 0) :?>
            <div class="news-photo">
                 <img src="<?php empty($item['image'])? print thumbnail(TEMPLATE_URL.'/images/news1.jpg', $tn[0], $tn[1]) : print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt=""  />
                 <div class="news-side-img-content-title"><a href="<?php print $item['url'] ?>" target="_blank"><?php print mb_substr($item['title'],0,15) ?></a></div>
            </div>
       <?php endif; ?>
       <?php if($key == 1)  :?>
            <div class="news-photo news-photo-right">
                 <img src="<?php empty($item['image'])? print thumbnail(TEMPLATE_URL.'/images/news1.jpg', $tn[0], $tn[1]) : print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt=""  />
                 <div class="news-side-img-content-title"><a href="<?php print $item['url'] ?>" target="_blank"><?php print mb_substr($item['title'],0,15) ?></a></div>
            </div>
 		<?php endif; ?>
  <?php endforeach; ?>
</div>
	<?php foreach ($data as $key => $item): ?>
		<?php  if($key !== 0 && $key !== 1) :?>
			<div class="news-list news-list-side"><a class="link media-heading" href="<?php print $item['url'] ?>" target="_blank"><?php print mb_substr($item['title'],0,15) ?></a></div>
		<?php endif; ?>
	<?php endforeach; ?>

<?php }?>
