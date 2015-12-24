<?php if (!defined('THINK_PATH')) exit();?><legend> <a href="<?php echo ($url); ?>"><h4>返回</h4></a></legend>
<form name="slideEditForm" id="slideEditForm" class="form-horizontal" enctype="multipart/form-data" action="<?php echo ($slide["actionUrl"]["append"]); ?>" method="post" >
    <fieldset>  

        <input type="hidden" name="id" value="$info" />
        <div class="control-group">
            <label class="control-label" for="sonthree">幻灯片名称</label>
            <div class="controls">
                <input type="text" name='slide_name' id="slide_name"  >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="file">上传图片</label>
            <div class="controls">
                <input type="file" name='img'>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="product">选择商品</label>
            <div class="controls">
                <select name="role" id="role" onchange="choose($(this))" >
                    <option value="" selected="selected"><option>
                    <?php if(is_array($goods)): foreach($goods as $key=>$value): ?><option <?php if($value['id'] == $slide['product_id']) echo ' selected="selected"'; ?> value="<?php echo ($value['id']); ?>"> <?php echo ($value["name"]); ?></option><?php endforeach; endif; ?>
                </select> 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="product_id">商品编号</label>
            <div class="controls">
                <input type="text" name="product_id" id="product_id" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="weight">权重</label>
            <div class="controls">
                <input type="text" name="weight" id="weight">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="is_good">是否商品链接</label>
            <div class="controls">
                <select name="is_good" id="is_good">
                    <option value="0" >是</option>
                    <option value="1" >否</option>
                </select>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="url">外部URL(仅在商品链接为否时生效)</label>
            <div class="controls">
                <input type="text" name="url" id="url">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="status">状态</label>
            <div class="controls">
                <select name="status" id="role">
                    <option value="0">禁用</option>
                    <option value="1" selected="selected"> 启用</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存</button> <button type="button" class="btn" onclick="location = '<?php echo ($url); ?>'">取消</button>
        </div>
    </fieldset>
</form>
<script>
    function choose($this) {
        $("#product_id").attr('value', $this.val());
    }
//    $(document).ready(function(){
//        $("#role").change(function(){
//            $("#product_id").attr('value',$(this).val());
//        });
//    }); 
</script>