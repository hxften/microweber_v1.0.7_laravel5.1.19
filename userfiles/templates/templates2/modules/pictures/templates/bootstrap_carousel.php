<?php

/*

type: layout

name: Bootstrap Carousel

description: Bootstrap Carousel

*/

  ?>


<?php if(is_array($data )): ?>

<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css"); </script>

<?php $rand = 'item_carousel_'.$params['id']; $id = 'carousel_'.$params['id']; ?>
    <div class="mw-module-images">
    <div id="<?php print $id; ?>" class="carousel slide mw-image-carousel">
      <ol class="carousel-indicators">
        <?php $count = -1; foreach($data  as $item): ?>
        <li data-target="#<?php print $id; ?>" data-slide-to="<?php print $count++; ?>" class="<?php if($count==0){ print 'active';} ?>"></li>
        <?php endforeach; ?>
      </ol>
    <!-- Carousel items -->
      <div class="carousel-inner">
        <?php $count = -1; foreach($data  as $item): ?>
         <?php $count++; ?>
          <div class="<?php if($count==0){ print 'active ';} ?>item">
            <img src="<?php print thumbnail($item['filename'], 1900, 1000); ?>"  />
            <?php if(isset($item['title']) and $item['title'] !=''){ ?>
            <div class="carousel-caption">
                <p><?php print $item['title']; ?></p>
            </div>
            <?php } ?>

          </div>
        <?php endforeach ; ?>
      </div>
    <!-- Carousel nav -->
      <a class="carousel-control left" href="#<?php print $id; ?>" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="carousel-control right" href="#<?php print $id; ?>" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
    </div>

<?php else : ?>
<?php 

if(empty($data)){
	$arry_img[]=array('id' => 2,
			'updated_at' => '2016-12-10 03:23:55',
			'created_at' => '2016-12-10 03:23:55',
			'created_by' => 1,
			'edited_by' => 1,
			'session_id' => 'ff036915fe05d437b90667f7c29a13aed381c1a7',
			'rel_type' => 'content',
			'rel_id' => 38,
			'media_type' => 'picture',
			'position' => 9999999,
			'title' =>'',
			'description' =>'',
			'embed_code'=>'',
			'filename' => TEMPLATE_URL.'/images/default_banner1.jpg',
	);
	$arry_img[]=array('id' => 3,
			'updated_at' => '2016-12-10 03:23:55',
			'created_at' => '2016-12-10 03:23:55',
			'created_by' => 1,
			'edited_by' => 1,
			'session_id' => 'ff036915fe05d437b90667f7c29a13aed381c1a7',
			'rel_type' => 'content',
			'rel_id' => 38,
			'media_type' => 'picture',
			'position' => 9999999,
			'title' =>'',
			'description' =>'',
			'embed_code'=>'',
			'filename' => TEMPLATE_URL.'/images/default_banner2.jpg',
	);
	$data=$arry_img;
}

?>
 <?php endif; ?>
