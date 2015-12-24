<?php if (!defined('THINK_PATH')) exit();?><a href="<?php echo ($addSlideUrl); ?>"><h4>添加</h4></a>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>名称</th>
            <th>图片</th>
            <th>商品</th>
            <th>权重</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1; ?>
    <?php if(is_array($SlideShow)): foreach($SlideShow as $key=>$value): ?><tr>
            <td><?php echo ($value['slide_name']); ?></td>
            <td><img src="<?php echo ($value["attchment_id"]["url"]); ?>" style="max-width: 90px;"></img></td>
            <td><?php echo ($value['describe']); ?></td>
            <td><?php echo ($value['weight']); ?></td>
            <td><?php echo ($value['status']); ?></td>
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