<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-striped">
    <thead>
            <tr>
                    <th>序号</th>
                    <th>持有用户</th>
                    <th>发放时间</th>
                    <th>过期时间</th>
                    <th>使用时间</th>
                    <th>面额</th>
                    <th>状态</th>
                    <th>操作</th>
            </tr>
    </thead>
    <tbody>
             <?php $i=1; ?>
            <?php if(is_array($sent)): foreach($sent as $key=>$value): ?><tr>
                <td><?php echo ($i++); ?></td>
                <td><?php echo ($value["_openid"]["nickname"]); ?></td>
                <td><?php echo (date("y/m/d H:s",$value["start_time"])); ?></td>
                <td><?php echo (date("y/m/d H:s",$value["end_time"])); ?></td>
                <td><?php if(($value["use_time"]) == "0"): ?>-<?php else: echo (date("y/m/d H:s",$value["use_time"])); endif; ?></td>
                <td><?php echo (format_money($value["cover"])); ?></td>
                <td><?php if(($value["is_freezed"]) == "0"): ?>正常<?php else: ?>冻结<?php endif; ?></td>
                <td><a href="<?php echo ($value["url"]["freezeUrl"]); ?>"><?php if(($value["is_freezed"]) == "0"): ?>冻结<?php else: ?>解冻<?php endif; ?></a></td>
            </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div class="pagination" align="center"><?php echo ($page); ?></div>