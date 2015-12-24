<?php if (!defined('THINK_PATH')) exit();?><legend> <a href="<?php echo ($url); ?>"><h4>返回</h4></a></legend>
<form name="slideEditForm" id="slideEditForm" class="form-horizontal" enctype="multipart/form-data" action="<?php echo ($slide["actionUrl"]["update"]); ?>" method="post" >
    <fieldset>  

        <input type="hidden" name="id" value="<?php echo ($slide['id']); ?>" />
        <div class="control-group">
            <label class="control-label" for="sonthree">幻灯片名称</label>
            <div class="controls">
                <input type="hidden" name="slide_name" value="<?php echo ($slide['slide_name']); ?>" />
                <span><?php echo ($slide['slide_name']); ?></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="file">上传图片</label>
            <div class="controls">
                <div><img src="<?php echo ($slide["attchment_id"]["url"]); ?>" style="max-width: 100px;"/></div>
                <input type="file" name='img' id="openid" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="product">选择商品</label>
            <div class="controls">
                <select name="role" id="role" onchange="choose($(this))" >
                    <option value="" selected="selected"></option>
                    <?php if(is_array($goods)): foreach($goods as $key=>$value): ?><option value="<?php echo ($value["id"]); ?>" <?php if($value['id'] == $slide['product_id']) echo ' selected="selected"'; ?> > <?php echo ($value["name"]); ?></option><?php endforeach; endif; ?>
                </select> 
            </div>

        </div>
        <div class="control-group">
            <label class="control-label" for="product_id">商品编号</label>
            <div class="controls">
                <input type="text" name="product_id" id="product_id" value="<?php echo ($slide['product_id']); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="weight">权重</label>
            <div class="controls">
                <input type="text" name="weight" id="weight"  value="<?php echo ($slide['weight']); ?>">
            </div>
        </div>
        
        
        <div class="control-group">
            <label class="control-label" for="is_good">是否商品链接</label>
            <div class="controls">
                <select name="is_good" id="is_good">
                    <option value="0" >是</option>
                    <option value="1" <?php if(($slide["is_good"]) == "1"): ?>selected="selected"<?php endif; ?> >否</option>
                </select>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="url">外部URL(仅在商品链接为否时生效)</label>
            <div class="controls">
                <input type="text" name="url" id="url" value="<?php echo ($slide["url"]); ?>">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="status">状态</label>
            <div class="controls">
                <select name="status" id="status">
                    <option value="1"> 启用</option>
                    <option value="0" <?php if(($slide["status"]) == "0"): ?>selected="selected"<?php endif; ?>>禁用</option>                 
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存</button> <button type="button" class="btn" onclick="location = '<?php echo ($url); ?>'">取消</button>
        </div>
    </fieldset>
</form>
<script>
    function choose($this){       
        $("#product_id").attr('value',$this.val());
    }
//    $(document).ready(function(){
//        $("#role").change(function(){
//            $("#product_id").attr('value',$(this).val());
//        });
//    }); 
</script>