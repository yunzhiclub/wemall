<?php if (!defined('THINK_PATH')) exit();?><ul class="<?php echo ($class); ?>">
    <?php if(is_array($data)): foreach($data as $key=>$value): if(($value["hide"]) == "0"): ?><li class="<?php echo ($value["class"]); ?>"><a href="<?php echo ($value["url"]); ?>"><?php echo ($value["title"]); ?></a></li><?php endif; endforeach; endif; ?>
</ul>