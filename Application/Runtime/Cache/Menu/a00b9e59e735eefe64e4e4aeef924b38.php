<?php if (!defined('THINK_PATH')) exit();?><h4><a href="<?php echo ($addRootMenuUrl); ?>">添加根菜单</a></h4>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>序号</th>
            <th>标题</th>
            <th>URL</th>
            <th>开发模式</th>
            <th>排序</th>
            <th>隐藏</th>
            <th>操作</th>
        </tr>
    </thead>
    <?php $i=1; ?>
    <tbody>
    <?php if(is_array($MenuList)): foreach($MenuList as $key=>$value): ?><tr>
        <td><?php echo ($i++); ?></td>
        <td style="text-align: left"><?php for($j=0;$j<$value['level'];$j++) echo '|--'; echo ($value["title"]); ?></td>
        <td><?php echo ($value["module"]); ?>/<?php echo ($value["controller"]); ?>/<?php echo ($value["action"]); ?></td>
        <td><?php if($value["is_dev"] == 0): ?>否<?php else: ?>是<?php endif; ?></td>
        <td><?php echo ($value["sort"]); ?></td>
        <td><?php if($value["hide"] == 0): ?>否<?php else: ?>是<?php endif; ?></td>
    <td>
        <a href="<?php echo ($value["actionUrl"]["edit"]); ?>">
            <button class="btn btn-mini btn-primary" type="button">
                编辑
            </button>
        </a>
        <a href="<?php echo ($value["actionUrl"]["addSub"]); ?>">
            <button class="btn btn-mini" type="button">
                添加子菜单
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