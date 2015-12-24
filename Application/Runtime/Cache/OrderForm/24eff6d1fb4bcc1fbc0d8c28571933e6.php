<?php if (!defined('THINK_PATH')) exit();?> <nav class="navbar navbar-default navbar-fixed-bottom text-center vertical-middle-sm footer row" role="navigation">
     <div class="col-xs-8">
         <p>应付金额: <font id="totalPayable" data-value="<?php echo ($total['totalPayable']); ?>" class="f4_b"><?php echo (format_money($total['totalPayable'])); ?></font>元</p> 
     </div>
    <div class="col-xs-4 submit">
        <button onclick="submit()" id="Submit" class="btn btn-danger btn-block" name="Submit">确认支付</button>
    </div>
</nav>