<?php if (!defined('THINK_PATH')) exit();?><footer>
<ul class="footer-menu-box">
    <li class="home"><a href="<?php echo ($indexUrl); ?>"><span class="ico"></span></a></li>
    <li class="cart"><a href="<?php echo ($shoppingCartUrl); ?>"><span class="ico"></span></a></li>
    <li class="user"><a href="<?php echo ($userCenterUrl); ?>"><span class="ico"></span></a></li>

</ul>
</footer>
<script>
    $(".footer-menu-box li a").click(function(e){
        $(".footer-menu-box li a").removeClass("on");
        $(this).addClass("on");
    });
</script>