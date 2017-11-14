<?php

/*

  type: layout
  content_type: static
  name: Home
  position: 11
  description: Home layout

*/

?>

<?php include THIS_TEMPLATE_DIR. "header.php"; ?>
<div class="edit" field="templates_content" rel="content">
<!-- banner start -->
    <div id="myCarousel" class="carousel slide">
        <div class="carousel-inner edit" id="innder"  rel="content" field="carouselindex">
            <div class="active item element">
                <div class="carousel-inner-content" >
                    <div class="carousel-inner-content-left" >
                        <img src="{TEMPLATE_URL}/images/phone.png" class="phone"/>
                    </div>
                    <div class="carousel-inner-content-right">	
                        <div><a class="carousel-span1">随时随地轻松投注</a></div>
                        <div><a class="carousel-span2">移动端同步即时信息，财富尽在指尖，打开手机轻松<br/>投注，让你玩的更尽兴</a></div>
                        <ul>
                            <li><a><img src="{TEMPLATE_URL}/images/point.png"/></a><a>安全、诚信、可靠的在线平台</a></li>
                            <li><a><img src="{TEMPLATE_URL}/images/point.png"/></a><a>多种多样化的彩种</a></li>
                        </ul>
                        <div><a class="carousel-span3">会员注册就送现金</a>
                        	 <a class="carousel-span4">&nbsp;¥18元</a></div>
                        
                        <a class="login">登录</a>
                        <a class="login">注册</a>
                    </div>
                </div>
            </div>
        </div>
        
    </div> 
    <!-- banner end -->
    <!-- 马上注册 start-->
    <div class="regist-now edit" id="registeredit"  rel="content" field="carouselregister">
        <div class="regist-now-content element">
             <img src="{TEMPLATE_URL}/images/mac.png" class="mac">
            <div class="regist-info">
                <a>狂欢在即</a>
                <a>欢乐无限</a>
                <a>每日结算</a>
                <div class="regist-content1"><a class="regist-span1">美女客服7*24小时贴心在线答疑</a></div>
                <div><a class="regist-span2">财富之门即将开启现在注册就送现金大礼，投注与中<br/>奖信息无缝对接，心动不如行动，还等什么马上注册<br/>，开启财富之旅......</a></div>
                <a class="regist-now-button">马上注册</a>
            </div>
            
        </div>
    </div>
    <!-- 马上注册 end-->
    <!--马上试玩 start-->
    <div class="play-now edit" id="playedit"  rel="playnow" field="global">
    	<div class="play-now-content element">
        <div class="play-info">
            <a>真人视讯</a>
            <a>美女如云</a>
            <a>不容错过</a>
            <div class="play-content1"><a class="play-span1">真人荷官在线陪玩一流服务宾至如归</a></div>
            <div><a class="play-span2">美女荷官在线陪玩，经典游戏，正规操作，经验娴熟<br/>给您最好的服务，最为最尊贵的您......</a></div>
            <a class="play-now-button">马上试玩</a>
        </div>
        <img src="{TEMPLATE_URL}/images/play-show.jpg" class="play-show">
    </div>
   </div>
    <!--马上试玩 end-->
    <!--logo list start-->
    <div class="icon-list edit" id="iconedit"  rel="page" field="carouselicon">
        <div class="icon-list-content element">
            <img src="{TEMPLATE_URL}/images/AG.png">
            <img src="{TEMPLATE_URL}/images/BBIN_logo.png">
            <img src="{TEMPLATE_URL}/images/en.png">
            <img src="{TEMPLATE_URL}/images/playtech.png">
            <img src="{TEMPLATE_URL}/images/basha.png">
            <img src="{TEMPLATE_URL}/images/riental.png">
        </div>
    </div>
    <!--logo list end-->
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>