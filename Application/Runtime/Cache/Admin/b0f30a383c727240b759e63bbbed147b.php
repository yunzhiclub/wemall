<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="en"><!--<![endif]--> 
	<head>
		<meta charset="utf-8">
		<title>登陆</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="<?php echo add_root_path('/theme/Akira/css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo add_root_path('/theme/Akira/css/bootstrap-responsive.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo add_root_path('/theme/Akira/css/site.css'); ?>" rel="stylesheet">
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	</head>
        <body>
    <div id="login-page" class="container">
        <h5><?php echo ($error); ?></h5>
        <form id="login-form" class="well" method="post" action="<?php echo ($url); ?>">
        <input type="text" class="span2" name="name" placeholder="Name" /><br />
        <input type="password" class="span2" name="password" placeholder="Password" /><br />
        <label class="checkbox"> <input type="checkbox" />记住密码</label>
        <button type="submit" class="btn btn-primary">登陆</button>
    </form>	
    </div>
		<script src="<?php echo add_root_path('/theme/Akira/js/jquery.min.js'); ?>"></script>
		<script src="<?php echo add_root_path('/theme/Akira/js/bootstrap.min.js'); ?>"></script>
		<script src="<?php echo add_root_path('/theme/Akira/js/site.js'); ?>"></script>
	</body>
</html>