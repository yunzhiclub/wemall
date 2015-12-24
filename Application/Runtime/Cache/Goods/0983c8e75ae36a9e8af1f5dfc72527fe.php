<?php if (!defined('THINK_PATH')) exit();?>
<div class="goods-picture row row-s">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php if(is_array($goods["picturePath"])): foreach($goods["picturePath"] as $key=>$value): ?><div class="swiper-slide text-center"><img class="swiper-image" src="<?php echo ($value); ?>" /></div><?php endforeach; endif; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
   <!-- Initialize Swiper -->
    <script>
        var mySwiper = new Swiper('.swiper-container',{
        pagination: '.swiper-pagination',       
        paginationClickable: true
        }); 
    </script>
</div>
<div class="container-fluid con-s">
        <div class="goods-content" value="" data-price="<?php echo ($goods["international_price"]); ?>" data-max-count="<?php echo ($goods["shop_superior_limit"]); ?>" data-min-count="<?php echo ($goods["shop_lower_limit"]); ?>" data-id="<?php echo ($goods["id"]); ?>"></div>
        <div class="row line">
            <div class="col-xs-9">
                <div class="row">
                    <div class="col-xs-12 name"><?php echo ($goods["name"]); ?></div>
                    
                </div>
                <div class="row money" id="goods-price0">￥<?php echo format_money($goods['purchasing_price']); ?></div>
                <div class="row">
                    <div class="col-xs-5 good-num" ><span>购买数量：</span>
                    </div>
                    <div class="col-xs-7 input-group" >
                        <form id="goods_form">
                                <input type="hidden" value="<?php echo ($goods["id"]); ?>" name="goodsId" />
                                <div id="goodsNum-box" class="input-group">
                                    <span class="input-group-btn">
                                        <button onclick="decrease($(this))" id="reduce" class="btn btn-default disabled" type="button"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
                                    </span>
                                    <input disabled="ture" type="text" name="goodsNum" id="goodsNum" class="form-control" data-max-value="<?php echo ($goods["shop_superior_limit"]); ?>" data-min-value="<?php echo ($goods["shop_lower_limit"]); ?>" value="" placeholder="">
                                    <span class="input-group-btn">
                                        <button onclick="increase($(this))" id="add" class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                    </span>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                    <div class="row row1"><img class="img-responsive" src="<?php echo ($goods["source"]["icon_url"]); ?>"></div>
                    <div class="row arrive-days">预计到手<br /><?php echo ($goods["arrive_days"]); ?>天</div>
            </div>
        </div>
        <div class="row row-bg row-fengexian"><div class="fengexian"></div></div>
<!--        分割线-->
        <div class="row">

            <div class="col-xs-6 left2">
                物流方式：
                <a href="javascript:void(0)" tabindex="0" data-placement="top" role="button" data-toggle="popover" data-content="<?php if(($goods["logistics_mode"]["mode"]) == "1"): ?>商品由原产国直接邮寄到您手中，国际运费由您独立承担<?php else: ?>与其他客户商品一起拼箱邮回国，再由众筹海淘快递给您（拼箱会帮您节省国际运费）<?php endif; ?>"><?php echo ($goods["logistics_mode"]["name"]); ?></a>
            </div>
            <div class="col-xs-6 right2">商品计重：<?php echo ($goods["weight"]); ?>g</div>
        </div>
        <div class="row row-bg row-fengexian"><div class="fengexian"></div></div>
<!--        分割线-->
        <div class="row">
                <div class="col-xs-6 left2">国际邮费：<?php echo ($goods['internation_transportion_expenses']); ?></div>
                <div class="col-xs-6 right2">国内邮费：¥<?php echo format_money($goods['domestic_transportation_expenses']); ?></div>
        </div>
        <div class="row row-bg row-fengexian"><div class="fengexian"></div></div>
<!--        分割线-->
        <div class="row">
                <div class="col-xs-6 left2">关税：<?php echo ($goods['customs']); ?></div>
                <?php if(($goods["discount_amount"]) > "0"): ?><div class="col-xs-6 right2">优惠政策：<?php echo ($goods['discount_way']); ?></div><?php endif; ?>
        </div>
            <?php if(($customerType) == "1"): ?><div class="row row-bg row-fengexian"><div class="fengexian"></div></div>
<!--        分割线-->
        <div class="row">
                <div class="col-xs-6 left2">直销返点：<?php echo ($goods["direct_selling_commission"]); ?>%</div>
                <div class="col-xs-6 right2">下线返点：<?php echo ($goods["line_selling_commission"]); ?>%</div>
        </div><?php endif; ?>
            <div class="row row-bg row-fengexian"><div class="fengexian"></div></div>
<!--        分割线-->
        <div class="row">
                <div class="col-xs-12 left2">
                    备注：<?php echo ($goods["remarks"]); ?>
                </div>
        </div>
        <div class="row row-bg row-fengexian"><div class="fengexian"></div></div>
<!--        分割线-->
        <div class="goods-introduce row">
                <?php $c = $goods['discribe']; $d = html_entity_decode($c, ENT_QUOTES); echo $d; ?>
        </div>
</div>
    <div class="footer row">
            <ul>
                <li class="footer-item-left" style="list-style: none"><a onclick="addGoodsToCart($(this))" href="javascript:void(0)">加入购物车</a></li>
                <li class="footer-item-right"style="list-style: none"><a href="javascript:void(0)" onclick="buyNow($(this))">立即购买</a></li>
                <li class="off clr"></li>
            </ul>
    </div>
<?php echo ($js); ?>
<?php echo ($css); ?>
<script>
//                    $(function () {
//                  $('[data-toggle="popover"]').popover();
//                });
                $(document).ready(function(){
                    $('[data-toggle="popover"]').popover();
                });
</script>