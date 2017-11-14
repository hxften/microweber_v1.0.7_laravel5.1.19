<?php

    $before =  get_option('before', $params['id']);
    $after =  get_option('after', $params['id']);
    $title =  get_option('title', $params['id']);
    if(empty($before)){
        if (isset($params['onmouseover_img'])) {
            $onmouseover_img= $params['onmouseover_img'];
        }
        $before=$onmouseover_img;
        //"http://kime.cms.com/userfiles/templates/templates1//images/Shape_22.png";
    }
    if(empty($after)){
        if (isset($params['onmouseleave_img'])) {
            $onmouseleave_img = $params['onmouseleave_img'];
        }
        $after=$onmouseleave_img;
        //"http://kime.cms.com/userfiles/templates/templates1//images/Shape_22_1.png";
    }
    if(empty($title)){
        if (isset($params['dealut_content'])) {
            $title_tmp = $params['dealut_content'];
        }
        $title=$title_tmp;
    }
    $rand = uniqid();
    $class_name='before_after'.$rand;
    $title_class_name='title_class_name'.$rand;
?>
<script>
    $(document).ready(function(){
        $(".<?php echo $class_name;?>").mouseover(function(){
            $(this).attr("src","<?php echo $after;?>");
            $(".<?php echo $title_class_name;?>").css("color","#23b39a");
        });
        $(".<?php echo $class_name;?>").mouseleave(function(){
            $(this).attr("src","<?php echo $before;?>");
            $(".<?php echo $title_class_name;?>").css("color","#9dafc0");
        });
    });
</script>
<img class="<?php echo $class_name;?>" class="about-us-listItem" src="<?php echo $before;?>"/>
<div class="<?php echo $title_class_name;?> about-us-listItem-name"><?php echo $title;?></div>