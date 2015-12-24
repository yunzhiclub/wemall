<?php if (!defined('THINK_PATH')) exit();?><script>
function sendGift()
{
    var openid = $("#openid").val();
    var minValue = $("#minValue").val();
    var maxValue = $("#maxValue").val();
    var url = "<?php echo ($getUrl); ?>?openid="+openid+"&minValue="+minValue+"&maxValue="+maxValue;
    $.get(   
        url,
        function(data)
        {
            alert(data);
        }
    );
}
</script>