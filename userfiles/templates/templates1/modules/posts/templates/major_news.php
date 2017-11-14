<?php

/*

type: layout

name: major_news

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
<div class="clearfix module-posts-template-news" id="posts-<?php print $rand; ?>">
<div style="width:100%;height:1px;background-color:#d3d3d3;margin-top: 5px;"></div>
<ul class="news-review-list">
    <?php if(!empty($data)){?>
        <?php foreach($data as $item){ ?>
            <li class="element"><a class="point">▪</a><a href="<?php echo $item['url'];?>" class="news-title"><?php echo $item['title'];?> </a></li>
        <?php } ?>
    <?php }else {?>
        <li class="element"><a class="point">▪</a><a class="news-title">新疆油田“6+1”重点工程收获2百吨井</a></li>
        <li class="element"><a class="point">▪</a><a class="news-title">新疆油田“6+1”重点工程收获2百吨井</a></li>
        <li class="element"><a class="point">▪</a><a class="news-title">新疆油田“6+1”重点工程收2百吨井</a></li>
        <li class="element"><a class="point">▪</a><a class="news-title">新疆油田“6+1”重点工程收2百吨井</a></li>
        <li class="element"><a class="point">▪</a><a class="news-title">新疆油田“6+1”重点工程收获42百吨井</a></li>
        <li class="element"><a class="point">▪</a><a class="news-title">新疆油田“6+1”重点工程收获2百吨井</a></li>
    <?php }?>
</ul>
    </div>
<!--
</ul>-->
