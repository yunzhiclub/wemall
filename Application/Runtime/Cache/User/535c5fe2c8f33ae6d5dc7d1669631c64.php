<?php if (!defined('THINK_PATH')) exit();?><div class="row">
<div class="pending-detial">
		<div class="order-info">
                    <?php if(is_array($coupon)): foreach($coupon as $key=>$value): ?><ul>
				<li class="first clearfix">
					<p class="title">金额：</p>
					<p class="sum"><?php echo ($value["cover"]); ?></p>
				</li>
                                <li class="clearfix">
					<p class="title">到期时间：</p>
					<p class="sum"><strong><?php echo (date("y/m/d H:s",$value["end_time"])); ?><br></strong></p>
				</li>
				<li class="clearfix">
					<p class="title">有效期：</p>
					<p class="sum"><strong><?php echo ($value["stay_time"]); ?>天<br></strong></p>
				</li>
			</ul><?php endforeach; endif; ?>
		</div>
</div>