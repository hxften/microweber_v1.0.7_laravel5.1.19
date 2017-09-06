<?php

/*

type: layout

name: right_bottom

description: Default

*/
?>
<?php  $rand = uniqid(); ?>
<div class="module-posts-template-right_bottom" id="posts-<?php print $rand; ?>">

<?php


$tn = $tn_size;
if (!isset($tn[0]) or ($tn[0]) == 150) {
    $tn[0] = 220;
}
if (!isset($tn[1])) {
    $tn[1] = $tn[0];
}


?>
<?php
$only_tn = false;


$search_keys = array('title', 'created_at', 'description', 'read_more');

if (isset($show_fields) and is_array($show_fields) and !empty($show_fields)) {
    $only_tn = true;
    foreach ($search_keys as $search_key) {
        foreach ($show_fields as $show_field) {
            if ($search_key == $show_field) {
                $only_tn = false;
            }
        }
    }

}
?>

<ul class="pic-news-list">
<?php if(!empty($data)){?>
     <?php foreach($data as $item){ ?>
        <li>
            <img src="<?php echo empty($item['image'])?TEMPLATE_URL.'/images/pic_news.jpg':$item['image'];?>" class="pic_new">
            <div class="pic-news-title"><a href="<?php echo $item['url'];?>"><?php echo $item['title'];?></a></div>
        </li>
    <?php } ?>
<?php }else {?>
    <li>
        <img src="<?php print TEMPLATE_URL; ?>/images/pic_news.jpg" class="pic_new">
        <div class="pic-news-title"><a>重庆天然气净化总厂渠县分厂应急演练保安全</a></div>
    </li>
    <li>
        <img src="<?php print TEMPLATE_URL; ?>/images/pic_news.jpg" class="pic_new">
        <div class="pic-news-title"><a>重庆天然气净化总厂渠县分厂应急演练保安全</a></div>
    </li>
    <li>
        <img src="<?php print TEMPLATE_URL; ?>/images/pic_news.jpg" class="pic_new">
        <div class="pic-news-title"><a>重庆天然气净化总厂渠县分厂应急演练保安全</a></div>
    </li>
    <li>
        <img src="<?php print TEMPLATE_URL; ?>/images/pic_news.jpg" class="pic_new">
        <div class="pic-news-title"><a>重庆天然气净化总厂渠县分厂应急演练保安全</a></div>
    </li>
    <li>
        <img src="<?php print TEMPLATE_URL; ?>/images/pic_news.jpg" class="pic_new">
        <div class="pic-news-title"><a>重庆天然气净化总厂渠县分厂应急演练保安全</a></div>
    </li>
    <li>
        <img src="<?php print TEMPLATE_URL; ?>/images/pic_news.jpg" class="pic_new">
        <div class="pic-news-title"><a>重庆天然气净化总厂渠县分厂应急演练保安全</a></div>
    </li>
    <?php }?>
</ul>
</div>
<!--
</ul>-->