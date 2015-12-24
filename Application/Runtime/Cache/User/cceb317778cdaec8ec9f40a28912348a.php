<?php if (!defined('THINK_PATH')) exit();?>    <div class="row">    
        <div class="col-xs-6 col-xs-offset-3 text-center">
            <img class="img-responsive" src="<?php echo ($customer["headimgurl"]); ?>"/>
            <br />
            <span><?php echo ($customer["nickname"]); ?><span>    
        </div>
    </div>
    <div class="lineBox"></div>
    <!--订单管理-->
    <div class="row">
        <div class="col-xs-12 title">
            订单管理:
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3 text-center order">
            <a class="first" href="<?php echo ($allOrderUrl); ?>">
                <span class="glyphicon glyphicon-th-large"></span>
                <br />
                全部订单
            </a>
        </div>
        <div class="col-xs-3 text-center order">
            <a class="first" href="<?php echo ($unpayOrderUrl); ?>">
                <span class="glyphicon glyphicon-tasks"></span>
                <br />
                待支付<?php if(($noPayOrderCount) > "0"): ?><br /><span class="badge mange_number"><?php echo ($noPayOrderCount); ?></span><?php endif; ?>
            </a>
        </div>
        <div class="col-xs-3 text-center order">
            <a href="<?php echo ($payedOrderUrl); ?>">
                <span class="glyphicon glyphicon-credit-card"></span>
                <br />
                已支付<?php if(($payedOrderCount) > "0"): ?><br /><span class="badge mange_number"><?php echo ($payedOrderCount); ?></span><?php endif; ?>
            </a>
        </div>
        <div class="col-xs-3 text-center order">
            <a href="<?php echo ($finishedOrderUrl); ?>">
                <span class="glyphicon glyphicon-briefcase"></span>
                <br />
                已完成
            </a>
        </div>
    </div>
   <div class="lineBox"></div>

    <div class="account order row">
        <div class="col-xs-12">
          <div class="bt title">           
                <span class="sr-only" aria-hidden="true"></span>账户管理
            </div>
        </div>
    </div>
   <div class="row">
       <a href="<?php echo ($couponUrl); ?>">
           <div class="col-xs-10"><span>我的优惠券</span>
           </div>
        <div class="col-xs-2 text-center">
        <span class="glyphicon glyphicon-chevron-right"></span>
        </div></a>
    </div>
   <div class="row xuxian"></div>
   <div class="row">   
        <a href="<?php echo ($changeUrl); ?>?id=<?php echo ($customer["openid"]); ?>">
            <div class="col-xs-10"><?php if(($customer["flag"]) == "1"): ?><span style="color:red">请完善个人信息</span><?php else: ?><span>修改个人信息</span><?php endif; ?>
            </div>
        <div class="col-xs-2 text-center">
        <span class="glyphicon glyphicon-chevron-right"></span>
        </div></a>
    </div>
     <div class="lineBox"></div>
    <!--账户管理end-->
    <div class="account order row">
        <div class="col-xs-12 title">      
                <span class="sr-only" aria-hidden="true"></span>我的业绩
        </div>
    </div>
    <div class="row">
        <div class="col-xs-10">我的当前业绩</div>
        <div class="col-xs-2 text-center">
        <span class="glyphicon glyphicon-chevron-down"></span>
        </div>
    </div>
    <div class="row xuxian"></div>
    <div class="row">          
        <div class="col-xs-6">
            直销业绩:<?php echo format_money($achievement['direct']['_totalGoodsFee']); ?><br/>
            直销佣金基数:<?php echo format_money($achievement['direct']['_totalDirectFee']); ?><br />
            当前佣金系数:<?php echo ($achievement['_direct_radio']['direct_ratio']); ?><br />
            当前实际佣金:<?php $dirFee = floor($achievement['direct']['_totalDirectFee']*$achievement['_direct_radio']['direct_ratio']); echo format_money($dirFee); ?><br /> 
            当前总佣金:<?php echo format_money($lineFee+$dirFee); ?>元
        </div>
        <div class="col-xs-6">
            下线业绩:<?php echo format_money($achievement['line']['_totalGoodsFee']); ?><br />
            下线佣金基数:<?php echo format_money($achievement['line']['_totalLineFee']); ?><br />
            下线佣金系数:<?php echo ($achievement['_line_radio']['line_ratio']); ?><br />
            下线实际佣金:<?php $lineFee = floor($achievement['line']['_totalDirectFee']*$achievement['_line_radio']['line_ratio']); echo format_money($line); ?><br /> 
        </div>
    </div>
    <div class="row xuxian"></div>
    <div class="row">
        <a href="<?php echo ($wangqiurl); ?>">
        <div class="col-xs-10 title" style="padding-bottom: 20px;">
            <span>我的往期业绩</span>
        </div>
        <div class="col-xs-2 text-center">
        <span class="glyphicon glyphicon-chevron-right"></span>
        </div></a>
    </div>
<?php echo ($indexCss); ?>