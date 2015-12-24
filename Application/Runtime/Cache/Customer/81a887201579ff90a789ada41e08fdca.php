<?php if (!defined('THINK_PATH')) exit(); echo ($css); ?>
<table class="table table-bordered table-striped">
  
						<thead>
							<tr>
								<th class="width">昵称</th>
                                                                <th class="width">手机号</th>
                                                                <th class="width">关注时间</th>
                                                                <th class="width">上线用户姓名</th>
                                                                <th class="width">用户状态</th>
                                                                <th class="width">操作</th>
							</tr>
						</thead>
						<tbody>
                                                         <?php $i=1; ?>
							<?php if(is_array($customer)): foreach($customer as $key=>$value): ?><tr>
                                                        <td><a onclick="#" href="<?php echo ($value["actionUrl"]["look"]); ?>"><?php echo ($value["nickname"]); ?></a></td>
                                                        <td><?php echo ($value["phone_number"]); ?></td>
                                                        <td><?php echo (date("y/m/d H:s",$value["subscribe_time"])); ?></td>
                                                        <td><a onclick="#" href="<?php echo ($value["actionUrl"]["parent_look"]); ?>"><?php echo ($value["parent_name"]); ?></a></td>
                                                        <td><?php echo ($value["subscribe_state"]); ?></td>
                                                        <td>
                                                        <a onclick="#" href="<?php echo ($value["actionUrl"]["edit"]); ?>">修改&nbsp;</a>
                                                        <a onclick="#" href="<?php echo ($value["actionUrl"]["freezen"]); ?>"><?php echo ($value["freezen_state"]); ?></a>
                                                        </td>
                                                        </tr><?php endforeach; endif; ?>
						</tbody>
					</table>
<div class="pagination" align="center"><?php echo ($page); ?></div>