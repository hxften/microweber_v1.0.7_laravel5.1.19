<?php

/*

type: layout

name: left

description: Default

*/
?>


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
<?php  $rand = uniqid(); ?>
<div class="clearfix module-posts-template-left" id="posts-<?php print $rand; ?>">
<ul class="news-list">
<?php if(!empty($data)){?>
     <?php foreach($data as $item){ ?>
        <li class="element"><a class="point">▪</a><a  href="<?php echo $item['url'];?>" class="news-title"><?php echo $item['title'];?> </a><a class="news-date"><?php echo substr($item['created_at'],0,10);;?></a></li>
    <?php } ?>
<?php }else {?>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <li class="element"><a class="point">▪</a><a class="news-title">国家电网或成首家公布混改方案企业</a><a class="news-date">2016-12-07</a></li>
    <?php }?>
</ul>
</div>
<!--
</ul>-->