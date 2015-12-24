<?php if (!defined('THINK_PATH')) exit();?><script>  
    var totalCount = $("#totalCount").attr("data-value");//商品总数量
    var coupleFee = 0; //优惠总金额
    var count = 0;//前当选择数量
    var flag = "order"; //用于区分用户点击页面
    $(".coupon").click(function(){
        var totalPayable = $("#totalPayable").attr("data-value"); //应付总金额
        var fee = parseInt($(this).attr("data-value"));
        if($(this).is(":checked") == true)
        {
            if(count == totalCount)
            {
                $(this).attr("checked",false);
                return;
            }
            count++;
            coupleFee += fee;
        }
        else
        {
           count--; 
           coupleFee -= fee;
        }       
        $(".couponTotal").text(count);
        totalPayable -= coupleFee;
        $("#couponFee").text("￥" + format_money(coupleFee));
        if(totalPayable > 0)
        {
            $("#totalPayable").text(format_money(totalPayable));   
        }
        else
        {
            $("#totalPayable").text("0.01");
        }
    });
    
/*
 * 提交操作
 * 1.取IDS counts
 * 2.取收货地址三个信息
 * 3.取身份证号码
 * 4.取上传身份证后微信返回ID
 * 5.遍历优惠券信息
 * 6.取支付方式
 */
function submit()
{
    var ids = "<?php echo ($ids); ?>";
    var counts = "<?php echo ($counts); ?>";
    var addressId = $("#addressId").attr("data-value");
    if(addressId == '')
    {
        alert("请选择收货地址");
        return false;
    }
    $("#Submit").attr("disabled","disabled");
    $("section").showLoading();
    var coupons = new Array();
    
    //遍历取优惠券使用信息
    $(".coupon").map(function(){
        if($(this).is(":checked") == true)
        {
            coupons.push($(this).attr("data-id"));
        }
    });
    coupons = coupons.join("-");
    var url = "<?php echo ($url); ?>"+"?ids="+ids+"&counts="+counts;
    url += "&addressId="+addressId;
    url += "&coupons="+coupons;    
    //alert(url);
    
    location.href= url;
}


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
    /*刷新后取消选中*/
    $(document).ready(function(){
        $(".coupon").map(function(){
            $(this).attr("checked",false);
            $('#Submit').removeAttr("disabled");
        });
    });
    
    /*
     *调用地址管理
     */
    function showAddress()
    {
        $(".navbar-fixed-bottom").hide();
        var url = "<?php echo ($address["chooseAddressUrl"]); ?>?openid=<?php echo ($openid); ?>";
        $(".headerTitle").text("选择收货地址");
        flag = "showAddress";
        $("section").showLoading();
        $("section").hide();
        $.get(
            url,
            function(data){
                $("body").append(data);
                $("section").hideLoading();           
            }
        );
    }
    
    //添加收货地址
    function addAddress($this)
    {
        flag = "addAddress";
        $(".headerTitle").text("添加收货地址");
        var url = "<?php echo ($data["addUrl"]); ?>?openid=<?php echo ($openid); ?>";
        getEditHtml(url);
         
    }

    //编辑收货地址
    function editAddress($this)
    {
        flag = "editAddress";
        $(".headerTitle").text("编辑收货地址");
        var id = $this.attr("data-id");
        var url = "<?php echo ($data["editUrl"]); ?>?id="+id;
        getEditHtml(url);
    }
    
    //删除收货地址
    function deleteAddress($this)
    {
        var id=$this.attr("data-id");
        var url = "<?php echo ($data["deleteUrl"]); ?>?id="+id;
        var r=confirm("删除后将无法恢复");
        if (r==true)
        {
            $(".addressChoose").showLoading();
            $.get(
                url,
                function(data){
                    $("#address-"+id).remove();
                    $(".addressChoose").hideLoading();
                }
            );
        }
    }
    
    /*
     * 根据URL获取html信息
     */
    function getEditHtml(url)
    {
        $(".navbar-fixed-bottom").hide();
        $(".addressChoose").showLoading();
        $.get(
            url,
            function(data){
                $(".addressChoose").hideLoading();
                $(".addressChoose").hide();
                $("body").append(data);
            }
        );
    }
    
    //后退操作
    function back($this)
    {
        if(flag == 'order')
        {
            location.href= "<?php echo ($indexUrl); ?>";
        }
        else if(flag == 'showAddress')
        {
            $(".addressChoose").remove();
            $("section").show();
            $(".navbar-fixed-bottom").show();
            flag = 'order';
            $(".headerTitle").text("确认订单");
        }
        else
        {
            $(".addressEdit").remove();
            $(".addressChoose").show();
            flag = 'showAddress';
            $(".headerTitle").text("选择收货地址");
        }
    }
    
    /*
     * 
     */
    function addAddressInfo($this)
    {
        var id = $this.attr("data-id");
        var openid = $this.attr("data-openid");
        var name = $("#name").val();
        var phone = $("#phone").val();
        var provice = $("#provice").val();
        var city = $("#city").val();
        var address = $("#address").val();
        var id_no = $("#id_no").val();
        var frontid = $("#frontid").attr("data-value");
        var backid = $("#backid").attr("data-value");
        //进行较验
        if(name == '')
        {
            alert("收货人姓名不能为空");
            return false;
        }
        
        if( !validatemobile(phone) )
        {
            return false;
        }
        
        if(provice == '')
        {
            alert("省份不能为空");
            return false;
        }
        
        if(city == '')
        {
            alert("城市不能为空");
            return false;
        }
        
        if(address == '')
        {
            alert("详细地址不能为空");
            return false;
        }
        
        if( !IdCardValidate(id_no) )
        {
            alert("请输入正确的身份证号码");
            return false;
        }
        
        if(frontid == '')
        {
            alert("请上传身份证正面照片");
            return false;
        }
        
        if(backid == '')
        {
            alert("请上传身份证反面照片");
        }
        $(".addressEdit").showLoading();
        var url = "<?php echo ($data["saveAddressUrl"]); ?>?id="+id+"&openid="+openid+"&name="+name+"&phone="+phone+"&provice="+provice+"&city="+city+"&address="+address+"&id_no="+id_no+"&frontid="+frontid+"&backid="+backid;
        $.get(
            url,
            function(id){
                var urlShow = "<?php echo ($address["chooseAddressUrl"]); ?>?openid=<?php echo ($openid); ?>";
                flag = "showAddress";
                $.get(
                    urlShow,
                    function(data){
                        $(".addressChoose").remove();
                        $(".addressEdit").remove();
                        $("body").append(data);
                        $(".addressEdit").hideLoading();           
                    }
                );
            }
                );
        
    }
    
    /*
     * 用户选择完地址后，返回
     * @param {type} $this
     * @returns {undefined}
     */
    function chooseAddress($this)
    {
        var id = $this.attr("data-value");
        $(".addressChoose").showLoading();
        
        //更新数据库为最近使用的记录
        var url = "<?php echo ($data["updateAddressUrl"]); ?>?id="+id;
        $.get(
            url,
            function(data){
               $(".addressChoose").hideLoading();
               $(".addressChoose").remove(); //去除选择收货地址
               $(".address").html(data);     //添加收化地址详情
               $("section").show();          //显示主体界面 
               flag = "order";               //置状态字              
            }
        );
        $(".navbar-fixed-bottom").show();
    }
    
    
    /*
     * 代金券下拉函数
     */
    function onFreight(){
        $("#daijinquan-box").toggle(500);
        $("#glyphicon-chevron-right").toggle();
        $("#glyphicon-chevron-down").toggle();
        
    }
    
    /*
     * 较验手机号
     */
    function validatemobile(mobile)
    {
        var length = trim(mobile).length;
        if(length === 0)
        {
           alert('请输入手机号码！');
           return false;
        }    
        if(length !== 11)
        {
            alert('请输入有效的手机号码！');
            return false;
        }
        return true;        
    }
    
    /*
     * 
     * 以下为校验身份证号是否正确
     */
    
    var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];    // 加权因子   
    var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];            // 身份证验证位值.10代表X   
    function IdCardValidate(idCard) { 
    idCard = trim(idCard.replace(/ /g, ""));               //去掉字符串头尾空格                     
    if (idCard.length == 15) {   
        return isValidityBrithBy15IdCard(idCard);       //进行15位身份证的验证    
    } else if (idCard.length == 18) {   
        var a_idCard = idCard.split("");                // 得到身份证数组   
        if(isValidityBrithBy18IdCard(idCard)&&isTrueValidateCodeBy18IdCard(a_idCard)){   //进行18位身份证的基本验证和第18位的验证
            return true;   
        }else {   
            return false;   
        }   
    } else {   
        return false;   
    }   
}   
/**  
 * 判断身份证号码为18位时最后的验证位是否正确  
 * @param a_idCard 身份证号码数组  
 * @return  
 */  
function isTrueValidateCodeBy18IdCard(a_idCard) {   
    var sum = 0;                             // 声明加权求和变量   
    if (a_idCard[17].toLowerCase() == 'x') {   
        a_idCard[17] = 10;                    // 将最后位为x的验证码替换为10方便后续操作   
    }   
    for ( var i = 0; i < 17; i++) {   
        sum += Wi[i] * a_idCard[i];            // 加权求和   
    }   
    valCodePosition = sum % 11;                // 得到验证码所位置   
    if (a_idCard[17] == ValideCode[valCodePosition]) {   
        return true;   
    } else {   
        return false;   
    }   
}   
/**  
  * 验证18位数身份证号码中的生日是否是有效生日  
  * @param idCard 18位书身份证字符串  
  * @return  
  */  
function isValidityBrithBy18IdCard(idCard18){   
    var year =  idCard18.substring(6,10);   
    var month = idCard18.substring(10,12);   
    var day = idCard18.substring(12,14);   
    var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
    // 这里用getFullYear()获取年份，避免千年虫问题   
    if(temp_date.getFullYear()!=parseFloat(year)   
          ||temp_date.getMonth()!=parseFloat(month)-1   
          ||temp_date.getDate()!=parseFloat(day)){   
            return false;   
    }else{   
        return true;   
    }   
}   
  /**  
   * 验证15位数身份证号码中的生日是否是有效生日  
   * @param idCard15 15位书身份证字符串  
   * @return  
   */  
  function isValidityBrithBy15IdCard(idCard15){   
      var year =  idCard15.substring(6,8);   
      var month = idCard15.substring(8,10);   
      var day = idCard15.substring(10,12);   
      var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
      // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法   
      if(temp_date.getYear()!=parseFloat(year)   
              ||temp_date.getMonth()!=parseFloat(month)-1   
              ||temp_date.getDate()!=parseFloat(day)){   
                return false;   
        }else{   
            return true;   
        }   
  }   
//去掉字符串头尾空格   
function trim(str) {   
    return str.replace(/(^\s*)|(\s*$)/g, "");   
}  
alert("i m here");
</script>