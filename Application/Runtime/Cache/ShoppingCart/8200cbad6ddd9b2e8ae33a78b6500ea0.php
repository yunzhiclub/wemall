<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" language="javascript">
    //1个int,记录总金额
    //1个url,订单提交的链接

    totalCount = 0;
    totalFee = 0;
    submitUrl = '<?php echo ($submitUrl); ?>';
    /*
     * 增加或是减少商品
     * 1.取上级元素的信息
     * 2.增加或减少商品数量
     * 4.达到购买上限时,不能点击
     * 10.改变合计金额
     */
    function change_num($this)
    {
        $(".shopping-cart").showLoading();
        var url = "<?php echo ($actionUrl["changeCountUrl"]); ?>";
        var good = $this.parents(".line");
        var count = parseInt(good.attr("data-count"));
        var price = parseInt(good.attr("data-price"));
        var id = good.attr("data-id");
        var type = $this.attr("data-type");
        var buyMinCount = good.attr("data-mincount");
        var buyMaxCount = good.attr("data-maxcount");
        var beforCount= count;
        //添加商品
        if (type === 'add') {   
            if(count < buyMaxCount)
            {
                count++;
            }
        } else {
            if(type === 'red')
            {
                if (count != 1 && count > buyMinCount) {
                    count--;
                }     
            }
            else
            {
                if($this.val() > buyMaxCount)
                {
                    $this.val(buyMaxCount);
                }
                else
                {
                    if($this.val() < buyMinCount)
                    {
                        $this.val(buyMinCount);
                    }
                }
                count = $this.val();
            }
        }
        $("#count"+id).val(count);
        good.attr('data-count',count);
        totalFee += (count-beforCount)*price;
        totalCount += (count-beforCount);
        url = url+'?count='+count+'&id='+id;
        $.get(url,function(data,state){
            $(".shopping-cart").hideLoading();
        }); 
        update();//更新信                   
    }
 
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
    /*
     * 更新每个产品的小计金额
     * 1.选中当前选中的checkedbox
     * 2.未选中不做处理
     * 3.选中将id,count添加到数组中
     * 4.计算出总的金额,并输出到合计
     */
    $(document).ready(function(){
        $goods = $(".line");
        $goods.each(function(){
            var count = parseInt($(this).attr("data-count"));
            var price = parseInt($(this).attr("data-price"));
            var subTotal = count*price;
            totalFee += subTotal;
            totalCount += count;
        });
        //添加购物车总数量
        $("#totalCount").text($goods.length);
        $(".number").attr("disabled","disabled");
        update();//更新信息
    });
    /*
     *删除商品
     *1. 取出商品ID
     *2. $.GET商品ID
     *3. 取返回值
     *4. 如果为成功，则将该行记录删除
     *5. 如果无兄弟元素，则将该物流方式的商品删除 
     *6. 检测是否被选中，被选中更新IDS,COUNTS，TOTALFEE
     *7. 
     *8. 更新数据
     * @returns {undefined}
     */
    $(".trash").click(function(){
        $(".shopping-cart").showLoading();
        var good = $(this).parents(".line");
        var id = good.attr("data-id");
        var count = parseInt(good.attr("data-count"));
        var price = parseInt(good.attr("data-price"));
        //提交数据
        var url = "<?php echo ($actionUrl["deleteUrl"]); ?>?id="+id;
        $.get(url,function(data,state){
            $(".shopping-cart").hideLoading();
        });//更新数据       
        totalFee -= count*price;
        totalCount -= count;
        good.remove();
        update();
    });
    /*
     * 清空购物车
     * 1.遍历商品ID
     * 2.转化为JSON.
     * 3.发送数据
     */
    $(".delete-all").click(function(){
        var r = confirm("购物车清空后无法恢复");
        if (r == false)
        {
            return;
        }
        $(".shopping-cart").showLoading();
        var ids = new Array(); 
        $(".line").map(function(){
             ids.push($(this).attr("data-id"));
             $(this).remove();
         });
         var url = "<?php echo ($actionUrl["deleteUrl"]); ?>?type=deleteAll";
         totalFee = 0;
         totalCount = 0;
         update();
         $.get(url,function(data,state){
             $(".shopping-cart").hideLoading();
         });
         $("#pay").attr("href","#");
    });
    /*
     * 利用全局变量,更新以下数据
     * 1.更新用户选择结算的数量
     * 2.更新用户所选商品的总金额
     * 3.更新提交的URL信息
     */
    function update()
    {
        var ids = new Array();
        var counts = new Array();
        var res = format_money(totalFee);
        $("#totalFee").text(res);    
        $("#totalCount").text(totalCount);
        $(".line").map(function(){
            ids.push($(this).attr("data-id"));
            counts.push($(this).attr("data-count"));
        });
        var actionUrl = submitUrl + '?ids=' + ids.join("-") + '&counts=' + counts.join("-");
        if(ids.length == 0)
        {
            $("#pay").attr("href","#");
            $("#pay").attr("disabled","disabled");
            return;
        }
        $("#pay").attr("href",actionUrl);
    }
</script>