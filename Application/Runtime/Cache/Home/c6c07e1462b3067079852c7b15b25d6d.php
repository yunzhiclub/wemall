<?php if (!defined('THINK_PATH')) exit();?><header><?php echo ($logo); ?></header>
<div id="viewport" class="viewport">
    <div class="banner"><?php echo ($banner); ?></div>
    <div class="search_box"><?php echo ($search); ?></div>
    <div class="cover"></div>
    <div class="search">       
        <div class="card card-list">
            <div class="col0">
                <?php echo ($list); ?>
            </div>
        </div>
    </div>
    <div class="btn-box" role="group" ><?php echo ($page); ?></div>
</div>
<footer><?php echo ($footer); ?></footer>