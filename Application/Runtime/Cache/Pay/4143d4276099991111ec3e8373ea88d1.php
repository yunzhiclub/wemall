<?php if (!defined('THINK_PATH')) exit();?><div class="row paycenter">
    <div class="col-xs-8 vline ">
        &nbsp;支付中心
    </div>
</div>
<?php if(($freezen_state) == "0"): ?><div class="row yz-f2f2f2 orderinfo">
    <div class="col-xs-12">
        <p>您的支付编号为：<?php echo I('get.payid'); ?><br />应付款金额：￥<?php echo format_money($pay); ?>元</p>
    </div>
    <div class="clr"></div>
</div>
<div class="row wxpay" data-href="<?php echo ($wxPay); ?>" onclick="wxPay($(this))">
        <div class="payline col-xs-9">
            <img src="<?php echo add_root_path('/theme/wemall/images/weixin.png'); ?>" alt="微信支付" />微信支付
        </div>
        <div class="col-xs-3 text-center">        
            <span class="glyphicon glyphicon-chevron-right"></span>
        </div>
</div>
<div class="row otherpay"  onclick="otherPay()">
    <div class="col-xs-9">
        <div class="payline"><img class="img1" src="<?php echo add_root_path('/theme/wemall/images/yinhang.png'); ?>" alt="其它支付" />银行卡/支付宝支付</div> 
    </div>

    <div class="col-xs-3 text-center">    
       <span class="glyphicon glyphicon-chevron-right"></span>
    </div>
    <div class="col-xs-11 tips col-xs-offset-1">
        <p><?php echo ($tips); ?></p>
    </div>
</div> 
       <?php else: ?>
       <div class="row">
           <div class="col-xs-12">
               <p>对不起，您的账户状态不正常，无法完成支付。如有任何疑问，请联系店小二。</p>
           </div>
       </div><?php endif; ?>
<?php echo ($css); ?>
<?php echo ($js); ?>