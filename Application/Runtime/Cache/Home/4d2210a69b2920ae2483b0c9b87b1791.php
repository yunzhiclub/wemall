<?php if (!defined('THINK_PATH')) exit();?><div class="col-xs-12 slider card card-nomb yz-no-padding" style="visibility: visible;">
<div id="focus" class="focus">
    <div class="hd">
      <ul>
      </ul>
    </div>
    <div class="bd">
      <ul>
      <?php if(is_array($slideInfo)): foreach($slideInfo as $key=>$slide): ?><li><a href=<?php if(($slide["is_good"]) == "0"): ?>"<?php echo ($goodsDetailUrl); ?>?goodsId=<?php echo ($slide["product_id"]); ?>"<?php else: ?>"<?php echo ($slide["url"]); ?>"<?php endif; ?> title=""><img name="ad_img" src="<?php echo ($slide["path"]); ?>" alt="" /></a></li><?php endforeach; endif; ?>
      </ul>
    </div>
</div>
    <script type="text/javascript">
	TouchSlide({ 
		slideCell:"#focus",
		titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
		mainCell:".bd ul",
		delayTime:600,
		interTime:4000,
		effect:"leftLoop", 
		autoPlay:true,//自动播放
		autoPage:true, //自动分页
		switchLoad:"_src" //切换加载，真实图片路径为"_src" 
	});
	</script>
    <!-- banner轮播End -->
</div>