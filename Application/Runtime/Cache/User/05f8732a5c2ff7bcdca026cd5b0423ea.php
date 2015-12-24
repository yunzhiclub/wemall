<?php if (!defined('THINK_PATH')) exit();?><h4><a>用户列表</a></h4>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>序号</th>
            <th>用户名</th>
            <th>姓名</th>
            <th>邮箱</th>
            <th>手机</th>   
            <th>类别</th>
            <th>操作</th>
        </tr>
    </thead>
    <?php $i=1; ?>
    <tbody>
    <?php if(is_array($UserList)): foreach($UserList as $key=>$value): ?><tr>
            <td><?php echo ($i++); ?></td>
            <td><?php echo ($value["userid"]); ?></td>
            <td><?php echo ($value["name"]); ?></td>
            <td><?php echo ($value["email"]); ?></td>
            <td><?php echo ($value["phone"]); ?></td>
            <td><?php echo ($value["Identity"]); ?></td>
            <td>
                <a href="<?php echo ($value["actionUrl"]["edit"]); ?>">
                    <button class="btn btn-mini btn-primary" type="button">
                        修改
                    </button>
                </a>
                <a href="<?php echo ($value["actionUrl"]["reset"]); ?>">
                    <button class="btn btn-mini" type="button">
                        重置密码
                    </button>
                </a>
                <a href="<?php echo ($value["actionUrl"]["delete"]); ?>">
                    <button class="btn btn-mini btn-danger" type="button">
                        删除
                    </button>
                </a>
            </td>
        </tr><?php endforeach; endif; ?>
</tbody>

</table>
<div class="pagination"><?php echo ($page); ?></div>