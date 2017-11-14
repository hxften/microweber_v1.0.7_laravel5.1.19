<?php include TEMPLATE_DIR. "header.php"; ?>
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
	<style>
		.news-content dl dt{
			height:auto;
		}						
	</style>
	<!-- banner end -->
	<!--新闻列表部分 start-->
		<div class="news-content" data-mw="main">
		      <div class="news-content-middle edit" id="newinner"  rel="content" field="contentnew">
			        <div class="news-content-left">
			        	<div class="line"></div>
			        	<div class="news_title"><a>大陆微信赌博成产业链 赌资动辄上千万</a></div>
			        	<div class="news-time-content">
			        		<a>时间：2016-08-16</a>
			        		<a class="news-resource">来源：中新网</a>
			        	</div>
			        	<div class="news-dashed-line"></div>
			        	<div class="newsContent">
			        		<p>近年来，微信〝抢红包〞游戏演变成一种新型的网路赌场。玩家沉浸在各种眼花缭乱的赌博游戏中，一夜间几千上万元就没了。在微信赌博群里，不法份子暗箱操作，分工明确，已形成一条完整的微信赌博产业链。从2015年起，大陆各地相继破获一批利用微信赌博案件，赌资动辄上千万元。
据陆媒报导，日前有记者通过暗访加入微信赌博群，揭示了其中的秘密。记者发现有一种〝拼手气红包〞玩法：庄家1元分3个红包发，以中间红包金额的尾数作为开奖结果，参与者可以买单双、大小、数字、豹子，赔率从两倍到十倍不等。
一个玩家陈翔（化名）称，〝玩这个有瘾，每次我想着不玩的时候，总是觉得自己还能赢一把。就是这种赌博心态让我越输越多。这个月一共3500元的工资，全都输给了这个群，不知道接下来的日子怎么过。〞
另外一种类似于彩票买数字的玩法，玩家可以从0到27数字中选出多个进行购买。这种游戏5分钟一开奖，根据不同群的规则，玩家一局可下注数千元，赔率最高可达12倍。</p>
<p>在赌博群里，玩家24小时不停下注，眨眼间，成千上万的赌资就已蒸发。</p>
<p>一名22岁大四女生木子称，在一个微信红包群玩〝抢红包〞游戏，发了206万多元的微信红包，抢了196万元红包，输了10万多元。为了还债她借了〝高利贷〞，父亲也卖掉爱车。
还有庄家向记者爆料称，庄家十有八九都作弊，赌博群中流行的游戏，都可在网上找到相应软体进行作弊。〝每款软体都是据庄家不同要求定制的，庄家就是靠着这些软体才能赚钱〞。
而且不是所有庄家都会遵守游戏规则，有些庄家还会使用一些〝黑招〞赚钱，玩家赢钱后被庄家拉黑的情况也时常出现。玩家吃了哑巴亏，也没地方说理去。
玩家大龙（化名）告诉记者，〝我用300元的本金赚到了12000元，对方答应我如数兑付。我喊了‘回宝’（提现）后，却一直没有收到钱。〞随后，对方发来一张转账截图说，账已经转了，可不是转给他了，然后就被踢出群。
甚至有一些占据主动权的庄家不会兑付账户金额，卷款跑路，这样的赌群被称作〝鲨鱼群〞。鲨鱼群都是进行账户充值的，群内正常运行一段时间，等到账面总分累积较多时，庄家会直接封盘跑路。
不仅如此，卖家还向玩家出售全自动操盘记账软体赚钱，也有职业中介拉玩家入群获得提成，更有庄家撺掇赌客开设新局以求抽成。微信赌博已形成产业链模式。
从去年开始，利用微信红包赌博在大陆各地蔓延，愈演愈烈。与传统赌博相比，它更隐蔽、随机、灵活，也更难被发现，危害性更大。在大陆公安部门破获的微信赌博案件中，赌资动辄达上千万元。
			        		</p>
			        	</div>
			        </div>
			        <div class="news-content-right">
						<dl>
						<?php 
							// 返回所有分类
							$categories = content_categories(PAGE_ID); 

							if(!empty($categories)){
							if(count($categories) > 3){
								$categories = array_slice($categories,0,3);
							}

							foreach($categories as $key => $category){
						?>
							<dt>
								<div class="line"></div>
								<div class="news-title-content">
									<div class="news-type"><a><?php print $category['title'];?></a></div>
									<div class="more"><a class="service-item-des" href="/new">更多</a></div>
								</div>
								<module content-id="<?php print PAGE_ID; ?>" type="posts" template="coollife" limit="6" data-category-id="<?php print $category['id'];?>" data-show="thumbnail,title,description,created_at" hide-paging="y" />
							</dt>
							<?php } ?>
						<?php } ?>
						</dl>
					</div>
				  </div>
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
<?php include TEMPLATE_DIR. "footer.php"; ?>