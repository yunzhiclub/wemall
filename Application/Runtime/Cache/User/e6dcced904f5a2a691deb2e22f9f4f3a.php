<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">    
    var openid = "<?php echo ($openid); ?>";
    $(document).ready(function(){
        $("#phone").focus();
        $("nav").hide();
    });
      
    
    function checkOrder()
    {		
        var phone = $('#phone').val();	
        var account = $('#account').val();
        if(checkMobile(phone) == false)
        {
            alert("手机格式有误");
            $('#phone').val();
            return false;
        }
        var url = "<?php echo ($changeUrl); ?>?newphone="+phone+"&openid="+openid+"&account="+account;
        $(".container-fluid").showLoading();
        $.get(url,
            function(data){
                if(data == 'success')
                {
                    alert("修改成功");
                    var gotoUrl = "<?php echo ($gotoUrl); ?>";
                    location=gotoUrl;
                }
                else
                {
                    alert(data);
                    $(".container-fluid").hideLoading();
                    return false;
                }
            });
          
    };	

/*
 * 较验手机号
 */
    function checkMobile(str) {
        var reg =/^(13[0-9]{9})|(15[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(18[0-9]{9})$/
        if (reg.test(str) === false) {
            return false;
        }
        else
        {
            return true;
        }
    }
</script>