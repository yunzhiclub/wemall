<?php if (!defined('THINK_PATH')) exit();?><div>统计周期：<?php echo (date("Y年m月d日 H:i",$beginTime)); ?>&nbsp;&nbsp;至&nbsp;&nbsp;<?php echo (date("Y年m月d日 H:i",$endTime )); ?></div>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr class="head">
            <th>昵称</th>
            <th>手机号</th>
            <th>直销业绩</th>
            <th>直销佣金</th>
            <th>直销系数</th>
            <th>下线业绩</th>
            <th>下线佣金</th>
            <th>下线系数</th>
            <th>应发佣金</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($data)): foreach($data as $key=>$value): ?><tr>
            <td><?php echo ($value["_customer_id"]["nickname"]); ?></td>
            <td><?php echo ($value["_customer_id"]["phone_number"]); ?></td>
            <td style="text-align:right"><?php echo format_money($value['totalGoodsFee']); ?></td>
            <td style="text-align:right"><?php echo format_money($value['totalDirectFee']); ?></td>
            <td><?php echo ($value["_totalGoodsFee_rebate"]["direct_ratio"]); ?></td>
            <td style="text-align:right"><?php echo format_money($value['lineTotalGoodsFee']); ?></td>
            <td style="text-align:right"><?php echo format_money($value['lineTotalLineFee']); ?></td>
            <td><?php echo ($value["_lineTotalGoodsFee_rebate"]["line_ratio"]); ?></td>
            <td style="text-align:right"><?php echo format_money($value['commission']); ?></td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div class="pagination"><?php echo ($page); ?></div>
<div class="js off">
    <?php echo ($js); ?>
</div>