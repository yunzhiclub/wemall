<?php if (!defined('THINK_PATH')) exit();?><!--
梦云智工作室
-->
<form id="changePay" class="form-horizontal" name="changePay" method="post" action="<?php echo ($url); ?>" />
<fieldset>
   <legend>修改状态</legend>
        <div class="control-group">
            <label class="control-label" for="title">订单总额</label>
            <div class="controls">
                <input type="text" name='payable' id="payable" value="<?php echo format_money($change['payable']) ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存</button>
            <input type="button"  onclick="javascript:history.back(-1);" value="返回" class="btn">
        </div>
</fieldset>
</form>