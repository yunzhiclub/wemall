<?php if (!defined('THINK_PATH')) exit();?><div class="seo clearfix">
	<div class="search-area clearfix s-area">
		
            <div class="search_c"><input id="search-input"  type="text" name="condition"  placeholder="搜索商品/订单" class="key-word"></div> 
            <div class="search_r"><INPUT class="search-icon" type="button" onclick="search();" id='btn_search' /></div>
            <div style="clear:both"></div>
	</div>
</div>
<script>
    function search(){
        var value = $("#search-input").val();
        if(value === ''){
            return;
        }else{
            $(".btn-box").html('');
            $(".list-group").html('');
            $(".list-group").next().remove();
            $(".content").append('<div class="loading"></div>');
            $.get("<?php echo ($searchUrl); ?>?condition=" + value,function(data){
                var obj = eval("(" + data + ")"); 
                $(".loading").remove();
                if(obj.state === 0){                
                     $(".content").append(obj.content);
                }else{
                    $(".list-group").html(obj.content);
                    $(".btn-box").html(obj.page);
                }
            });
        }
       
    }
    function queryLoad($this){
        var value = $("#search-input").val();
        var pageNum = $this.attr("data-page");
        $(".btn-box").html('');
        $(".content").append('<div class="loading"></div>');
        $.get("<?php echo ($loadUrl); ?>?pageNum=" + pageNum +"&queryAdd=" + value,function(data){            
            var obj = eval("(" + data + ")"); 
            $(".loading").remove();
            if(obj.state === 0){                
                     $(".list-group").append(obj.content);
                     $(".btn-box").html("没有了...");
                }else{
                    $(".list-group").append(obj.content);
                    $(".btn-box").html(obj.page);
                }
        });
    }
</script>