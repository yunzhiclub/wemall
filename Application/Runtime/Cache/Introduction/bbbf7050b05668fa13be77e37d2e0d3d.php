<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="en"><!--<![endif]--> 
	<head>
            <meta charset="utf-8">
            <title><?php echo ($YZTitle); ?></title>
            <?php if(is_array($YZCss)): foreach($YZCss as $key=>$value): ?><link href="<?php echo ($value); ?>" rel="stylesheet"><?php endforeach; endif; ?>
            <?php if(is_array($YZJs)): foreach($YZJs as $key=>$value): ?><script type="text/javascript" src="<?php echo ($value); ?>" ></script><?php endforeach; endif; ?>
            <?php if(is_array($YZHead)): foreach($YZHead as $key=>$value): echo ($value); endforeach; endif; ?>
        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    </head>
	<body>
		<div class="container">
			<div class="navbar">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> 
						<div class="nav-collapse">
                                                   <?php echo ($YZMenu); ?>
                                                </div>
					</div>
				</div>
			</div>
                        <div class="row">
				<div class="span3">
					<div class="well" style="padding: 8px 0;">
                                            
                                            <?php echo ($YZLeft); ?>
                                            </div>
				</div>
                                <div class="span9">
                                    
    <?php echo ($YZRight); ?>

                                </div>
            <div class="clr"></div>
        </div>
    </div>
    </body>
    <?php echo ($YZFooterJs); ?>
</html>