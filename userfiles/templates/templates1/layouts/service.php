<?php

/*

  type: layout
  content_type: dynamic
  name: Service
  position: 5
  description: Service page
  tag: Service page

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>
<!--首页 start-->
<div class="tab-pane fade in active" id="home">
  <!--公司图片 start-->
  <div class="edit"id="service_bg" field="service_bg" rel="page" >
  <img  src="<?php print TEMPLATE_URL; ?>/images/gma-bld.jpg">
  </div>
  <!--公司图片 end-->
  <!--服务列表 start-->
  <div class="service-content edit" id="service_content2" field="service_content2" rel="page">
    <div>
    <ul>
      <li class="element" id="service_content_edit21">
        <img class="service1" src="<?php print TEMPLATE_URL; ?>/images/service1.png"/>
      <div style="margin-left: 44px;"><a class="service-item-name">快速存取款</a></div>
      <div style="margin-left: 44px;"><a class="service-item-des">众多的存取款方式并保障存取过程顺利及时</a></div>
      </li>
      <li class="element" id="service_content_edit22">
        <img class="service2" src="<?php print TEMPLATE_URL; ?>/images/service2.png"/>
      <div style="margin-left: 44px;"><a class="service-item-name">安全省心</a></div>
      <div style="margin-left: 44px;"><a class="service-item-des">使用中国银联特级加密系统</a></div>
      </li>
      <li class="element" id="service_content_edit23">
        <img class="service3" src="<?php print TEMPLATE_URL; ?>/images/service3.png"/>
      <div style="margin-left: 44px;"><a class="service-item-name">手续费全免</a></div>
      <div style="margin-left: 44px;"><a class="service-item-des">中国银联无缝对接，确保账号和资金安全</a></div>
      </li>
      </ul>
      </div>
    <div>
       <ul>
      <li class="element" id="service_content_edit24">
        <img class="service4" src="<?php print TEMPLATE_URL; ?>/images/service4.png"/>
      <div style="margin-left: 44px;"><a class="service-item-name">卓越的技术服务</a></div>
      <div style="margin-left: 44px;"><a class="service-item-des">最专业的技术团队，保障您全天安全游戏</a></div>
      </li>
      <li class="element" id="service_content_edit25">
        <img class="service5" src="<?php print TEMPLATE_URL; ?>/images/service5.png"/>
        <div style="margin-left: 44px;"><a class="service-item-name">实力雄厚</a></div>
        <div style="margin-left: 44px;"><a class="service-item-des">注册资金6000万与菲律宾首都马尼拉</a>
        </div>
      </li>
      <li class="element" id="service_content_edit26">
        <img class="service6" src="<?php print TEMPLATE_URL; ?>/images/service6.png"/>
        <div style="margin-left: 44px;"><a class="service-item-name">VIP制度</a></div>
        <div style="margin-left: 44px;"><a class="service-item-des">回馈广大用户，制定丰厚VIP奖励方案</a>
        </div>
      </li>
       </ul>
      </div>
    <div>
      <ul>
      <li class="element" id="service_content_edit27">
        <img class="service7" src="<?php print TEMPLATE_URL; ?>/images/service7.png"/>
        <div style="margin-left: 44px;"><a class="service-item-name">客户服务</a></div>
        <div style="margin-left: 44px;"><a class="service-item-des">7*24小时不间断服务，让您百分百满意</a>
        </div>
      </li>
      <li class="element" id="service_content_edit28">
        <img class="service7" src="<?php print TEMPLATE_URL; ?>/images/service7.png"/>
        <div style="margin-left: 44px;"><a class="service-item-name">客户服务3</a></div>
        <div style="margin-left: 44px;"><a class="service-item-des">7*24小时不间断服务，让您百分百满意</a>
        </div>
      </li>
      <li class="element" id="service_content_edit29">
        <img class="service7" src="<?php print TEMPLATE_URL; ?>/images/service7.png"/>
        <div style="margin-left: 44px;"><a class="service-item-name">客户服务3</a></div>
        <div style="margin-left: 44px;"><a class="service-item-des">7*24小时不间断服务，让您百分百满意</a></div>
      </li>
    </ul>
      </div>
  </div>
  <!--服务列表 end-->
<?php include TEMPLATE_DIR. "footer.php"; ?>
