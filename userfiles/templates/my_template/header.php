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
        <link rel="stylesheet" type="text/css"
          href="<?php print template_url('css/style.css'); ?>" />
        <script type="text/javascript"
          src="<?php print template_url('assets/site.js'); ?>"></script>

    </head>
    <body>
    <div id="header">
    <div class="edit" rel="global" field="header">
        <h1>
          <a href="<?php print site_url(); ?>">
            <?php echo get_option('website_title', 'website'); ?>
          </a>
        </h1>
        <module type="menu" name="header_menu"
          id="main-navigation" template="pills"  />
    </div>
</div>