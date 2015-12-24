<?php if (!defined('THINK_PATH')) exit();?><form id="PersonalinfoForm" class="form-horizontal" name="PersonalinfoForm" method="post" action="<?php echo ($User["actionUrl"]["update"]); ?>" />
<fieldset>
    <?php  ?>
    <legend> 查看用户信息</legend>
    <?php if((ACTION_NAME) == "edit"): ?><input type="hidden" name="id" value="<?php echo ($User['userid']); ?>"/><?php endif; ?>
    <div class="control-group">
        <label class="control-label" for="userid">用户名</label>
        <div class="controls">
            <span><?php echo ($User["userid"]); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="name">姓名</label>
        <div class="controls">
            <span><?php echo ($User["name"]); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="email">邮箱</label>
        <div class="controls">
             <span><?php echo ($User["email"]); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="action">手机</label>
        <div class="controls">
            <span><?php echo ($User["phone"]); ?></span>
        </div>
    </div>

        <div class="form-actions">
           </div>
</fieldset>
</form>