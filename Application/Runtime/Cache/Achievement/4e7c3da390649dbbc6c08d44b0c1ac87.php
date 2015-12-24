<?php if (!defined('THINK_PATH')) exit();?><div>统计周期：<?php echo (date("Y年m月d日 H:i",$data[0]['issue']['begin_time'])); ?>&nbsp;&nbsp;至&nbsp;&nbsp;<?php echo (date("Y年m月d日 H:i",$data[0]['issue']['end_time'] )); ?></div>
<table class="table table-bordered table-striped">
    <thead>
        <tr class="head">
            <th>姓名</th>
            <th>账号</th>
            <th>流水号</th>
            <th>手机号</th>
            <th>直销业绩</th>
            <th>直销基数</th>
            <th>直销系数</th>
            <th>分销业绩</th>
            <th>分销基数</th>
            <th>分销系数</th>
            <th>应发提成</th>
            <th>实发</th>
            <th>备注</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($data)): foreach($data as $key=>$value): ?><tr>
            <td><?php echo ($value["openid"]["nickname"]); ?></td>
            <td><?php echo ($value["openid"]["aipayid"]); ?></td>
            <td><span class='sno'><?php echo ($value["serial_number"]); ?></span></td>
            <td><?php echo ($value["openid"]["phone_number"]); ?></td>
            <td style="text-align:right"><?php echo format_money($value['direct_result']); ?></td>
            <td style="text-align:right"><?php echo format_money($value['direct_base']); ?></td>
            <td><?php echo ($value["direct_coe"]); ?></td>
            <td style="text-align:right"><?php echo format_money($value['line_result']); ?></td>
            <td style="text-align:right"><?php echo format_money($value['line_base']); ?></td>
            <td><?php echo ($value["line_coe"]); ?></td>
            <td style="text-align:right"><?php echo format_money( floor($value['direct_base']*$value['direct_coe'] + $value['line_base']*$value['line_coe'] )); ?></td>
        <td style="text-align:right"><?php echo format_money($value['real_pay']); ?></td>
            <td><span class='remark'><?php echo ($value["remark"]); ?></span></td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div class="pagination"><?php echo ($page); ?></div>