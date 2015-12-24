<?php if (!defined('THINK_PATH')) exit();?><form id="SetRedpacketForm" class="form-horizontal" name="SetRedpacketForm" method="post" action="<?php echo ($update); ?>" enctype="multipart/form-data" target="id_iframe" />
    <fieldset>
    <legend>统一设置</legend>
    <div class="control-group">
        <label class="control-label" for="act_name">活动名称</label>
        <div class="controls">
        <input type="text" name='act_name' id="act_name" value="<?php echo ($list["act_name"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="send_name">商户名称</label>
        <div class="controls">
        <input type="text" name='send_name' id="send_name" value="<?php echo ($list["send_name"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="remark">备注</label>
        <div class="controls">
        <input type="text" name='remark' id="remark" value="<?php echo ($list["remark"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="wishing">祝福语</label>
        <div class="controls">
            <textarea name='wishing' id="wishing"><?php echo ($list["wishing"]); ?></textarea>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="share_content">分享文案</label>
        <div class="controls">
        <input type="text" name='share_content' id="share_content" value="<?php echo ($list["share_content"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="share_url">分享的链接</label>
        <div class="controls">
            <textarea name='share_url' id="share_url"><?php echo ($list["share_url"]); ?></textarea>
        </div>
    </div>
    
    <legend>老用户</legend>
    <input type="hidden" name="id" value="<?php echo ($list['id']); ?>"/>
    <div class="control-group">
        <label class="control-label" for="old_min_value">发放最小金额</label>
        <div class="controls">
        <input type="text" name='old_min_value' id="old_min_value" value="<?php echo (format_money($list["old_min_value"])); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="old_max_value">发放最大金额</label>
        <div class="controls">
            <input type="text" name='old_max_value' id="old_max_value" value="<?php echo (format_money($list["old_max_value"])); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="old_max_num">发放红包最大个数</label>
        <div class="controls">
            <input type="text" name='old_max_num' id="old_max_num" value="<?php echo ($list["old_max_num"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="old_state">状态</label>
        <div class="controls">
        <select  name="old_state" id="old_state">
            <?php if($list["old_state"] == 1): ?><option value="1"> 开启</option><option value="0"> 关闭</option>
                <?php else: ?>
                <option value="0"> 关闭</option><option value="1"> 开启</option><?php endif; ?>
        </select>
        </div>
    </div>

    <legend>新用户</legend>
<!--    <input type="hidden" name="id" value="<?php echo ($GoodsList['id']); ?>"/>-->
    <div class="control-group">
        <label class="control-label" for="new_min_value">发放最小金额</label>
        <div class="controls">
        <input type="text" name='new_min_value' id="new_min_value" value="<?php echo (format_money($list["new_min_value"])); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="new_max_value">发放最大金额</label>
        <div class="controls">
            <input type="text" name='new_max_value' id="new_max_value" value="<?php echo (format_money($list["new_max_value"])); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="attention_give">直接关注是否发放</label>
        <div class="controls">
        <select  name="attention_give" id="attention_give">
            <?php if($list["attention_give"] == 1): ?><option value="1"> 是</option><option value="0"> 否</option>
                <?php else: ?>
                <option value="0"> 否</option><option value="1"> 是</option><?php endif; ?>
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="new_state">状态</label>
        <div class="controls">
        <select  name="new_state" id="new_state">
            <?php if($list["new_state"] == 1): ?><option value="1"> 开启</option><option value="0"> 关闭</option>
                <?php else: ?>
                <option value="0"> 关闭</option><option value="1"> 开启</option><?php endif; ?>
        </select>
        </div>
    </div>
    <div class="form-actions">
        <button onclick="save()" class="btn btn-primary">保存</button> <button class="btn">取消</button>
    </div>
</fieldset>
</form>