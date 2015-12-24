<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">


<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>

<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<title><?php echo ($YZTitle); ?></title>
<?php if(is_array($YZCss)): foreach($YZCss as $key=>$value): ?><link href="<?php echo ($value); ?>" rel="stylesheet" type="text/css"><?php endforeach; endif; ?>
        <?php if(is_array($YZJs)): foreach($YZJs as $key=>$value): ?><script type="text/javascript" src="<?php echo ($value); ?>" ></script><?php endforeach; endif; ?>
        
        <?php echo ($YZHead); ?>
</head>
<body>
    <?php echo ($header); ?>
    
    <?php echo ($YZBody); ?>
    <?php echo ($footer); ?>
    
    
</body>
</html>