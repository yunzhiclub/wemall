<?php if (!defined('THINK_PATH')) exit();?>
<h4>查看历史业绩</h4>
<table class="table table-bordered table-striped table-hover">
    <tr>
    <th>序号</th>
    <th>描述</th>
    <th>开始时间</th>
    <th>结束时间</th>
    </tr>
    <?php if(is_array($data)): foreach($data as $key=>$value): ?><tr class="">
        <td><?php echo ($value["id"]); ?></td> 
        <td><a href="<?php echo ($value["actionUrl"]["viewUrl"]); ?>"><?php echo ($value["describe"]); ?></a></td> 
        <td><?php echo (date('Y-m-d',$value["begin_time"])); ?></td> 
        <td><?php echo (date('Y-m-d',$value["end_time"])); ?></td> 

    </tr><?php endforeach; endif; ?>
</table>
<div class="pagination"><?php echo ($page); ?></div>