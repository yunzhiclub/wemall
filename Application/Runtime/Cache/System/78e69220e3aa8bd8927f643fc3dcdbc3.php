<?php if (!defined('THINK_PATH')) exit();?><h4>    <a href="<?php echo ($sendMenuUrl); ?>">生成菜单</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?php echo ($deleteMenuUrl); ?>">删除菜单</a></h4>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>菜单名</th><th>菜单类型</th><th>回复类型</th><th>回复标题</th><th>回复图片</th><th>回复内容</th><th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php if(is_array($data)): foreach($data as $key=>$value): ?><tr>
                <td><?php echo ($value["title"]); ?></td>
                <td><?php echo ($value["type"]); ?></td>
                <td><?php echo ($value["reply_type"]); ?></td>
                <td><?php echo ($value["reply_title"]); ?></td>
                <td>
                    
                    <a href="<?php echo ($value["reply_image"]["url"]); ?>"><img class="smallPic" src="<?php echo ($value["reply_image"]["url"]); ?>" /></a></td>
                <td><?php echo ($value["reply_content"]); ?></td>
                <td>
                    <?php if(($value["is_dev"]) == "0"): ?><a href="<?php echo ($value["actionUrl"]["edit"]); ?>">
                        <button class="btn btn-mini btn-primary" type="button">
                            修改
                        </button>
                    </a><?php endif; ?>
                </td>
            </tr><?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<style>
    .smallPic{
        width:40px;
    }
</style>