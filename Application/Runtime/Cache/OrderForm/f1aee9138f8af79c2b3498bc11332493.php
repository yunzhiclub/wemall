<?php if (!defined('THINK_PATH')) exit();?><script>
$("#dian").focus(function(){
     var txt_value=$(this).val();
     if(txt_value=="请填写收货人姓名需与身份证相同"){
         $(this).val("");
     }
});
$("#dian").blur(function(){
      var txt_value=$(this).val();
       if(txt_value==""){
         
         $(this).val("请填写收货人姓名需与身份证相同");
     } 
    
    
});
$("#phone").focus(function(){
     var txt_value=$(this).val();
     if(txt_value=="联系人手机号码"){
         $(this).val("");
     }
});
$("#phone").blur(function(){
      var txt_value=$(this).val();
       if(txt_value==""){
         
         $(this).val("联系人手机号码");
     } 
    
    
});
$("#provice").focus(function(){
     var txt_value=$(this).val();
     if(txt_value=="所在省份"){
         $(this).val("");
     }
});
$("#provice").blur(function(){
      var txt_value=$(this).val();
       if(txt_value==""){
         
         $(this).val("所在省份");
     } 
    
    
});
$("#city").focus(function(){
     var txt_value=$(this).val();
     if(txt_value=="所在城市"){
         $(this).val("");
     }
});
$("#city").blur(function(){
      var txt_value=$(this).val();
       if(txt_value==""){
         
         $(this).val("所在城市");
     } 
    
    
});
$("#address").focus(function(){
     var txt_value=$(this).val();
     if(txt_value=="详细地址"){
         $(this).val("");
     }
});
$("#address").blur(function(){
      var txt_value=$(this).val();
       if(txt_value==""){
         
         $(this).val("详细地址");
     } 
    
    
});
$("#id_no").focus(function(){
     var txt_value=$(this).val();
     if(txt_value=="身份证号"){
         $(this).val("");
     }
});
$("#id_no").blur(function(){
      var txt_value=$(this).val();
       if(txt_value==""){
         
         $(this).val("身份证号");
     } 
    
    
})


</script>