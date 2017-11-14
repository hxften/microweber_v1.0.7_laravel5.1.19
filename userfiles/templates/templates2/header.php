<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
    <head>
        <title>{content_meta_title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <!--  Site Meta Data  -->
        <meta name="keywords" content="{content_meta_keywords}">
        <meta name="description" content="{content_meta_description}">

        <!--  Site Open Graph Meta Data  -->
        <meta property="og:title" content="{content_meta_title}">
        <meta property="og:type" content="{og_type}">
        <meta property="og:url" content="{content_url}">
        <meta property="og:image" content="{content_image}">
        <meta property="og:description" content="{og_description}">
        <meta property="og:site_name" content="{og_site_name}">

        <!--  Loading CSS and Javascripts  -->
		<link rel="stylesheet" href="<?php print template_url('css/bootstrap.min.css');?>" type="text/css" media="all">
		<link rel="stylesheet" href="<?php print template_url('css/animator.css');?>" type="text/css" media="all">
		<link rel="stylesheet" href="<?php print template_url('css/home.css');?>" type="text/css" media="all">
		<!-- <script type="text/javascript" src="{TEMPLATE_URL}js/jquery-1.11.1.min.js"></script> -->
		<script type="text/javascript" src="<?php print template_url('js/bootstrap.min.js');?>"></script>
		<script type="text/javascript" src="<?php print template_url('js/home.js');?>"></script>
		<script>
			$(document).ready(function(){
				$(".navbar-nav li").each(function(i){
					$(this).removeClass("nav_active");
					if($(this).hasClass("active")){
						$(this).addClass("nav_active");
					}
				});
				//设置body的背景图片
				$(".navbar-nav li").each(function(i){
					if($(this).hasClass("nav_active")){
						var index = $(this).index();
						if(index == 2){
							$("body").addClass("aboutUs_bg");
						
						}else if(index == 3 || index == 4){
							$("body").addClass("advantage_bg");
						}
					}
				});
			});
		</script>
    </head>
    <body>

    <!-- 导航部分 start-->
    <div class="edit head-menu" field="header" rel="global">
    	<!-- <div class="head_nav"> -->
			<div class="head_nav_content">
				<module type="logo" id="logo_headnav" default-text="Bootstrap" class="navbar-header" data-defaultlogo="<?php echo TEMPLATE_URL; ?>images/logo.png"/>
	    	</div>
	    	<module type="menu" name="header_menu" id="main-navigation"  template="navbar" ul_class="nav navbar-nav"/>
	   <!--  </div>-->
    </div>
    <!-- 导航部分 end-->
