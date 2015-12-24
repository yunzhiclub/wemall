<?php if (!defined('THINK_PATH')) exit(); if(is_array($data)): foreach($data as $key=>$value): ?><div class="row clearfix">
    <div class="col-xs-12 column">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <!--php>var_dump($value);</php-->
                    <div class="col-xs-12">
                        NO:<?php echo ($value['id']); ?><!--<?php echo ($value['source']); ?>-<?php echo (date("Y-m-d",$value['buy_time'])); ?>-->
                    <?php if($value['is_finish'] == 1) { echo '<span class="label label-success">已完成</span>'; } elseif($value['is_cancel'] == 1) { echo '<span class="label label-default">已取消</span>'; } elseif($value['is_pay'] == 1) { echo '<span class="label label-primary">'; if($value['is_pick'] == 0) { echo "正在配货"; } elseif($value['is_getlog'] == 0) { echo "获取物流信息中"; } elseif($value['is_origin'] == 0) { echo "原产国配送中"; } else { echo "国际配送中"; } echo '</span>'; } else { echo '<span class="label label-info">未支付</span>'; } ?>
                    </div>
                    <div class="clr"></div>
                    </h3>
            </div>
            <div class="panel-body">
                <?php if(is_array($value["_goods"])): foreach($value["_goods"] as $key=>$goods): ?><div class="row">
                        <div class="col-xs-2  no-padding">
                            <img class="img-responsive" src="<?php echo ($goods["_pic"]["url"]); ?>"/>
                        </div>
                        <div class="col-xs-8">
                            <p><?php echo ($goods["goods_name"]); ?></p>
                        </div>
                        <div class="col-xs-2">
                            <p><?php echo (format_money($goods["price"])); ?></p>
                            <p>*<?php echo ($goods["quantity"]); ?></p>
                        </div>
                        
                    </div><?php endforeach; endif; ?>
                <div class="col-xs-12">
                            <p><?php echo ($value["_payid"]["customer_address"]); ?></p>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-xs-12">
                    <span class="danger" data-toggle="popover" data-placement="top" title="Tips" data-content="相同支付编号的订单将合并支付">支付编号：<?php echo ($value["_payid"]["id"]); ?></span>
                    
                </div>
                <div class="col-xs-3 col-xs-offset-6 text-center">
                    <?php if($value['is_cancel'] == 0 && $value['is_pay'] == 0) { $flag = 0; } else { $flag = 1; } ?>
                    <?php if(($flag) == "0"): ?><button id="cancel-<?php echo ($value["payid"]); ?>" data-payid="<?php echo ($value["payid"]); ?>" class="btn-xs btn btn-danger cancel-payid-<?php echo ($value["payid"]); ?>" onclick="cancelOrder($(this))">取消</button><?php endif; ?>
                </div>
                <div class="col-xs-3 text-center">
                    <?php if(($flag) == "0"): ?><a href="<?php echo ($value["_payUrl"]); ?>" class="btn-xs btn btn-primary finish-payid-<?php echo ($value["payid"]); ?>">支付</a><?php endif; ?>
                    <?php if(($value["is_cancel"]) == "0"): if(($value["is_inter"]) == "0"): if(($value["is_origin"]) == "1"): ?><button id="finish-<?php echo ($value["id"]); ?>" data-id='<?php echo ($value["id"]); ?>' class="btn-xs btn btn-primary" onclick="finishOrder($(this))">确认收货</button><?php endif; endif; endif; ?>
                </div>
                <div class="clr"></div>
            </div>
        </div>
    </div>
</div><?php endforeach; endif; ?>
<?php echo ($css); ?>
<?php echo ($js); ?>