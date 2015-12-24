<?php if (!defined('THINK_PATH')) exit();?><table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>商品号</th>
            <th>商品名称</th>
            <th>海外同步价</th>
            <th>已销数量</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; ?>
       <?php if(is_array($MenuList)): foreach($MenuList as $key=>$value): ?><tr>
                <td><?php echo ($i++); ?></td>
                <td><?php echo ($value["name"]); ?></td>
                <td><?php echo format_money($value['international_price']); ?></td>
            <td><?php if(($$value["weight"]) == ""): ?>0<?php else: ?>1<?php endif; ?></td>    
            <td>
                <a href="<?php echo ($value["actionUrl"]["edit"]); ?>">
                    <button class="btn btn-mini btn-primary" type="button">
                        编辑
                    </button>
                </a>
                <a href="<?php echo ($value["actionUrl"]["delete"]); ?>">
                    <button class="btn btn-mini btn-danger" type="button">
                        删除
                    </button>
                </a>
                <a href="<?php echo ($value["actionUrl"]["underSheleves"]); ?>">
                     <button class="btn btn-mini btn-info" type="button">
                         下架
                     </button>
                </a>
            </td>
            </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div class="pagination"><?php echo ($page); ?></div>