<?php if (!defined('THINK_PATH')) exit();?>
<div class="row clearfix">
    <div class="col-xs-12 column">
        <div class="panel-group" id="panel-675075">
            <div class="panel panel-default">
                <div class="panel-heading">
                         <a class="panel-title" data-toggle="collapse" data-parent="#panel-675075" href="#panel-element-870696">未使用的优惠券</a>
                </div>
                <div id="panel-element-870696" class="panel-collapse in">
                        <div class="panel-body">
                                <table class="table">
				<thead>
					<tr>
						<th>
							序号
						</th>
						<th>
							过期时间
						</th>
						<th class="text-right">
							优惠金额
						</th>
						<th>
							状态
						</th>
					</tr>
				</thead>
				<tbody>
                                <?php if(is_array($unUsedCoupons)): foreach($unUsedCoupons as $key=>$value): ?><tr>
						<td>
							<?php echo ++$i;?>
						</td>
						<td>
							<?php echo (date("Y-m-d",$value["end_time"])); ?>
						</td>
						<td  class="text-right">
                                        <?php echo (format_money($value["cover"])); ?>
						</td>
						<td>
                                        <?php if(($value["is_freezed"]) == "0"): ?>正常
                                        <?php else: ?>
                                        冻结<?php endif; ?>
						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
			</table>
                        </div>
                </div>
        </div>
        <div class="panel panel-default">
                <div class="panel-heading">
                         <a class="panel-title collapsed" data-toggle="collapse" data-parent="#panel-675075" href="#panel-element-718805">已使用的优惠券</a>
                </div>
                <div id="panel-element-718805" class="panel-collapse collapse">
                        <div class="panel-body">
                                <table class="table">
				<thead>
					<tr>
						<th>
							序号
						</th>
						<th>
							使用时间
						</th>
						<th class="text-right">
							优惠金额
						</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($unUsedCoupons)): foreach($unUsedCoupons as $key=>$value): ?><tr>
						<td>
							<?php echo ++$j;?>
						</td>
						<td>
							<?php echo (date("Y-m-d",$value["use_time"])); ?>
						</td>
						<td class="text-right">
							<?php echo (format_money($value["cover"])); ?>
						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
			</table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>