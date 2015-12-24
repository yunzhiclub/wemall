<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ padding: 24px 48px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 0px; padding-left: 98px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 37px;float:left; }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
.system-border {width:23%;margin: auto; margin-top: 10%; padding-bottom: 40px;background-color: #F5F5F5; border:1px solid #E3E3E3;border-radius: 10px}
.system-content{font-family:"Arial","Microsoft YaHei","黑体","宋体",sans-serif;;font-size: 14px;}
.img{float:left;width:100px;}
.p{}
.clr{clear:both;}
</style>
</head>
<body>
<div class="system-message system-border system-content">
<?php if(isset($message)) {?>
<div class="img"><img src="<php>echo add_root_path("/theme/wemall/images/success.png");</php>"/></div>
<div class="success"><?php echo($message); ?></div>
<div class="clr"></div>
<?php }else{?>
<div class="img"><img src="<php>echo add_root_path("/theme/wemall/images/fail.png");</php>" /></div>
<div class="error"><?php echo($error); ?></div>
<div class="clr"></div>
<?php }?>
<p class="detail"></p>
<div>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p>
</div>
</div>
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
</body>
</html>
