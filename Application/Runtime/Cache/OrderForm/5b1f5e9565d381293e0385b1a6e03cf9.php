<?php if (!defined('THINK_PATH')) exit();?><div>
    <ul style="list-style:outside none; line-height:2em" class="column_order">
        <li id="order_id">订单号:<?php echo ($pickingD["id"]); ?></li>
        <li id="name">用户昵称:<?php echo ($pickingD["customer"]["nickname"]); ?></li>
        <li id="tel">用户手机号:<?php echo ($pickingD["customer"]["phone_number"]); ?></li>
        <li id="time">支付时间:<?php echo (date("y/m/d H:i",$pickingD["pay_time"])); ?></li>
    </ul><!--订单号栏-->
    <div class="clr"></div>
    <div class="table-data tab">
        <legend style="font-weight:bold; color:#CC0000; margin:30px">商品信息</legend>
        <table align="center" border="1px" width="90%" cellpadding="5px" cellspacing="0px" >
            <?php if(is_array($pickingD['goods'])): foreach($pickingD['goods'] as $key=>$value): ?><tr>
                <td><?php echo ($value["goods_name"]); ?></td>
                <td>价格：￥<?php echo format_money($value['price']); ?></td>
            </tr>
            <tr>
            <td>编号：<?php echo ($value["goods_id"]); ?></td>
                <td>数量：<?php echo ($value["quantity"]); ?></td>
            </tr><?php endforeach; endif; ?>
        </table><!--商品信息二-->
    </div><!--商品信息table-->
    <div class="clr"></div>
    <div style="margin-top:10px">
      <legend style="font-weight:bold; color:#CC0000; margin:30px">收货人信息</legend>
        <table align="center" border="1px" width="90%" cellpadding="5px" cellspacing="0px">
                <tr>
                <td>收货信息：</td>
                <td><?php echo ($pickingD["consignee"]["customer_address"]); ?><br /><?php echo ($pickingD["consignee"]["customer_idno"]); ?></td>               
                </tr><!--收货地址-->
                <tr>
                    <td>收货人身份证正面：</td>
                    <td>
                        <a href="<?php echo ($pickingD["consignee"]["frontpath"]); ?>">
                            <img class="tu" src="<?php echo ($pickingD["consignee"]["frontpath"]); ?>"/>
                        </a>
                    </td>
                    <td>收货人身份证反面：</td>
                    <td>
                        <a href="<?php echo ($pickingD["consignee"]["frontpath"]); ?>">
                            <img class="tu" src="<?php echo ($pickingD["consignee"]["backpath"]); ?>"/>
                        </a>
                    </td>
                </tr><!--收货人身份证-->
        </table>       
    </div><!--收货table-->
    <div>
      <input type="button"  onclick="javascript:history.back(-1);" value="返回" class="btn btn-s">

    </div><!--修改状态-->
    <div class="clr"></div>
</div><!--最外层div-->

<style>
  .wrap{
     width:70%;
	 margin-left:auto;
	 margin-right:auto;
	 border:1px solid #99CC00;
  }
 .column_order {
    padding:20px; 
  width:43%; 
  margin-left:20px;
 }
.clr{
 clear:both;
}
.total_price{
  padding:20px;
  float:left;
  width:43%; 
  margin-left:20px;
}
.coupon_number{
  padding-top:10px; 
  padding:20px;
  float:right;
  width:33%;
}
.revise_status{
  float:right;
  margin:20px;
  margin-right:40px;
}
.btn-s{
    float: right;
    margin: 60px;
}
</style>