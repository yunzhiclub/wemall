<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-striped table-hover">
    <thead>
            <tr>
                    <th>昵称</th>                                                     
                    <th>面额</th>
                    <th>发放时间</th>
                    <th>使用时间</th>
            </tr>
    </thead>
    <tbody>
             <?php $i=1; ?>
            <?php if(is_array($used)): foreach($used as $key=>$value): ?><tr>
                <td><?php echo ($value["_openid"]["nickname"]); ?></td>
                <td><?php echo (format_money($value["cover"])); ?></td>
                <td><?php echo (date("y/m/d H:s",$value["start_time"])); ?></td>
                <td><?php echo (date("y/m/d H:s",$value["use_time"])); ?></td>
            </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div class="pagination" align="center"><?php echo ($page); ?></div>