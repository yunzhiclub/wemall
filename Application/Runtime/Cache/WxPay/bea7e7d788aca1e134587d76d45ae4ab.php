<?php if (!defined('THINK_PATH')) exit();?>
<script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo ($jsApiParameters); ?>,
            function(res){
               WeixinJSBridge.log(res.err_msg);
               if(res.err_msg == 'get_brand_wcpay_request:ok')
               {
                   window.location.href="<?php echo ($actionUrl["success"]); ?>";
               }
               else
               {
                  alert("支付未完成，错误信息:"+res.err_msg);
                   window.location.href="<?php echo ($actionUrl["error"]); ?>";
               }
            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }
    //启动支付
    callpay();
</script>