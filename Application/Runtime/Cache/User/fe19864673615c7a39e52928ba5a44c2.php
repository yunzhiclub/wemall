<?php if (!defined('THINK_PATH')) exit();?>                <div class="row"> <!--最大的行-->
                    <div class="col-xs-12"><!--最大列-->
                        <?php if(is_array($list)): foreach($list as $key=>$order): ?><div class="row big-box"><!--第二行-->
                                <div class="col-xs-12"><!--第二列-->
                                    <div class="row"><!--标题行-->
                                        <div class="col-xs-6 num">订单编号:<?php echo ($order["id"]); ?></div>
                                        <div class="col-xs-6">
                                            <div class="row">
                                                <!--<div class="col-xs-2"></div>-->
                                                <div class="col-xs-5"><a href="<?php echo ($tocancel); echo ($order["id"]); ?>"><button class="btn btn-danger btn-mini" type="button">取消订单</button></a></div>
                                                <div class="col-xs-5"><a href="<?php echo ($topay); echo ($order["id"]); ?>"><button class="btn btn-warning btn-mini" type="button">立即支付</button></a></div>
                                                <!--已关闭 & 已完成订单end-->
                                            </div>
                                        </div>
                                    </div>
                                    <?php if(is_array($order["order_form"])): foreach($order["order_form"] as $key=>$value): ?><div class="row"><!--详情行-->
                                            <div class="col-xs-12"><!--详情列-->
                                                <a class="touch" href="<?php echo ($url); echo ($value["id"]); ?>"><!--详情标签-->
                                                    <div class="row"><!--编号行-->
                                                        <div class="col-xs-12"><!--编号列-->
                                                            <p class="id">订单编号：<?php echo ($value["id"]); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row"><!--状态行-->
                                                        <div class="col-xs-12"><!--状态列-->
                                                            <p class="price">订单状态：<strong>未支付</strong></p>
                                                        </div>
                                                    </div>
                                                    <div class="row"><!--总数行-->
                                                        <div class="col-xs-12"><!--总数列-->
                                                        <p>商品总数：<?php echo ($value["total_count"]); ?></p>
                                                        <!--<span class="more"><span class="inner"></span></span>-->
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div><?php endforeach; endif; ?>
                                    <div class="row"><!--总额行-->
                                        <div class="col-xs-12"><!--总额列-->
                                            <p class="date">订单总额：<?php echo format_money($order[count]); ?>元</p>
                                        <div class="clr"></div>
                                        </div>
                                        <div class="col-xs-12">
                                           <p class="shipped"><?php echo ($order_data["order_status"]); ?></p> 
                                        </div>
                                    </div>
                                </div>
                            </div><?php endforeach; endif; ?>
                    </div>
                </div>
		<!--订单基本信息end-->
		<!--已关闭 & 已完成订单begin-->