<?php if (!defined('THINK_PATH')) exit();?>
    <?php if(is_array($list)): foreach($list as $key=>$goods): ?><li class="goods-list">
        <a class="goods-list-content" href="<?php echo ($goodsDetailUrl); ?>?goodsId=<?php echo ($goods["id"]); ?>">
            <span class="thumbnail"><img width="" height=""  src="<?php echo ($rootPath); ?>/Uploads/Picture/Goods/2.jpg" alt="..."></span>
        <table class="list-table">
            <tr>
                <td colspan="2" class="goods-name" ><?php echo ($goods["name"]); ?></td>
            </tr>
            <tr>
                <td class="list-col1 goods-deliver" >
                    <img width="20px" height="20px" src="<?php echo ($rootPath); ?>/Uploads/Picture/Country/1.png" alt="..." class="img-circle">
            <?php if(($goodslogistics_mode) == "0"): ?>海外直邮<?php else: ?>保税直邮<?php endif; ?>
                </td>
                <td class="list-col2 arrive-days" >预计到手：<?php echo ($goods["arrive_days"]); ?></td>
            </tr>
            <tr>
                <td class="list-col1 international_price ">海外同步价：<?php echo ($goods["international_price"]); ?></td>
                <td class="list-col2 purchasing_price">代购参考价：<?php echo ($goods["purchasing_price"]); ?></td>
            </tr>
            <tr>
                <td class="list-col1 policy">优惠政策：<?php echo ($goods["policy"]); ?></td>
                <td class="list-col2 customs">关税：<?php echo ($goods["customs"]); ?></td>
            </tr>
            <tr>
                <td class="list-col1" direct_ratio>直销返点：<?php echo ($goods["direct_ratio"]); ?></td>
                <td class="list-col2" line_ratio>线销返点：<?php echo ($goods["line_ratio"]); ?></td>
            </tr>
        </table>
        </a>
        <div class="clr"></div>
    </li><?php endforeach; endif; ?>