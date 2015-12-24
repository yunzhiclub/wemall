<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
梦云智工作室
 
-->
<table class="table table-bordered table-striped table-hover">
    <thead>
            <tr>
                    <th>订单编号</th>
                    <th>用户昵称</th>
                    <th>下单时间</th>
                    <th>支付时间</th>
                    <th>收货时间</th>
                    <th>商品总数</th>
                    <th>订单总额</th>
                    <th>备注</th>
                    <th>操作</th>
            </tr>
    </thead>
    <tbody>
            <?php if(is_array($canceled)): foreach($canceled as $key=>$value): ?><tr>
                <td><?php echo ($value["id"]); ?></td>
                <td><?php echo ($value["customer"]["nickname"]); ?></td>
                <td><?php echo (date("y/m/d H:i",$value["buy_time"])); ?></td>
                <td><?php echo (date("y/m/d H:i",$value["pay_time"])); ?></td>
                <td><?php echo (date("y/m/d H:i",$value["finish_time"])); ?></td>
                <td><?php echo ($value["goodsQuantity"]); ?></td>
                <td><?php echo ($value["total_prices"]); ?></td>
                <td><?php echo ($value["remarks"]); ?></td> 
                <td>
                    <a href="<?php echo ($value["url"]["detail"]); ?>">
                        <button class="btn btn-mini btn-primary" type="button">
                            查看详情
                        </button>
                    </a>
                 </td>
            </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div class="pagination"><?php echo ($page); ?></div>