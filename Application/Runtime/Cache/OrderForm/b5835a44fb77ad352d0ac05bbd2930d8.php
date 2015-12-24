<?php if (!defined('THINK_PATH')) exit();?><table  class="table table-bordered table-striped table-hover">  
        
        <tr>
            <th>支付编号</th>
            <th>订单编号</th>
            <th>用户昵称</th>
            <th>订单总额</th>
            <th>商品总数</th>         
            <th>下单时间</th>
            <th>操作</th>
	</tr>
        
        <?php if(is_array($noPay)): foreach($noPay as $key=>$value1): ?><tr align="center">
                <td><?php echo ($value1["id"]); ?></td>
                <td></td>
                <td><?php echo ($value1["_customer"]["nickname"]); ?></td>
                <td style='text-align: right'><?php echo format_money($value1['payable']); ?></td>
                <td><?php echo ($value1["total_count"]); ?></td>             
                <td><?php echo (date("Y/m/d H:i",$value1["creat_time"])); ?></td>
                <td>
                    <a  href="<?php echo ($value1['_actionUrl']['change']); ?>">
                        <button class="btn btn-mini btn-primary" <?php if ($value1['prepay_id'] != null) echo "style='display:none'"; ?> type="button">
                            修改金额
                        </button>
                    </a>
                    <a href="<?php echo ($value1['_actionUrl']['pay']); ?>">
                        <button class="btn btn-mini btn-primary" type="button">
                            支付
                        </button>
                    </a>
                    <a href="<?php echo ($value1['_actionUrl']['cancel']); ?>">
                         <button class="btn btn-mini btn-danger" type="button">
                             取消
                         </button>
                    </a>
                </td>
            </tr>
            <?php if(is_array($value1["_detail"])): foreach($value1["_detail"] as $key=>$value): ?><tr align="center">
                <td></td>
                <td><?php echo ($value1["id"]); ?>-<?php echo ($value["id"]); ?></td>
                <td><?php echo ($value["customer"]["nickname"]); ?></td>
                <td style='text-align: right'><?php echo format_money($value['total_prices']); ?></td>
                <td><?php echo ($value["total_count"]); ?></td>             
                <td><?php echo (date("Y/m/d H:i",$value["buy_time"])); ?></td>
                <td>-</td>
            </tr><?php endforeach; endif; endforeach; endif; ?>
  </table>
<div class="pagination"><?php echo ($page); ?></div>