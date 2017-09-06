<?php

/*

  type: layout
  content_type: static
  name: Advantage
  position: 11
  description: Home layout

*/

?>

<?php include THIS_TEMPLATE_DIR. "header.php"; ?>
<style>
/* entire container, keeps perspective */
.flip-container {
	perspective: 1000;
	float: left;
}
	/* flip the pane when hovered */
	.flip-container:hover .flipper, .flip-container.hover .flipper {
		transform: rotateY(180deg);
	}

.flip-container, .front, .back {
	width: 311px;
	height: 311px;
}

/* flip speed goes here */
.flipper {
	transition: 0.6s;
	transform-style: preserve-3d;

	position: relative;
}

/* hide back of pane during swap */
.front, .back {
	backface-visibility: hidden;

	position: absolute;
	top: 0;
	left: 0;
}

/* front pane, placed above back */
.front {
	z-index: 2;
}

/* back, initially hidden pane */
.back {
	transform: rotateY(180deg);
}
</style>
<style type="text/css">
.main {
	width: 200px;
	margin: 0 auto;
}

.item1 {
	height: 500px;
	position: relative;
	padding: 30px;
	text-align: center;
	-webkit-transition: top 1.2s linear;
	transition: top 1.2s linear;
}

.item1 .kodai {
	position: absolute;
	bottom: 0;
	cursor: pointer;
}

	.item1 .kodai .full {
		display: block;
	}

	.item1 .kodai .empty {
		display: none;
	}

.item1 .clipped-box {
	display: none;
	position: absolute;
	bottom: 40px;
	left: 137px;
	height: 540px;
	width: 980px;
}

.item1 .clipped-box img {
	position: absolute;
	top: auto;
	left: 0;
	bottom: 0;
	-webkit-transition: -webkit-transform 1.4s ease-in, background 0.3s ease-in;
	transition: transform 1.4s ease-in;
}
.carousel-inner-content{
	margin-top:84px;
}
</style>
<script>
$(document).ready(function () {
	var height = $(".cion_field").offset().top - 100;
	var flag = true;
	$(window).scroll(function(){
    if($(window).scrollTop() > height && flag){
       	showCion();
       	flag = false;
      }
   });
   
    (genClips = function () {
        $t = $('.item1');
        var amount = 5;
        var width = $t.width() / amount;
        var height = $t.height() / amount;
        var totalSquares = Math.pow(amount, 2);
        var y = 0;
        var index = 1;
        for (var z = 0; z <= (amount * width) ; z = z + width) {
            $('<img class="clipped" src="{TEMPLATE_URL}images/cion' + index + '.png" />').appendTo($('.item1 .clipped-box'));
            if (z === (amount * width) - width) {
                y = y + height;
                z = -width;
            }
            if (index >= 5) {
                index = 1;
            }
            index++;
            if (y === (amount * height)) {
                z = 9999999;
            }
        }
    })();
    function rand(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    var first = false,
        clicked = false;
    // On click
    //$('.item1 div.kodai').on('click', function () {
   function showCion(){
        if (clicked === false) {
            setTimeout(function(){
	            $('.full').css({
	                'display': 'none'
	            });
	            $('.empty').css({
	                'display': 'block'
	            });
            },1500);
            clicked = true;

            $('.item1 .clipped-box').css({
                'display': 'block'
            });
            // Apply to each clipped-box div.
            $('.clipped-box img').each(function () {
                var v = rand(120, 90),
                    angle = rand(80, 89), 
                    theta = (angle * Math.PI) / 180, 
                    g = -9.8; 

                // $(this) as self
                var self = $(this);
                var t = 0,
                    z, r, nx, ny,
                    totalt =10;
                var negate = [1, -1, 0],
                    direction = negate[Math.floor(Math.random() * negate.length)];

                var randDeg = rand(-5, 10),
                    randScale = rand(0.9, 1.1),
                    randDeg2 = rand(30, 5);

                // And apply those
                $(this).css({
                    'transform': 'scale(' + randScale + ') skew(' + randDeg + 'deg) rotateZ(' + randDeg2 + 'deg)'
                });

                // Set an interval
                z = setInterval(function () {
                    var ux = (Math.cos(theta) * v) * direction;
                    var uy = (Math.sin(theta) * v) - ((-g) * t);
                    nx = (ux * t);
                    ny = (uy * t) + (0.25 * (g) * Math.pow(t, 2));
                    if (ny < -40) {
                        ny = -40;
                    }
                    //$("#html").html("g:" + g + "bottom:" + ny + "left:" + nx + "direction:" + direction);
                    $(self).css({
                        'bottom': (ny) + 'px',
                        'left': (nx) + 'px'
                    });
                    // Increase the time by 0.10
                    t = t + 0.10;

                    //跳出循环
                    if (t > totalt) {
                        clicked = false;
                        first = true;
                        clearInterval(z);
                    }
                }, 20);
            });
        }
    }
    r = setInterval(function () {
        if (first === true) {
            $('.empty').addClass("Shake");//晃动空袋子
            //TODO:空袋子晃动几下 就弹出 奖项框
            first = false;
        }
    }, 300);
});
</script>
	<!-- banner start -->
	<div id="myCarousel" class="carousel slide">
  
	<!-- 轮播（Carousel）start-->
		<div class="carousel-inner edit" id="advantage"  rel="page" field="carouseladvantage">
			<div class="active item">
				<div class="carousel-inner-content">
					<div class="advantage-carousel">
						<img class="advantage-carousel-title" src="{TEMPLATE_URL}images/globle.png">
						<div class="advantage-carousel-title2">
							<a>部分地区仅剩</a>
							<a class="advantage-carousel-span">99</a>
							<a>个名额</a>
						</div>
						<img src="{TEMPLATE_URL}images/money-bag.png" class="money-bag">
						<div class="apply-now"><a>立即申请</a></div>
					</div>
				</div>
			</div>
		</div>
		<!-- 轮播（Carousel）end-->

	</div> 
	<!-- banner end -->
	<div class="ad-content">
			<div class="edit advantage-content" id="advantageedit"  rel="page" field="contentadvantage">
				<div class="advantage-content-title1">
					<div class="step"><a>1</a></div>
					<a>如何实现人生终极梦想</a>
				</div>
				<div class="icon-content">
				<table><tr><td>
				<div class="flip-container" ontouchstart="this.classList.toggle('hover');">
					<div class="flipper">
						<div class="front">
							<!-- 前面内容 -->
							<div class="front pic">	<img src="{TEMPLATE_URL}images/circle1.png" /></div>
						</div>
						<div class="back">
							<!-- 背面内容 -->
							<div class="back-logo"></div>
								<div class="back-title"><img src="{TEMPLATE_URL}images/circle1_1.png" /></div>
							</div>
					</div>
				</div></td><td>
				<div class="flip-container" ontouchstart="this.classList.toggle('hover');">
					<div class="flipper">
						<div class="front">
							<!-- 前面内容 -->
							<div class="front pic">	<img src="{TEMPLATE_URL}images/circle2.png" /></div>
						</div>
						<div class="back">
							<!-- 背面内容 -->
							<div class="back-logo"></div>
								<div class="back-title"><img src="{TEMPLATE_URL}images/circle2_1.png" /></div>
							</div>
					</div>
				</div>
				</td><td>
				<div class="flip-container" ontouchstart="this.classList.toggle('hover');">
					<div class="flipper">
						<div class="front">
							<!-- 前面内容 -->
							<div class="front pic">	<img src="{TEMPLATE_URL}images/circle3.png" /></div>
						</div>
						<div class="back">
							<!-- 背面内容 -->
							<div class="back-logo"></div>
								<div class="back-title"><img src="{TEMPLATE_URL}images/circle3_1.png" /></div>
							</div>
					</div>
				</div></td></tr></table>
			</div>
			<div class="advantage-content-title2">
				<div class="step"><a>2</a></div>
				<a>屌丝逆袭你只需要1个财富的入口</a>
			</div>
			<div class="icon-content2"><img src="{TEMPLATE_URL}images/money1.png"/></div>
			<div class="cion_field">
				<div class="advantage-content-title2">
					<div class="step"><a>3</a></div>
					<a>加入我们成为代理</a>
				</div>
						<div class="flash-content">
							<div class="item1">
							<div class="kodai">
								<img src="{TEMPLATE_URL}images/kd2.png" class="full" />
								<img src="{TEMPLATE_URL}images/kd1.png" class="empty" />
							</div>
							<div class="clipped-box">

							</div>
						</div>
				</div>			
			</div>
		</div>
		<!--logo list start-->
		<div class="icon-list edit iconnewedit" id="iconnewedit" rel="page" field="iconlist">
			<div class="icon-list-content element">
				<img src="{TEMPLATE_URL}images/AG.png">
				<img src="{TEMPLATE_URL}images/BBIN_logo.png">
				<img src="{TEMPLATE_URL}images/en.png">
				<img src="{TEMPLATE_URL}images/playtech.png">
				<img src="{TEMPLATE_URL}images/basha.png">
				<img src="{TEMPLATE_URL}images/riental.png">
			</div>
		</div>
	</div>
	<!--logo list end-->
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>