<?php

/*

  type: layout
  content_type: dynamic
  name: news
  position: 5
  description: news
  tag: news

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<!--首页 start-->
<div class="tab-pane fade in active" id="home">
  <!--公司图片 start-->
  <div class="edit"  id="news_edit_bg" field="news_edit_bg" rel="page">
    <img src="<?php print TEMPLATE_URL; ?>/images/gma-bld.jpg" >
  </div>
  <!--公司图片 end-->
  <!--新闻部分 start-->
  <div class="news-content" >
    <div class="news-content-left" >
        <!--新闻列表 start-->
        <div class="edit">
          <a style="font-family:Microsoft YaHei;font-weight: bold;color: #00a290;font-size: 21px;vertical-align: middle;">新闻中心</a>
          <div style="height:30px;">
            <div style="width:100%;height:6px;background-color:#d3d3d3;position: relative;">
              <img src="<?php print TEMPLATE_URL; ?>/images/news-down.png" style="width:87px;position: absolute;top: 0px;">
            </div>
          </div>
          <img src="<?php print TEMPLATE_URL; ?>/images/news-arrow.png">
          <a style="font-family:Microsoft YaHei;color: #8a807a;font-size: 15px;margin-left: 8px;vertical-align: middle;">行业新闻</a>
          <img src="<?php print TEMPLATE_URL; ?>/images/news-arrow2.png" style="margin-left: 10px;cursor:pointer">
          <div style="width:100%;height:1px;background-color:#d3d3d3;margin-top: 10px;">
          </div>
          <module content-id="<?php print PAGE_ID; ?>" type="posts"  template="left" limit="15"/>
        </div>
      <!--新闻列表 end-->
    </div>

    <div class="news-middle-line"></div>
    <div class="news-content-right">
      <!--新闻排行 start-->
      <div class="edit">
        <img src="<?php print TEMPLATE_URL; ?>/images/news-arrow.png">
        <a style="font-family:Microsoft YaHei;color: #2a323e;font-size: 20px;margin-left: 8px;vertical-align: middle;font-weight: bold;">新闻排行</a>
        <img  src="<?php print TEMPLATE_URL; ?>/images/news-arrow2.png" style="margin-left: 10px;cursor:pointer">
        <div style="width:100%;height:1px;background-color:#d3d3d3;margin-top: 5px;">
        </div>
        <module content-id="<?php print PAGE_ID; ?>" type="posts"  template="right_top" limit="6"/>
      </div>
      <!--新闻排行 end-->
      <!--图片新闻 start-->
      <div class="edit">
        <div style="margin-top:45px;">
          <img src="<?php print TEMPLATE_URL; ?>/images/news-arrow.png">
          <a style="font-family:Microsoft YaHei;color: #2a323e;font-size: 20px;margin-left: 8px;vertical-align: middle;font-weight: bold;">图片新闻</a>
          <img src="<?php print TEMPLATE_URL; ?>/images/news-arrow2.png" style="margin-left: 10px;cursor:pointer">
        </div>
        <div style="width:100%;height:1px;background-color:#d3d3d3;margin-top: 5px;">
        </div>
        <module content-id="<?php print PAGE_ID; ?>" type="posts"  template="right_bottom" limit="4"/>
      </div>
      <!--图片新闻 end-->
      <!--要闻回顾 start-->
      <div style="margin-top:45px;">
        <div class="edit" >
          <img src="<?php print TEMPLATE_URL; ?>/images/news-arrow.png">
          <a  style="font-family:Microsoft YaHei;color: #2a323e;font-size: 20px;margin-left: 8px;vertical-align: middle;font-weight: bold;">要闻回顾</a>
          <img src="<?php print TEMPLATE_URL; ?>/images/news-arrow2.png" style="margin-left: 10px;cursor:pointer">
          <module content-id="<?php print PAGE_ID; ?>" type="posts"  template="major_news" limit="4" />
        </div>
      </div>
      <!--要闻回顾 end-->
    </div>
  </div>
  <!--新闻部分  end-->
  <!--logo list start-->
  <div class="edit" id="news_icon_list_edit1" field="news_icon_list_edit1" rel="page">
      <div class="icon-list" style="margin-top: 63px;">
        <div class="icon-list-content">
          <img src="<?php print TEMPLATE_URL; ?>/images/AG.png">
          <img src="<?php print TEMPLATE_URL; ?>/images/BBIN_logo.png">
          <img src="<?php print TEMPLATE_URL; ?>/images/en.png">
          <img src="<?php print TEMPLATE_URL; ?>/images/playtech.png">
          <img src="<?php print TEMPLATE_URL; ?>/images/basha.png">
          <img src="<?php print TEMPLATE_URL; ?>/images/riental.png">
        </div>
      </div>
  </div>
  <!--logo list end-->
</div>
<!--首页 end-->
<?php include TEMPLATE_DIR. "footer.php"; ?>
