<?php if (!defined('THINK_PATH')) exit();?><div class="row search-box"><?php echo ($search); ?></div>

<div class="row clearfix">
    <?php echo ($banner); ?>
</div>
<div class="row yz-line" style="height: 10px"></div>
<div class="row main">
    <div class="col-xs-12">
        <?php echo ($list); ?>
    </div>       
</div>
<div class="row page">
      <div class="col-xs-12">
      <a onclick="loadingMore($(this))" href="javascript:void(0)" class="btn btn-block" id="page" data-value="<?php echo ($page); ?>">点击加载下一页</a>   
    </div>
</div>
        
<?php echo ($css); ?>
<?php echo ($js); ?>