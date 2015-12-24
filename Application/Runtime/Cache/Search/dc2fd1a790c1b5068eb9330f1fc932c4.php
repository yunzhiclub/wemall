<?php if (!defined('THINK_PATH')) exit();?>
    <?php if(is_array($list)): foreach($list as $key=>$goods): ?><div class="row1 goods-list">
        <a class="goods-list-content" href="<?php echo ($goodsDetailUrl); ?>?goodsId=<?php echo ($goods["id"]); ?>">
            <span class="thumbnail"><img width="" height=""  src="<?php echo ($goods["image_path"]); ?>" alt="..."></span>
        <table class="list-table">
            <tr>
                <td colspan="2" class="goods-name" >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($goods["name"]); ?></td>
            </tr>
            <tr>
                <td class="list-col1 goods-deliver" >
                    <img width="20px" height="20px" src="<?php echo ($rootPath); ?>/Uploads/Picture/Country/1.png" alt="" class="img-circle">
            <?php if(($goodslogistics_mode) == "0"): ?>海外直邮<?php else: ?>保税直邮<?php endif; ?>
                </td>
                <td class="list-col2 arrive-days" >预计到手天数：<?php echo ($goods["arrive_days"]); ?>天</td>
            </tr>
            <tr>
                <td class="list-col1 international_price ">海外同步价：<?php echo format_money($goods['international_price']); ?>元</td>
                <td class="list-col2 purchasing_price">&nbsp;&nbsp;代购参考价：<?php echo format_money($goods['purchasing_price']); ?>元</td>
            </tr>
            <tr>
                <td class="list-col1 policy">&nbsp;&nbsp;优惠政策：<?php echo ($goods["discount_way"]); ?></td>
                <td class="list-col2 customs">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;关税：<?php echo format_money($goods['customs']); ?>元</td>
            </tr>
            <?php if(($openid) > "null"): ?><tr>
                <td class="list-col1" direct_ratio>&nbsp;&nbsp;直销返点：<?php echo ($goods['direct_selling_commission']); ?>%</td>
                <td class="list-col2" line_ratio>&nbsp;&nbsp;&nbsp;&nbsp;线销返点：<?php echo ($goods['line_selling_commission']); ?>%</td><?php endif; ?>
            </tr>
        </table>
        </a>
        <div class="clr"></div>
    </div><?php endforeach; endif; ?>

<script>
    /**
     * 将以分为单位的数字进行货货格式化
     * @param num 数值(Number或者String)
     * @num 12343242324
     * @return 金额格式后的字符串:123,432,423.24
     * @type String
     * 1.先判断位数.如果为1则前面0.0
     * 如果为2,则前面补0.
     * 如果3位以上,以进行格式化
     */
    function format_money(num) {
        var num = num.toString().replace(/\$|\,/g,'');
        if(isNaN(num))
            return '0.00';
        if(num.length > 2)
        {
            //截取分
            var cents = num.substring(num.length-2,num.length);
            //取元
            num = Math.floor(num/100).toString();
            for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
                num = num.substring(0,num.length-(4*i+3))+','+
                num.substring(num.length-(4*i+3));
            var res = (num + '.' + cents);
            return res;  
        }
        else
        {
            if(num.length == 2)
            {
                return '0.'+num;
            }
            else
            {
                return '0.0'+num;
            }
        } 
    }
</script>