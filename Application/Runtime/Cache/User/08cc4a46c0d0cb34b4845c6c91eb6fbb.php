<?php if (!defined('THINK_PATH')) exit();?>       <script type="text/javascript">
	var _hmt=_hmt||[];(function(){var e=document.createElement("script");
	var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();
	document.write('<div id="global_loading" style="width: 100%;height: 100%;position: fixed;top: 0;left: 0;background-color: #eee;z-index:999"><div style="width: 
100px;height: 75px;margin:auto;top:50%;position:relative"><span style="display:block;float:left;width:32px;height:32px;background:url(images/spacer.gif);margin-top:-
5px;"></span>&nbsp;&nbsp;加载中...</div></div>');
	</script>
<section>
<div class="pending-order">
	<!--订单列表begin-->
	<ul class="list">
                       <?php if(is_array($list)): foreach($list as $key=>$order): ?><li>
		    <div class="info">
				<a class="touch" href="<?php echo ($url); echo ($order["id"]); ?>">
                                    <div class="ddbh"><p class="id">订单编号：<?php echo ($order["id"]); ?></p><div class="xiangqing"><font color="#F7762E">【详情】</font></div><div 
class="clr"></div></div>
                                        <p class="price">订单状态：<strong>
                                            <?php if(($order["key"]) == "1"): ?>未支付<?php endif; ?>
                                            <?php if(($order["key"]) == "2"): ?>正在配送<?php endif; ?>
                                            <?php if(($order["key"]) == "3"): ?>正在获取物流<?php endif; ?>
                                            <?php if(($order["key"]) == "4"): ?>原产国配送中<?php endif; ?>
                                            <?php if(($order["key"]) == "5"): ?>国际配送中<?php endif; ?>
                                            <?php if(($order["key"]) == "6"): ?>已完成<?php endif; ?>
                                            </strong></p>
					<p class="date">商品总数：<?php echo ($order["total_count"]); ?></p>
                                        <p class="date">订单总额：<?php echo format_money($order[total_prices]); ?>元</p>
					<p class="shipped"><?php echo ($order_data["order_status"]); ?></p>
                    <span class="more"><span class="inner"></span></span>
				</a>
		    </div>
			<!--订单基本信息end-->
			<!--已关闭 & 已完成订单begin-->
                        <div class="btn-s">
                        <?php if(($order["key"]) == "1"): ?><a href="<?php echo ($tocancel); echo ($order["id"]); ?>"><button class="btn btn-danger btn-small" type="button">取消订单
</button></a>&nbsp;<a href="<?php echo ($topay); echo ($order["payid"]); ?>"><button class="btn btn-warning btn-small" type="button">立即支付</button></a><?php endif; ?>
                        <div class="clr"></div>
                        <?php if(($order["key"]) == "5"): ?><a href="<?php echo ($todone); echo ($order["id"]); ?>">确认订单</a><?php endif; ?>
			<!--已关闭 & 已完成订单end-->
                        </div>
		</li><?php endforeach; endif; ?>
	</ul>
</div>
</section>
<script type="text/javascript">
function payEben(orderid){	
	window.wst.payEben(orderid);
}
</script>
<script type="text/javascript">
		var global_loading=document.getElementById("global_loading");
		global_loading.parentNode.removeChild(global_loading);
</script>
<style>
    section{
        margin-top: 6em;
        margin-bottom: 6em;
    }
    .xiangqing{
        float: left;
    }
    .clr{
        clear: both;
    }
    .ddbh{
        width: 100%;
    }
    .btn-s{
        padding: 5px;
    }
</style>