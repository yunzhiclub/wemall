<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff;color: #333; font-size: 16px; }
.system-message{ padding: 24px 48px; margin-left: 10px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 0px; padding-left: 20px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message{ line-height: 2.6em; font-size: 30px;float:left; }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
.system-border {width:80%;margin: auto; margin-top: 20%; padding-top: 10px;padding-bottom: 20px;padding-left: 16px;padding-right: 10px;background-color: #F5F5F5; border:1px solid #E3E3E3;border-radius: 10px}
.system-content{font-family:"Arial","Microsoft YaHei","黑体","宋体",sans-serif;;font-size: 14px;}
.img{float:left;width:90px;}
.error{line-height: 1.9em; font-size: 22px;float:left; }
.p{}
.clr{clear:both;}
</style>
<div class="system-message system-border system-content">
<?php if($status == error) {?>
    <div class="img"><img width="80px" src=" <?php echo add_root_path("/theme/wemall/images/fail_f.png"); ?>"/></div>
    <div class="error"><?php echo $tips ?></div>
    <div class="clr"></div>
<?php }else{?>
    <div class="img"><img width="80px" src="<?php echo add_root_path("/theme/wemall/images/success_f.png"); ?>"/></div>
    <div class="success"><?php echo $tips ?></div>
    <div class="clr"></div>
<?php }?>
<p class="detail"></p>
<div class="p"><p class="jump">
系统将在<b id="wait"><?php echo ($waitSecond); ?></b>秒后为您<a id="href" href="<?php echo ($jumpUrl); ?>">跳转</a>。请稍候。
</p></div>
<!--
<?php echo ($tips); ?>
<p class="jump">
系统将在<b id="wait"><?php echo ($waitSecond); ?></b>秒后为您<a id="href" href="<?php echo ($jumpUrl); ?>">跳转</a>。请稍候。
</p>
-->
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>