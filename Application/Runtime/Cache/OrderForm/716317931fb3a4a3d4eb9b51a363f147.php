<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
梦云智工作室
 
-->
<div>
    <ul style="list-style:outside none; line-height:2em" class="column_order">
        <li id="order_id">订单号:<?php echo ($detail["id"]); ?></li>
        <li id="name">用户昵称:<?php echo ($detail["customer"]["nickname"]); ?></li>
        <li id="tel">用户手机号:<?php echo ($detail["customer"]["phone_number"]); ?></li>
        <li id="time">支付时间:<?php echo (date("y/m/d H:i",$detail["pay_time"])); ?></li>
    </ul><!--订单号栏-->
    <div class="clr"></div>
    <div class="table-data tab">
        <legend style="font-weight:bold; color:#CC0000; margin:30px">商品信息</legend>
        <table align="center" border="1px" width="90%" cellpadding="5px" cellspacing="0px" >
            <?php if(is_array($detail['goods'])): foreach($detail['goods'] as $key=>$value): ?><tr>
                <td><?php echo ($value["goods_name"]); ?></td>
                <td>价格：￥<?php echo ($value["price"]); ?></td>
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
                <tr>
                <td>收货信息：</td>
                <td><?php echo ($detail["consignee"]["customer_address"]); ?></td>               
                </tr><!--收货地址-->
        </table>       
    </div><!--收货table-->
    <div style="margin-top:10px">
      <legend style="font-weight:bold; color:#CC0000; margin:30px">配货信息</legend>
        <table align="center" border="1px" width="90%" cellpadding="5px" cellspacing="0px">
                <tr>
                <td>采购来源1：</td>
                <td><?php echo ($detail["procure_source1"]); ?></td>               
                </tr><!--采购来源-->
                <tr>
                <td>采购订单号1：</td>
                <td><?php echo ($detail["procure_orderid1"]); ?></td>               
                </tr><!--采购订单号-->
               
                <tr>
                <td>采购来源2：</td>
                <td><?php echo ($detail["procure_source2"]); ?></td>               
                </tr><!--采购来源-->
                <tr>
                <td>采购订单号2：</td>
                <td><?php echo ($detail["procure_orderid2"]); ?></td>               
                </tr><!--采购订单号-->
                
                <tr>
                <td>采购来源3：</td>
                <td><?php echo ($detail["procure_source3"]); ?></td>               
                </tr><!--采购来源-->
                <tr>
                <td>采购订单号3：</td>
                <td><?php echo ($detail["procure_orderid3"]); ?></td>               
                </tr><!--采购订单号-->
            </table>
    </div><!--配货表-->
    
    <div style="margin-top:10px">
      <legend style="font-weight:bold; color:#CC0000; margin:30px">原产国配送信息</legend>
        <table align="center" border="1px" width="90%" cellpadding="5px" cellspacing="0px">
                <tr>
                <td>原产国快递号1：</td>
                <td><?php echo ($detail["origin_orderid1"]); ?></td>               
                </tr><!--采购来源-->
                <tr>
                <td>原产国公司1：</td>
                <td><?php echo ($detail["origin_company1"]); ?></td>               
                </tr><!--采购订单号-->
               
                <tr>
                <td>原产国快递号2：</td>
                <td><?php echo ($detail["origin_orderid2"]); ?></td>               
                </tr><!--采购来源-->
                <tr>
                <td>原产国公司2：</td>
                <td><?php echo ($detail["origin_company2"]); ?></td>               
                </tr><!--采购订单号-->
                
                <tr>
                <td>原产国快递号3：</td>
                <td><?php echo ($detail["origin_orderid3"]); ?></td>               
                </tr><!--采购来源-->
                <tr>
                <td>原产国公司3：</td>
                <td><?php echo ($detail["origin_company3"]); ?></td>               
                </tr><!--采购订单号-->
            </table>
    </div><!--物流信息表-->
    
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