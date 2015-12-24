<?php if (!defined('THINK_PATH')) exit();?> <script type="text/javascript">
        document.write('<div id="global_loading" style="width: 100%;height: 100%;position: fixed;top: 0;left: 0;background-color: #eee;z-index:999"><div style="width: 100px;height: 75px;margin:auto;top:50%;position:relative"><span style="display:block;float:left;width:32px;height:32px;background:url(/mobile/images/spacer.gif);margin-top:-5px;"></span>&nbsp;&nbsp;加载中...</div></div>');
    </script>
<div class="header_03">
  <div class="back"> <a href="index.php" class="arrow"></a> </div>
  <div style="" class="tit">
    <h3>当期业绩管理</h3>
  </div>
  <div class="nav">
    <ul>
      <li class="cart"> <a href="cart.php">购物车</a> <span style="display:none" id="ShoppingCartNum"></span> </li>
    </ul>
  </div>
</div>
    <section>
<div class="pending-detial">
    <h2>直销业绩</h2>
        <div class="order-info">
            <ul>
                <li class="first clearfix">
                        <p class="title">直销业绩:</p>
                        <p class="sum">￥<?php echo format_money($dangqi['totalGoodsFee']); ?></p>                        
                </li>
                <li class="first clearfix">
                        <p class="title">直销佣金基数:</p>
                        <p class="sum">￥<?php echo format_money($dangqi['totalDirectFee']); ?></p>                      
                </li>
                <li class="first clearfix">
                        <p class="title">当前系数:</p>
                        <p class="sum"><?php echo ($dangqi["_totalGoodsFee_rebate"]["direct_ratio"]); ?></php</p>
                </li>
                <li class="first clearfix">
                        <p class="title">当前佣金:</p>
                        <p class="sum">￥<?php echo format_money($result['direct']); ?></p>
                </li>
            </ul>
        </div>
</div>
<div class="pending-detial">
    <h2>下线业绩</h2>
        <div class="order-info">
            <ul>
                <li>
                    <table border="1px" bordercolor="#D5D5D5" cellpadding="5px" cellspacing="0px" align="center" width="100%" style="margin-bottom:30px">
                    <th>下线昵称</th>
                    <th>销售额</th>
                    <?php if(is_array($dangqi['_line']['_lineDetail'])): foreach($dangqi['_line']['_lineDetail'] as $key=>$value): ?><tr>
                           <td align="center"><?php echo ($value["nickname"]); ?></td>
                           <td align="center">￥<?php echo ($value["basePrices"]); ?></td>
                    </tr><?php endforeach; endif; ?>
                    </table>
                </li>
                    <li class="first clearfix">
                            <p class="title">总下线业绩：</p>
                            <p class="sum">￥<?php echo ($dangqi["_line"]["totalGoodsFee"]); ?></p>
                    </li>
                    <li class="first clearfix">
                            <p class="title">总下线佣金基数：</p>
                            <p class="sum">￥<?php echo ($dangqi["_line"]["totalDirectFee"]); ?></p>
                    </li>
                    <li class="first clearfix">
                            <p class="title">系数:</p>
                            <p class="sum"><?php echo ($dangqi["_lineTotalGoodsFee_rebate"]["line_ratio"]); ?></p>
                    </li>
                    <li class="first clearfix">
                            <p class="title">应得:</p>
                            <p class="sum">￥<?php echo format_money($result['line']); ?></p>
                    </li>
            </ul>
        </div>
    </div>
    </section>
<script type="text/javascript">
		var global_loading=document.getElementById("global_loading");
		global_loading.parentNode.removeChild(global_loading);
</script>
<style>
    section{
        margin-top: 6em;
        margin-bottom: 6em;
    }
</style>