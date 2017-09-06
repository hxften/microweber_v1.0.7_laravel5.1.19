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
    <?php $theme_css_file = get_option("bootswatch_theme_css_file", "bootswatch_theme"); ?>
    <?php if($theme_css_file == ''): ?>
        <link rel="stylesheet" id="bootstrap_theme" href="<?php print template_url() ?>css/default.css" type="text/css" media="all">
    <?php else: ?>
        <link rel="stylesheet" id="bootstrap_theme" href="<?php print $theme_css_file; ?>" type="text/css" media="all">
    <?php endif; ?>
    <link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="{TEMPLATE_URL}css/style.css" type="text/css" media="all">
    <link rel="stylesheet" href="{TEMPLATE_URL}css/main.css" type="text/css" media="all">
    <script type="text/javascript" src="{TEMPLATE_URL}js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{TEMPLATE_URL}js/default.js"></script>
    <link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/css/index.css" type="text/css">

     <script type="text/javascript" src="<?php print TEMPLATE_URL; ?>/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="<?php /*print TEMPLATE_URL; */?>/js/index.js"></script>
         <script >
         
         function selNav(obj){
			$(".navbar-nav li").each(function(){
				$(this).removeClass("nav_active");
			});
			$(obj).addClass("nav_active");
		}

            $(document).ready(function(){
	             //header部分左侧是个图标控制
				$(".navbar-brand1 li").each(function(){
					$(this).mouseover(function(){
						var index = $(this).index();
						if(index == 0){
						  $(".logo_f").attr("src","<?php print TEMPLATE_URL; ?>images/logo_f1.png");
						  $(".logo_b").attr("src","<?php print TEMPLATE_URL; ?>images/logo_bird.png");
						  $(".logo_t").attr("src","<?php print TEMPLATE_URL; ?>images/logo_t.png");
						  $(".logo_c").attr("src","<?php print TEMPLATE_URL; ?>images/logo_circle.png");
						}else if(index == 1){
						  $(".logo_f").attr("src","<?php print TEMPLATE_URL; ?>images/logo_f.png");
						  $(".logo_b").attr("src","<?php print TEMPLATE_URL; ?>images/logo_bird1.png");
						  $(".logo_t").attr("src","<?php print TEMPLATE_URL; ?>images/logo_t.png");
						  $(".logo_c").attr("src","<?php print TEMPLATE_URL; ?>images/logo_circle.png");
						}else if(index == 2){
						  $(".logo_f").attr("src","<?php print TEMPLATE_URL; ?>images/logo_f.png");
						  $(".logo_b").attr("src","<?php print TEMPLATE_URL; ?>images/logo_bird.png");
						  $(".logo_t").attr("src","<?php print TEMPLATE_URL; ?>images/logo_t1.png");
						  $(".logo_c").attr("src","<?php print TEMPLATE_URL; ?>images/logo_circle.png");
						}else if(index == 3){
						  $(".logo_f").attr("src","<?php print TEMPLATE_URL; ?>images/logo_f.png");
						  $(".logo_b").attr("src","<?php print TEMPLATE_URL; ?>images/logo_bird.png");
						  $(".logo_t").attr("src","<?php print TEMPLATE_URL; ?>images/logo_t.png");
						  $(".logo_c").attr("src","<?php print TEMPLATE_URL; ?>images/logo_circle1.png");
						}
				});	
			});
			//控制网站登陆注册联系我们点击效果
			$(".navbar-nav li").each(function(){
				$(this).mouseover(function(){
					selNav(this);
				});
			});
            //设定骰子位置
            $(".dice").css("top",($("#myCarousel").height() - $(".dice").height())/2 + "px");
            });
        </script>
</head>
<body>
<?php if(is_live_edit() == true): ?>
    <div style="height:50px;"></div>
<?php endif; ?>
<!--header start-->
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <ul class="navbar-brand1  edit" id="index_header_share_img" field="index_header_share_img" rel="page">
                <li><img class="logo_f" src="<?php print TEMPLATE_URL; ?>/images/logo_f.png"></li>
                <li><img class="logo_b" src="<?php print TEMPLATE_URL; ?>/images/logo_bird.png"></li>
                <li><img class="logo_t" src="<?php print TEMPLATE_URL; ?>/images/logo_t.png"></li>
                <li><img class="logo_c" src="<?php print TEMPLATE_URL; ?>/images/logo_circle1.png"></li>
            </ul>
        </div>
        <div class="edit" id="index_header_login" field="index_header_login" rel="page">
            <ul class="nav navbar-nav ">
                <li class="nav_active" ><a href="javascript:void(0);"><img src="<?php print TEMPLATE_URL; ?>/images/user.png" class="menu-title-logo"><label>登陆login</label></a></li>
                <li ><a href="javascript:void(0);"><img src="<?php print TEMPLATE_URL; ?>/images/register.png" class="menu-title-logo"><label>注册register</label></a></li>
                <li><a href="javascript:void(0);" class="element" ><label style="margin-top: 4px;">联系我们：881-689-5913</label></a></li>
            </ul>
        </div>
    </div>
</nav>

<!--header end-->
<!--content start-->

<div class="content">
    <div class="KTool-logo">
        <module type="logo"  id="logo_header5" default-text="Bootstrap" class="KTool-logo" data-defaultlogo="<?php print TEMPLATE_URL; ?>/images/KTool.png" size="168"/>
    </div>
    <!--tab start-->

   <!--<ul  class="nav nav-tabs myTab">
    <li style="margin-right: 17%;"><a href="#about">关于我们</a><div class="myTabSel"></div></li>
        <li><a href="#news" >行业新闻</a></li>
        <li><a href="#service">服务优势</a></li>
        <li><a href="#power">公司实力</a></li>
        <li class="active"><a href="#home">首页</a></li>
    </ul>-->
 <module type="menu" name="header_menu3" id="main-navigation3"  template="T1_top_menu" ul_class="nav nav-tabs myTab" li_class="test2"/>
    <!--tab end-->
    <!--tabContent start-->
</div>

