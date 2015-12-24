<?php if (!defined('THINK_PATH')) exit();?>
<form id="adminMenuEditForm" class="form-horizontal" name="adminMenuEditForm" method="post" action="<?php echo ($adminMenu["actionUrl"]["update"]); ?>" />
<fieldset>
   <legend> 编辑菜单</legend>
<?php if((ACTION_NAME) == "edit"): ?><input type="hidden" name="id" value="<?php echo ($adminMenu['id']); ?>"/><?php endif; ?>
    <input type="hidden" name="pid" value="<?php echo ($adminMenu["pid"]); ?>" />
                            <input type="hidden" value="<?php echo ($adminMenu["id"]); ?>"/>
                            <div class="control-group">
                                <label class="control-label" for="title">标题</label>
                                <div class="controls">
                                <input type="text" name='title' id="title" value="<?php echo ($adminMenu["title"]); ?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="module">模块</label>
                                <div class="controls">
                                <input type="text" name='module' id="module" value="<?php echo ($adminMenu["module"]); ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="controller">控制器</label>
                                <div class="controls">
                                <input type="text" name='controller' id="controller" value="<?php echo ($adminMenu["controller"]); ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                            <label class="control-label" for="action">触发器</label>
                                <div class="controls">
                                <input type="text" name="action" id="action" value="<?php echo ($adminMenu["action"]); ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_dev">是否开发模式</label>
                                <div class="controls">
                                <select name="is_dev" id="is_dev">
                                    <option value="1">是</option>
                                    <option value="0" selected="selected"> 否</option>
                                </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sort">排序</label>
                                <div class="controls">
                                <input type="text" name='sort' id="group" value="<?php echo ($adminMenu["sort"]); ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="hide">是否隐藏</label>
                                <div class="controls">
                                <select name="hide" id="hide">
                                    <option value="1">是</option>
                                    <option value="0" selected="selected"> 否</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-actions">
				<button type="submit" class="btn btn-primary">保存</button> <button class="btn">取消</button>
			    </div>
</fieldset>
</form>