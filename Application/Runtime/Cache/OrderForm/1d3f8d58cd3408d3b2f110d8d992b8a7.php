<?php if (!defined('THINK_PATH')) exit();?><section>
<!--<form onsubmit="javascript:return check(theForm);" action="order.php?act=done" method="post" name="theForm" id="theForm">-->
<div class="pending-detial">
    <!--收货地址begin-->
    <div class="list-group address">
        <?php echo ($indexAddress); ?>
    </div>
    <div class="guodu row"></div>
    
    <!--商品列表begin-->       
    <?php if(is_array($product)): foreach($product as $ke=>$va): ?><div class="row row-bg">
            <div class="col-xs-12"><h2>订单信息(<?php echo ++$i;?>)</h2></div>
        </div>
        <?php if(is_array($va["goods"])): foreach($va["goods"] as $key=>$value): ?><div class="row row-goods row-bg" id="good-<?php echo ($value["id"]); ?>" data-id="<?php echo ($value["id"]); ?>" data-price="<?php echo ($value['purchasing_price']); ?>" data-count="<?php echo ($value["product_quantity"]); ?>" >
            <div class="col-xs-2 yz-pic">
                <img id="img-rounded" class="img-rounded" src="<?php echo ($value["focus_pictures"]["url"]); ?>">
            </div>
            <div class="col-xs-10">
                <div class="row">
                    <div class="col-xs-8 miaoshu"><a href="<?php echo U('Goods/GoodsDetail/index'); ?>?goodsId=<?php echo ($value["id"]); ?>"><?php echo ($value["name"]); ?></a></div>
                    <div class="col-xs-4 zidingyi "><strong>￥<?php echo format_money($value['purchasing_price']); ?></strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-9 ">商品计重：<?php echo ($value["weight"]); ?>克</div>
                    <div class="col-xs-3 zidingyi">×<?php echo ($value["product_quantity"]); ?></div>
                </div>
            </div>
        </div><?php endforeach; endif; ?>
        <div class="row row-space"></div>
        <div class="row row-bg row-item">
            <div class="col-xs-12 line">
                总计(含运费)：￥
                            <?php if($va['payable']<0){ $va['payable'] = 0; } echo format_money($va['payable']); ?>
            </div>
        </div>
<!--        <div class="row line"></div>-->
<!--  以上是分割线-->
<!--        <div class="row row-bg">
            <div class="col-xs-12 line">
                <span data-toggle="popover" title="运费说明" data-placement="right" data-content="<?php echo ($freight[5]['SYSTEM_FREIGHT_EXPLAIN']); ?>">运费：￥ <?php echo (format_money($va['logisticFee'])); ?></span>
            </div>
        </div>-->
        <div class="row row-bg">
            <div class="col-xs-12 line">
                
            <div class="col-xs-5 freight1">
                运费：￥ <?php echo format_money($va['logisticFee']); ?>
            </div>
            <div class="col-xs-7 text-right">
                <span data-toggle="popover" data-placement="top" data-content="<?php echo ($freight[5]['SYSTEM_FREIGHT_EXPLAIN']); ?>">运费说明</span>
            </div>
            </div></div>
        <?php if(($va["maxAmount"]) > "0"): ?><div class="row row-bg">
            <div class="col-xs-12 coupon0">
                优惠额度:￥<?php echo format_money($va['maxAmount']); ?>
            </div>
        </div><?php endif; ?>
        <div class="row row-space"></div><?php endforeach; endif; ?>
        
        <div class="row row-bg row-item">
            <div id="" class="col-xs-7 coupon1">
                代金券：<span id="couponFee">￥0.00</span>
            </div>
            <div class="col-xs-5 daijinquan">
                <a href="javascript:void(0)" id="chooseDJQ" onclick="onFreight()">选择代金券</a>
                <span id="glyphicon-chevron-right" class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span id="glyphicon-chevron-down" class="glyphicon glyphicon-chevron-down hide1" aria-hidden="true"></span>
            </div>
        </div>
        <div class="hide1" id="daijinquan-box">            
            <div  class="couponAll list-group">    
                    <?php if(is_array($coupon)): foreach($coupon as $key=>$value): ?><div class="checkbox list-group-item">
                          <label>
                            <input type="checkbox" id="coupon<?php echo ($value["id"]); ?>" class="coupon" type="checkbox" data-id="<?php echo ($value["id"]); ?>" data-value="<?php echo ($value["cover"]); ?>" data-show-value="<?php echo format_money($value['cover']); ?>"> 面额：<?php echo format_money($value['cover']); ?>&nbsp;&nbsp;&nbsp;&nbsp;使用期限：<?php echo date("Y-m-d H:i",$value['end_time']); ?>
                          </label>
                        </div><?php endforeach; endif; ?>
            </div>
        </div>
        <div class="border" bgcolor="#ffffff">本次最多可使用<font id="totalCount" data-value="<?php echo ($i); ?>"><?php echo ($i); ?></font>张，已使用<span class="couponTotal">0</span>张</div>
</div>
<!--</form>-->
</section>
<?php echo ($wxJs); ?>
<?php echo ($js); ?>
<?php echo ($css); ?>
<script>
$(function () { $("[data-toggle='popover']").popover(); 
    });//运费说明tips
</script>