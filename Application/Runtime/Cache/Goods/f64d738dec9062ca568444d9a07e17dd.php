<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<title><?php echo ($YZTitle); ?></title>
<?php if(is_array($YZCss)): foreach($YZCss as $key=>$value): ?><link href="<?php echo ($value); ?>" rel="stylesheet" type="text/css"><?php endforeach; endif; ?>
        <?php if(is_array($YZJs)): foreach($YZJs as $key=>$value): ?><script type="text/javascript" src="<?php echo ($value); ?>" ></script><?php endforeach; endif; ?>
        
        <?php echo ($YZHead); ?>
</head>
<body>

    
    <div class="container-fluid">
        <?php echo ($header); ?>
        <?php echo ($YZBody); ?>
        <?php echo ($footer); ?>   
    </div>
    
</body>
</html>