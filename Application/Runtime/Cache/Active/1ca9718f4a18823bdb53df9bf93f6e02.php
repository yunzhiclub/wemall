<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-striped table-hover">
    <thead>
	<tr>
            <th>客户名称</th>
            <th><a href='?orderby=sore&key=direct'>直销业绩</a></th>
            <th><a href='?orderby=sore'>下线业绩</a></th>
            <th>业绩汇总</th>
            <th><a href='?orderby=time'>关注时间</a></th>
            <th>操作</th>
	</tr>
    </thead>
    <tbody>
        <?php $i=1; ?>
	<?php if(is_array($customerList)): foreach($customerList as $key=>$value): ?><tr>
            <td><?php echo ($value["_customer"]["nickname"]); ?></td>
            <td><?php echo (format_money($value['_sore']['direct_total_fee'])); ?>元</td>
            <td>
                <?php echo (format_money($value['_sore']['line_total_fee'])); ?>元
            </td>
            <td><?php echo (format_money($value['_sore']['direct_total_fee']+$value['_sore']['line_total_fee'])); ?>元</td>
            <td><?php echo (date("y-m-d H:s",$value["_customer"]["subscribe_time"])); ?></td>
            <td><a href="<?php echo ($value["sendCouponUrl"]); ?>">
                    <button class="btn btn-mini btn-primary" type="button">
                        发放优惠券
                    </button>
                </a>
            </td>
                                                        
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div class="pagination" align="center"><?php echo ($page); ?></div>