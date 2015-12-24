<?php if (!defined('THINK_PATH')) exit();?><div class="shopping-cart row" id="ds_gal">
    <!--顶部begin-->
        
        <div class="count col-xs-12 totalCount">
            共<span id="totalCount"></span>件商品。<!--1.点击商品后跳转。2.按来源物流分类-->
        </div>
      <!--a class="continue" href="<?php echo ($indexUrl); ?>">继续购物&gt;&gt;</a--> 
    <!--顶部end-->
    <!--商品列表begin-->
          <?php if(is_array($shoppingcart)): foreach($shoppingcart as $skey=>$soure): if(is_array($soure)): foreach($soure as $lkey=>$sLogistic): $totalFee = 0; ?>
                <div class="count col-xs-12"><!--相同来源相同物流方式订单开始-->
                    <?php if(is_array($sLogistic)): foreach($sLogistic as $key=>$value): ?><!--商品记录开始-->
                        <div class="row line" id="good<?php echo ($value["id"]); ?>" data-id=<?php echo ($value["id"]); ?> data-price="<?php echo ($value["purchasing_price"]); ?>" data-count="<?php echo ($value["product_quantity"]); ?>" data-mincount="<?php echo ($value["shop_lower_limit"]); ?>" data-maxcount="<?php echo ($value["shop_superior_limit"]); ?>">
                            <div class="col-xs-4"> 
                                <a href="javascript:void(0);"><img class="img-rounded img-responsive" src="<?php echo ($value["focus_pictures"]["url"]); ?>"></a> 
                            </div>
                            <div class="col-xs-7">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <span class="name"><?php echo ($value["name"]); ?></span>
                                        <div class="row rowmoney">
                                            <div class="col-xs-12">
                                            <span class="money"> ￥<?php $totalFee += ($value['purchasing_price']*$value['product_quantity']); echo format_money($value['purchasing_price']); ?></span>
                                            </div>
                                    <?php if(($value["discount_amount"]) > "0"): ?><p class="price"><?php echo ($value[""]); ?></p><?php endif; ?>
                                        </div>
                                    
                                        
                                    <div class="row rowjiajian">
                                        <div class="col-xs-3" style="padding-right:0px;"><span class="shuliang">数量</span></div>
                                        <div style="width:160px">    
                                                <div class="input-group">
                                                    <span class="input-group-btn" onclick="change_num($(this));" data-type="red">
                                                      <button class="btn btn-default" type="button">-</button>
                                                    </span>
                                                    <input type="text" class="form-control number" id="count<?php echo ($value["id"]); ?>" data-type="change" value="<?php echo ($value["product_quantity"]); ?>" onblur="change_num($(this))" >
                                                    <span class="input-group-btn"  onclick="change_num($(this))" data-type="add">
                                                      <button class="btn btn-default" type="button">+</button>
                                                    </span>
                                                </div><!-- /input-group -->
                                        </div>
                                    </div>
                                <input type="hidden" name="rowid" value="a239d26ca5967a553b6db6489b378e17">
                                
                                </div>
                                    
                                </div>
                                
                            </div>
                            <div class="col-xs-1"><a class="trash" href="javascript:void(0);" data-id="<?php echo ($value["id"]); ?>"><span class="glyphicon glyphicon-trash"></span></a></div> 
                        <!--商品记录结束-->
                        </div><?php endforeach; endif; ?>
                </div><!--相同来源相同物流方式订单结束--><?php endforeach; endif; endforeach; endif; ?>
    <!--商品列表end-->
    <!--结算begin-->
          <div class="col-xs-12">
          <a href="javascript:void(0);" class="delete-all"><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;清空购物车</a>
          </div>
        <div class="shifu col-xs-12">
            <span class="shifu2">实付金额：<strong id="totalFee" class="money">￥<?php echo format_money($totalFee); ?></strong></span>
        </div>

        <div class="xiadan col-xs-12"> 
          <a id="pay">
              <button type="button" class="btn yz-btn btn-block" <?php if(($flag) == "1"): ?>onclick="location='<?php echo ($indexUrl); ?>'" >请先添加商品<?php else: ?>>立即下单<?php endif; ?></button></a>
        </div>
    <!--结算end-->
    <!---->
  </div>
<?php echo ($js); ?>
<?php echo ($css); ?>