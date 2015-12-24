<?php if (!defined('THINK_PATH')) exit();?><div class="content addressChoose">
  <div class=" list-group-item">
    <a href="javascript:void(0)" class="add-address" onclick="addAddress($(this))">
    <table>
      <tr>
        <td><span class="glyphicon glyphicon-plus"></span></td>
        <td>新增收货地址</td>
        <td class="num-address">你还可以新增<?php echo ($data["allowAddressNo"]); ?>个收货地址</td>
      </tr>
    </table>
    </a>
  </div>
  <div class="dis">
    请选择默认收货地址:
  </div>
  <ul class="list-address list-group">
    <?php if(is_array($address)): foreach($address as $key=>$value): ?><li class="list-address-item list-group-item">
          <table>
            <tr id="address-<?php echo ($value["id"]); ?>">
                <td class="col1" style="font-size: 25px;" onclick="chooseAddress($(this))" data-value="<?php echo ($value["id"]); ?>"><?php if($i++ == 0) echo '<span class="glyphicon glyphicon-ok-circle"></span>'; ?></td>
              <td onclick="chooseAddress($(this))" data-value="<?php echo ($value["id"]); ?>" class="address">
                <span>
                    <?php echo ($value["name"]); ?>&nbsp;&nbsp;<?php echo ($value["phone"]); ?><br>
                    <?php echo ($value["provice"]); echo ($value["city"]); echo ($value["address"]); ?>
                </span>
              </td>
              <td class="col3">
                  <?php if(($i) != "1"): ?><a style="color:green" href="javascript:void(0)" data-id="<?php echo ($value["id"]); ?>" onclick="deleteAddress($(this))"><i class="glyphicon glyphicon-remove"></i>删除</a><?php endif; ?>
                  <a style="color:red" href="#" data-id="<?php echo ($value["id"]); ?>" onclick="editAddress($(this))" class="edit"><span class="glyphicon glyphicon-pencil"></span> 编辑</a></td>
            </tr>
          </table>
        </li><?php endforeach; endif; ?>
  </ul>
  <!--div class="">
      <button type="button" class="btn btn btn-info btn-zhenfan" onclick="">完成返回</button>
  </div-->
  <?php echo ($js); ?>
<?php echo ($addressCss); ?>
</div>