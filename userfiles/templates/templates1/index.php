<?php include THIS_TEMPLATE_DIR. "header.php"; ?>
    <!--公司实力 end-->

    <!--首页 start-->
    <div class="tab-pane fade in active" id="home">
    <!-- dice nav  start-->
<!--    <div class="dice edit"  id="index_dice-prompt" field="index_dice_prompt" rel="page" >
        <div class="dice-log "></div>
        <div class="dice-prompt"  ><a style="font-size: 28PT;color: #fff;">欢迎来到KTOOL在线投注平台</a></div>
    </div>-->
    <!-- dice nav  end-->
    <!--banner start -->
    <div id="myCarousel" class="carousel slide edit" id="index_slide" field="index_slide" rel="page" >
        <!-- Carousel items  start-->
       <!-- <div class="carousel-inner">
            <div class="active item" style="background-image:url('<?php /*print TEMPLATE_URL; */?>/images/gambling-587996_1920.png');no-repeat; background-size:100% 100%;"></div>
            <div class="item" style="background-image:url('<?php /*print TEMPLATE_URL; */?>/images/list_image1.jpg');no-repeat; background-size:100% 100%;">
        </div>-->
        <!-- Carousel items  end-->
        <!-- Carousel nav  start-->
     <!--   <div class="carousel-control right" href="#myCarousel" data-slide="next"></div>
        <div class="carousel-control left" href="#myCarousel" data-slide="prev"></div>-->
        <!-- Carousel nav  end-->
        <module
            type="pictures"
            content-id="<?php echo PAGE_ID; ?>"
            template="bootstrap_carousel"
            id="bootstrap_carousel_id2"
            handle_empty='1'

        />
    </div>
    <!--banner end -->
    <!--热门游戏 start -->
    <script>
    	$(document).ready(function(){
    		$(".hotPlay-list li").each(function(){
                $(this).mouseover(function(){
                    if($(this).hasClass("hotPlay-list")){
                        return;
                    }else{
                        $(".hotPlay-list li").each(function(){
                            $(this).removeClass("sel-hotPlay");
                            $(this).find(".hotPlay-info").removeClass("sel-hotPlay-info");
                            $(this).find(".hotPlay-info .hotPlay-info-right img").attr("src","<?php print TEMPLATE_URL; ?>images/down.png");
                            $(this).find("img:first").css({"width":"268px","height":"245px"});
                        });
                        $(this).addClass("sel-hotPlay");
                        $(this).find(".hotPlay-info").addClass("sel-hotPlay-info");
                        $(this).find(".hotPlay-info .hotPlay-info-right img").attr("src","<?php print TEMPLATE_URL; ?>images/down1.png");
                        $(this).find("img:first").css({"width":"265px","height":"272px"});
                    }
                });

            });
    	});
    </script>
    <div class="hotPlay">
        <div class="hotPlay-logo edit" field="index_hotPlay_bg" id="index_hotPlay_bg" rel='page'></div>
        <!--热门游戏列表 start -->
        <ul class="hotPlay-list edit"  field="index_hotPlay_list" id="index_hotPlay_list" rel='page'>
            <li>
                <img  class="element" src="<?php print TEMPLATE_URL; ?>/images/list_image1.jpg"/>
                <div class="hotPlay-info">
                    <div class="hotPlay-info-left">
                        <div class="hotPlay-name">
                            <a>棋牌娱乐</a>
                        </div>
                        <div class="hotPlay-type">
                            <a >CHESS</a>
                        </div>
                    </div>
                    <div class="hotPlay-info-right">
                        <img src="<?php print TEMPLATE_URL; ?>/images/down.png"/>
                    </div>
                </div>
            </li>
            <li>
                <img  class="element" src="<?php print TEMPLATE_URL; ?>/images/list_image2.jpg"/>
                <div class="hotPlay-info">
                    <div class="hotPlay-info-left">
                        <div class="hotPlay-name">
                            <a>彩票游戏</a>
                        </div>
                        <div class="hotPlay-type">
                            <a>LOTTERY</a>
                        </div>
                    </div>
                    <div class="hotPlay-info-right">
                        <img src="<?php print TEMPLATE_URL; ?>/images/down.png"/>
                    </div>
                </div>
            </li>
            <li class="sel-hotPlay" >
                <img class="element" src="<?php print TEMPLATE_URL; ?>/images/list_image3.jpg"/>
                <div class="hotPlay-info sel-hotPlay-info">
                    <div class="hotPlay-info-left">
                        <div class="hotPlay-name">
                            <a>真人视讯<a>
                        </div>
                        <div class="hotPlay-type">
                            <a> LIVE CASINO</a>
                        </div>
                    </div>
                    <div class="hotPlay-info-right">
                        <img src="<?php print TEMPLATE_URL; ?>/images/down1.png"/>
                    </div>
                </div>
            </li>
            <li>
                <img class="element" src="<?php print TEMPLATE_URL; ?>/images/list_image4.jpg"/>
                <div class="hotPlay-info">
                    <div class="hotPlay-info-left">
                        <div class="hotPlay-name">
                            <a>电子游艺</a>
                        </div>
                        <div class="hotPlay-type">
                            <a>ELECTRONIC</a>
                        </div>
                    </div>
                    <div class="hotPlay-info-right">
                        <img src="<?php print TEMPLATE_URL; ?>/images/down.png"/>
                    </div>
                </div>
            </li>
            <li >
                <img class="element" src="<?php print TEMPLATE_URL; ?>/images/list_image5.jpg"/>
                <div class="hotPlay-info">
                    <div class="hotPlay-info-left">
                        <div class="hotPlay-name">
                            <a>皇冠体育</a>
                        </div>
                        <div class="hotPlay-type">
                            <a>SPORTS</a>
                        </div>
                    </div>
                    <div class="hotPlay-info-right">
                        <img src="<?php print TEMPLATE_URL; ?>/images/down.png"/>
                    </div>
                </div>
            </li>
            <li>
                <img class="element" src="<?php print TEMPLATE_URL; ?>/images/list_image6.jpg"/>
                <div class="hotPlay-info">
                    <div class="hotPlay-info-left">
                        <div class="hotPlay-name">
                            <a>幸运28</a>
                        </div>
                        <div class="hotPlay-type">
                            <a> LUCKY28</a>
                        </div>
                    </div>
                    <div class="hotPlay-info-right">
                        <img src="<?php print TEMPLATE_URL; ?>/images/down.png"/>
                    </div>
                </div>
            </li>
        </ul>
        <!-- 热门游戏列表 end -->
    </div>
    </div>
    <!--热门游戏 end -->
    <!--关于我们 start -->
    <div class="about-us edit" field="about_us_list_bg" id="about_us_list_bg" rel='page'>
        <div class="about-us-prompt">
            关于我们<br/>
            <label style="font-size: 30px;color: #fff;">ABOUT US</label>
        </div>
        <!--关于我们logo列表 start -->
        <ul class="about-us-list">
            <li class="about-us-list1">
                <module type="beforeafter"  id="about-us-list1"  onmouseover_img="http://kime.cms.com/userfiles/templates/templates1/images/Shape_22.png" onmouseleave_img="http://kime.cms.com/userfiles/templates/templates1/images/Shape_22_1.png" dealut_content="合作伙伴"/>
            </li>
            <li class="about-us-list2">
                <module type="beforeafter"  id="about-us-list2"  onmouseover_img="http://kime.cms.com/userfiles/templates/templates1/images/Shape_23.png" onmouseleave_img="http://kime.cms.com/userfiles/templates/templates1/images/Shape_23_1.png" dealut_content="运营咨询"/>
            </li>
            <li class="about-us-list3">
                <module type="beforeafter"  id="about-us-list3"  onmouseover_img="http://kime.cms.com/userfiles/templates/templates1/images/Shape_24.png" onmouseleave_img="http://kime.cms.com/userfiles/templates/templates1/images/Shape_24_1.png" dealut_content="添加收藏"/>
            </li>
            <li class="about-us-list4">
                <module type="beforeafter"  id="about-us-list4"  onmouseover_img="http://kime.cms.com/userfiles/templates/templates1/images/compas.png" onmouseleave_img="http://kime.cms.com/userfiles/templates/templates1/images/compas_1.png" dealut_content="公司地址"/>
            </li>
            <li class="about-us-list5">
                <module type="beforeafter"  id="about-us-list5"  onmouseover_img="http://kime.cms.com/userfiles/templates/templates1/images/SPEECH_BUBBLES.png" onmouseleave_img="http://kime.cms.com/userfiles/templates/templates1/images/SPEECH_BUBBLES_1.png" dealut_content="欢迎留言"/>
            </li>
            <li class="about-us-list6">
                <module type="beforeafter"  id="about-us-list6"  onmouseover_img="http://kime.cms.com/userfiles/templates/templates1/images/STAR.png" onmouseleave_img="http://kime.cms.com/userfiles/templates/templates1/images/STAR_1.png" dealut_content="业界认可"/>
            </li>
        </ul>
        <!--关于我们logo列表 end -->
    </div>
    <!--关于我们 end -->
    <!--平台代理优势 start -->
    <div class="platform-agent edit" field="index_agent_edit1" id="index_agent_edit1" rel='page'>
        <div class="platform-agent-prompt">
            <div class="agent_pri"><a>平台代理优势</a></div>
            <label>KTOOL在线娱乐平台十分重视客户的反馈真实玩家的声音</label>
        </div>
        <!--平台代理优势列表 start -->
        <dl class="platform-agent-list">
            <dt style="width:299px;">
            <div class="platform-agent-list-item">
                <div class="platform-agent-list-itemLine"></div>
                <label style="width: 170px;height: 56px;margin-top: 43px;padding-left: 10px;text-align: center;">新用户首次注册送888金卷</label>
            </div>
            </dt>
            <dt>
            <div class="platform-agent-list-item">
                <div class="platform-agent-list-itemLine"></div>
                <table border="0">
                    <tr>
                        <td td rowspan="3" width="30%" style="text-align: center;"><img src="<?php print TEMPLATE_URL; ?>images/house.png"/></td>
                        <td style="text-align: left;vertical-align: bottom;" width="32%">便携的银行服务</td>
                        <td td rowspan="3"><label style="font-size: 38px;margin-top: 60px;">28</label><label style="font-size: 16px;">家</label></td>
                    </tr>
                    <tr>
                        <td><img src="<?php print TEMPLATE_URL; ?>images/VISA1.png" style="width:55px;height:16px;"/><img src="<?php print TEMPLATE_URL; ?>images/VISA2.png" style="width:83px;height:16px;"/></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;font-size: 13px;">目前我们支付机构有：</td>
                    </tr>
                </table>
            </div>
            </dt>
            <dt>
            <div class="platform-agent-list-item">
                <div class="platform-agent-list-itemLine" style="display:block"></div>
                <table border="0">
                    <tr>
                        <td td rowspan="3" width="38%" style="text-align: center;"><img src="<?php print TEMPLATE_URL; ?>images/bank_cards.png" style="width:50px;height:45px;"/></td>
                        <td style="text-align: left;vertical-align: bottom;">存款到账</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px;">存款平均7秒急速到账</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;font-size: 13px;position: relative;"><img src="<?php print TEMPLATE_URL; ?>images/progress2.png" style="width: 203px;"><img src="<?php print TEMPLATE_URL; ?>images/progress1.jpg" class="progress1"></td>
                    </tr>
                </table>
            </div>
            </dt>
            <dt>
            <div class="platform-agent-list-item">
                <div class="platform-agent-list-itemLine"></div>
                <table border="0">
                    <tr>
                        <td td rowspan="3" width="38%" style="text-align: center;"><img src="<?php print TEMPLATE_URL; ?>images/card_in_use.png" style="width:60px;height:40px;"/></td>
                        <td style="text-align: left;vertical-align: bottom;">存款到账</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px;">取款平均25秒急速到账</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;font-size: 13px;position: relative;"><img src="<?php print TEMPLATE_URL; ?>images/progress2.png" style="width: 203px;"><img src="<?php print TEMPLATE_URL; ?>images/progress1.jpg" class="progress1"></td>
                    </tr>
                </table>
            </div>
            </dt>
            <dt style="margin: 0;width: 125px;background-position-x: left;">
            <div class="platform-agent-list-item">
                <div class="platform-agent-list-itemLine"></div>
                <img src="<?php print TEMPLATE_URL; ?>images/user1.png" style="width: 58px;height: 57px;margin-top: 41px;margin-left: 30px;">
            </div>
            </dt>
        </dl>
        <!--平台代理优势列表 end -->
    </div>
    <!--平台代理优势 end -->
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>