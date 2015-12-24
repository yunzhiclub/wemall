<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
                                                                <th>昵称</th>
                                                                <th>手机号</th>                                                               
                                                                <th>微信号</th>
								<th>金额</th>
                                                                <th>发放时间</th>
                                                                <th>结束时间</th>
							</tr>
						</thead>
						<tbody>
                                                         <?php $i=1; ?>
							<?php if(is_array($used)): foreach($used as $key=>$value): ?><tr>
                                                            <td><?php echo ($value["nickname"]); ?></td>
                                                            <td><?php echo ($value["phone_number"]); ?></td>
                                                            <td><?php echo ($value["weixinid"]); ?></td>
                                                            <td><?php echo ($value["cover"]); ?></td>
                                                            <td><?php echo (date("y/m/d H:s",$value["start_time"])); ?></td>
                                                            <td><?php echo (date("y/m/d H:s",$value["end_time"])); ?></td>

                                                        
                                                        </tr><?php endforeach; endif; ?>
						</tbody>
					</table>
<div class="pagination" align="center"><?php echo ($page); ?></div>