<?php if (!defined('THINK_PATH')) exit();?><nav class="navbar navbar-default navbar-fixed-bottom text-center vertical-middle-sm" role="navigation">
        <div class="col-xs-4"><a href="<?php echo ($indexUrl); ?>"><span class="glyphicon glyphicon-home" aria-hidden="true"></span><br /><span class="glyphicon-class">首页</span></a></div>
        <div  class="col-xs-4"><a href="<?php echo ($shoppingCartUrl); ?>"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span><br /><span class="glyphicon-class">购物车</span></a></div>
        <div  class="col-xs-4"><a href="<?php echo ($userCenterUrl); ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><br /><span class="glyphicon-class">个人中心</span></a></div>
</nav>
<script>
    $(".footer-menu-box li a").click(function(e){
        $(".footer-menu-box li a").removeClass("on");
        $(this).addClass("on");
    });
</script>