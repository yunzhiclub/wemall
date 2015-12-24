<?php if (!defined('THINK_PATH')) exit();?><h4>
    <a onclick="#" href="<?php echo ($urlAdd); ?>">添加新返点</a>
</h4>
<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>销售业绩(元)</th>
                                                                <th>直销系数 </th>
                                                                <th>操作</th>
							</tr>
						</thead>
                                                <tbody>
                                                    <?php if(is_array($rebate)): foreach($rebate as $key=>$value): ?><tr>
                                                            <td style='text-align: right'><?php echo format_money($value['top_money']); ?></td>
                                                        <td><?php echo ($value["direct_ratio"]); ?></td>
                                                        <td>
                                                            <a onclick="#" href="<?php echo ($value["actionUrl"]["edit"]); ?>">修改</a>
                                                            <a onclick="#" href="<?php echo ($value["actionUrl"]["delete"]); ?>">删除</a>
                                                        </td>
                                                        </tr><?php endforeach; endif; ?>
                                                </tbody>