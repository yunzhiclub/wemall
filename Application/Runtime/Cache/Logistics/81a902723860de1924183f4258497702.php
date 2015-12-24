<?php if (!defined('THINK_PATH')) exit();?><a href="<?php echo ($actionUrl); ?>"><h4>添加物流方式</h4></a>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>序号</th>
            <th>物流方式</th>
            <th>物流代码</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1; ?>
    <?php if(is_array($Log)): foreach($Log as $key=>$value): ?><tr>
            <td><?php echo ($i++); ?></td>
            <td><?php echo ($value['name']); ?></td>
            <td><?php echo ($value['mode']); ?></td>  
            <td>
                <a onclick="#" href="<?php echo ($value["actionUrl"]["edit"]); ?>">
                    <button class="btn btn-mini btn-primary" type="button">
                        修改
                    </button>
                </a>
                <a onclick="#" href="<?php echo ($value["actionUrl"]["delete"]); ?>">
                    <button class="btn btn-mini btn-danger" type="button">
                        删除
                    </button>
                </a>
            </td>
        </tr><?php endforeach; endif; ?>
</tbody>
</table>
<div class="pagination"><?php echo ($page); ?></div>