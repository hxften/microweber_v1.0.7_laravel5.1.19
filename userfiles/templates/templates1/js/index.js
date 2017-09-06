$(document).ready(function(){
	//header部分左侧是个图标控制
	$(".navbar-brand1 li ").each(function(){
		$(this).mouseover(function(){
			var index = $(this).index();
			if(index == 0){
			  $(".logo_f").attr("src","images/logo_f1.png");
			  $(".logo_b").attr("src","images/logo_bird.png");
			  $(".logo_t").attr("src","images/logo_t.png");
			  $(".logo_c").attr("src","images/logo_circle.png");
			}else if(index == 1){
			  $(".logo_f").attr("src","images/logo_f.png");
			  $(".logo_b").attr("src","images/logo_bird1.png");
			  $(".logo_t").attr("src","images/logo_t.png");
			  $(".logo_c").attr("src","images/logo_circle.png");
			}else if(index == 2){
			  $(".logo_f").attr("src","images/logo_f.png");
			  $(".logo_b").attr("src","images/logo_bird.png");
			  $(".logo_t").attr("src","images/logo_t1.png");
			  $(".logo_c").attr("src","images/logo_circle.png");
			}else if(index == 3){
			  $(".logo_f").attr("src","images/logo_f.png");
			  $(".logo_b").attr("src","images/logo_bird.png");
			  $(".logo_t").attr("src","images/logo_t.png");
			  $(".logo_c").attr("src","images/logo_circle1.png");
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
	//最热门游戏列表事件添加
	$(".hotPlay-list li").each(function(){
		$(this).mouseover(function(){
			if($(this).hasClass("hotPlay-list")){
				return;
			}else{
				$(".hotPlay-list li").each(function(){
					$(this).removeClass("sel-hotPlay");
					$(this).find(".hotPlay-info").removeClass("sel-hotPlay-info");
					$(this).find(".hotPlay-info .hotPlay-info-right img").attr("src","images/down.png");
					$(this).find("img:first").css({"width":"268px","height":"245px"});
				});
				$(this).addClass("sel-hotPlay");
				$(this).find(".hotPlay-info").addClass("sel-hotPlay-info");
				$(this).find(".hotPlay-info .hotPlay-info-right img").attr("src","images/down1.png");
				$(this).find("img:first").css({"width":"265px","height":"272px"});
			}	
		});
	
	});
	
	//关于我们事件添加
	$(".about-us-list li").each(function(){
		$(this).mouseover(function(){
		   var index = $(this).index();
		   $(".about-us-list li .about-us-listItem-name").css("color","#fff");
			if(index == 0){
			  $(".about-us-list1 img").attr("src","images/Shape_22_1.png");
			  $(".about-us-list1 .about-us-listItem-name").css("color","#23b39a");
			  $(".about-us-list2 img").attr("src","images/Shape_23.png");
			  $(".about-us-list3 img").attr("src","images/Shape_24.png");
			  $(".about-us-list4 img").attr("src","images/compas.png");
			  $(".about-us-list5 img").attr("src","images/SPEECH_BUBBLES.png");
			  $(".about-us-list6 img").attr("src","images/STAR.png");
			}else if(index == 1){
			  $(".about-us-list1 img").attr("src","images/Shape_22.png");
			  $(".about-us-list2 img").attr("src","images/Shape_23_1.png");
			  $(".about-us-list2 .about-us-listItem-name").css("color","#23b39a");
			  $(".about-us-list3 img").attr("src","images/Shape_24.png");
			  $(".about-us-list4 img").attr("src","images/compas.png");
			  $(".about-us-list5 img").attr("src","images/SPEECH_BUBBLES.png");
			  $(".about-us-list6 img").attr("src","images/STAR.png");
			}else if(index == 2){
			  $(".about-us-list1 img").attr("src","images/Shape_22.png");
			  $(".about-us-list2 img").attr("src","images/Shape_23.png");
			  $(".about-us-list3 img").attr("src","images/Shape_24_1.png");
			  $(".about-us-list3 .about-us-listItem-name").css("color","#23b39a");
			  $(".about-us-list4 img").attr("src","images/compas.png");
			  $(".about-us-list5 img").attr("src","images/SPEECH_BUBBLES.png");
			  $(".about-us-list6 img").attr("src","images/STAR.png");
			}else if(index == 3){
			  $(".about-us-list1 img").attr("src","images/Shape_22.png");
			  $(".about-us-list2 img").attr("src","images/Shape_23.png");
			  $(".about-us-list3 img").attr("src","images/Shape_24.png");
			  $(".about-us-list4 img").attr("src","images/compas_1.png");
			  $(".about-us-list4 .about-us-listItem-name").css("color","#23b39a");
			  $(".about-us-list5 img").attr("src","images/SPEECH_BUBBLES.png");
			  $(".about-us-list6 img").attr("src","images/STAR.png");
			}else if(index == 4){
			  $(".about-us-list1 img").attr("src","images/Shape_22.png");
			  $(".about-us-list2 img").attr("src","images/Shape_23.png");
			  $(".about-us-list3 img").attr("src","images/Shape_24.png");
			  $(".about-us-list4 img").attr("src","images/compas.png");
			  $(".about-us-list5 img").attr("src","images/SPEECH_BUBBLES_1.png");
			  $(".about-us-list5 .about-us-listItem-name").css("color","#23b39a");
			  $(".about-us-list6 img").attr("src","images/STAR.png");
			}else if(index == 5){
			  $(".about-us-list1 img").attr("src","images/Shape_22.png");
			  $(".about-us-list2 img").attr("src","images/Shape_23.png");
			  $(".about-us-list3 img").attr("src","images/Shape_24.png");
			  $(".about-us-list4 img").attr("src","images/compas.png");
			  $(".about-us-list5 img").attr("src","images/SPEECH_BUBBLES.png");
			  $(".about-us-list6 img").attr("src","images/STAR_1.png");
			  $(".about-us-list6 .about-us-listItem-name").css("color","#23b39a");
			}
		});
	});
	
	//平台代理优势控制
	$(".platform-agent-list dt").each(function(){
		$(this).mouseover(function(){
			$(".platform-agent-list dt .platform-agent-list-itemLine").hide();
			$(this).find(".platform-agent-list-itemLine").show();
		});	
	})
});

function selNav(obj){
	$(".navbar-nav li").each(function(){
		$(this).removeClass("nav_active");
	});
	$(obj).addClass("nav_active");
}