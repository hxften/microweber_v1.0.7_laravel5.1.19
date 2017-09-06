$(document).ready(function(){
	//控制顶部导航鼠标经过事件
	$(".navbar-nav li").each(function(){
	    $(this).click(function(){
	    	SelNav(this);	
		});	
	});
});

function SelNav(obj){
	if($(obj).hasClass("nav_active")){
		return;
	}else{
		$(".navbar-nav li").removeClass("nav_active")
		$(obj).addClass("nav_active");
	}
}