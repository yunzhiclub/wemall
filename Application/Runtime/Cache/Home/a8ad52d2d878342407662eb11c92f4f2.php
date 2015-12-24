<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): foreach($list as $key=>$goods): ?><div class="row line" data-href="<?php echo ($goodsDetailUrl); ?>?goodsId=<?php echo ($goods["id"]); ?>">
        <div class="col-xs-4">
            <a class="goods-list-content" href="<?php echo ($goodsDetailUrl); ?>?goodsId=<?php echo ($goods["id"]); ?>">
                <img class="img-responsive" href="<?php echo ($goodsDetailUrl); ?>?goodsId=<?php echo ($goods["id"]); ?>" src="<?php echo ($goods["image_path"]); ?>" /></a>
        </div>
        <div class="col-xs-8">
            <a class="goods-list-content" href="<?php echo ($goodsDetailUrl); ?>?goodsId=<?php echo ($goods["id"]); ?>"><span class="name"><?php echo ($goods["name"]); ?></span></a>
            <div class="row row1">
                <div class="col-xs-6 left1">
                    <span class="money"> ￥<?php echo format_money($goods['purchasing_price']); ?></span>
                </div>
                <div class="col-xs-6 right1">
                    <img  alt="140x140" width="20px" height="20px" src="<?php echo ($goods["source"]["icon_url"]); ?>" alt="" class="img-circle">
                    <?php echo ($goods["logistics_mode"]["name"]); ?>
                </div>  
            </div>
            
            <div class="row row2">
                <div class="col-xs-6 left2">
            直销返点：<?php echo ($goods['direct_selling_commission']); ?>%
                </div>
                <div class="col-xs-6 right2">
                    下线返点：<?php echo ($goods['line_selling_commission']); ?>%
                </div>
            </div>
            
            <?php if(($goods["discount_amount"]) > "0"): ?><div class="row left2">
                <div class="col-xs-11">
                    <span class="money">优惠政策：<?php echo ($goods["discount_way"]); ?></span>
                </div>
            </div><?php endif; ?>
        </div>
    </div><?php endforeach; endif; ?>