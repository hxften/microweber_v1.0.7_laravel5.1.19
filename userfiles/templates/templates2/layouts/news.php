<?php

/*

  type: layout
  content_type: dynamic
  name: New
  position: 11
  description: Home layout

*/

?>

<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

	<!-- banner start -->
		<div class="carousel-inner edit" id="new"  rel="content" field="carouselnew">
			<div class="active item">
				<div class="carousel-inner-content">
				    <div class="carousel-inner-content-left">
						<img src="{TEMPLATE_URL}images/phone.png" class="phone"/>
					</div>
					<div class="carousel-inner-content-right">	
						<div><a class="carousel-span1">随时随地轻松投注</a></div>
						<div><a class="carousel-span2">移动端同步即时信息，财富尽在指尖，打开手机轻松<br/>投注，让你玩的更尽兴<a></div>
						<ul>
							<li><a><img src="{TEMPLATE_URL}images/point.png"/></a><a>安全、诚信、可靠的在线平台</a></li>
							<li><a><img src="{TEMPLATE_URL}images/point.png"/></a><a>多种多样化的彩种</a></li>
						</ul>
						<div><a class="carousel-span3">会员注册就送现金</a>
							 <a class="carousel-span4">&nbsp;¥18元</a></div>
						<a class="login">登录</a>
                        <a class="login">注册</a>
					</div>
				</div>
			</div>
		</div>
	
	<!-- banner end -->
	<!--新闻列表部分 start-->

		<div class="news-content edit">
				<dl>
				<?php 
					// 返回所有分类
					$categories = content_categories(PAGE_ID); 
					if(!empty($categories)){

					foreach($categories as $key => $category) {
						if(($key%3) != 2) {
				?>
						<dt>
						    <div class="line"></div>
							<div class="news-title-content">
								<div class="news-type"><a href="#"><?php print $category['title'];?></a></div>
								<div class="more"><a class="service-item-des" href="<?php print '/news/category:'.$category['id'];?>">更多</a></div>
							</div>
							<module content-id="<?php print PAGE_ID; ?>" type="posts" template="international" limit="6" data-category-id="<?php print $category['id'];?>" data-show="thumbnail,title,description,created_at"  />		
						</dt>

						<?php } else { ?>

						<dt class="news-last">
							<div class="line"></div>
							<div class="news-title-content">
								<div class="news-type"><a href="#"><?php print $category['title'];?></a></div>
								<div class="more"><a class="service-item-des" href="<?php print '/news/category:'.$category['id'];?>">更多</a></div>
							</div>
							
							<module content-id="<?php print PAGE_ID; ?>" type="posts" template="coollife" limit="7" data-category-id="<?php print $category['id'];?>" data-show="thumbnail,title,description,created_at" hide-paging="y" title-length="40" description-length="40" />
						</dt>
						<?php }?>
					<?php } } else {?>
						<div>请添加分类</div>
					<?php }?>
				</dl>
		</div>

	<!--新闻列表部分 end-->
	<!--logo list start-->
	   <div class="icon-list edit" id="iconnewedit"  rel="content" field="iconlist">
		<div class="icon-list-content element">
			<img src="{TEMPLATE_URL}images/AG.png">
			<img src="{TEMPLATE_URL}images/BBIN_logo.png">
			<img src="{TEMPLATE_URL}images/en.png">
			<img src="{TEMPLATE_URL}images/playtech.png">
			<img src="{TEMPLATE_URL}images/basha.png">
			<img src="{TEMPLATE_URL}images/riental.png">
		</div>
	</div>
	<!--logo list end-->

<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>