<?php if (!defined('THINK_PATH')) exit();?><div class="addressEdit container-fluid">
    <?php $value = $address; ?>
  <div class="row">
    <div class="col-xs-12">
      <input type="text" placeholder="请填写收货人姓名需与身份证相同" class="form-control" id="name" name="name" value="<?php echo ($value["name"]); ?>" >
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <input type="text" placeholder="联系人手机号码" class="form-control" name="phone" id="phone"  value="<?php echo ($value["phone"]); ?>" />
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <input type="text" placeholder="所在省份" class="form-control" name="provice" id="provice"  value="<?php echo ($value["provice"]); ?>" />
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <input type="text" placeholder="所在城市" class="form-control" name="city" id="city"  value="<?php echo ($value["city"]); ?>"/>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <input type="text" placeholder="详细地址"  class="form-control" name="address" id="address" value="<?php echo ($value["address"]); ?>">
    </div>
  </div>
   <div class="row">
    <div class="col-xs-12">
      <input type="text" class="form-control" name="id_no" id="id_no"  value="<?php echo ($value["id_no"]); ?>">
    </div>
  </div>
  <div class="row">
      <div class="col-xs-12 ids">
        <span>身份证信息：</span>
        <span class="btn btn-small card-btn" id="frontid" onclick="chooseImage($(this));" data-value="<?php echo ($value["frontid"]); ?>"><a>+正面</a></span>
      <span class="btn btn-small card-btn" onclick="chooseImage($(this));" id="backid" data-value="<?php echo ($value["backid"]); ?>"><a>+反面</a></span>
    </div>
  </div>
  <div class="row description">
      <div class="col-xs-12  text-center">
      <p>直邮、免税商品需要办理入境申报,您所上传身份证明将用于办理海光清关手续，绝不作其它用途。其它用户无法查看您上传的身份证信息。</p>
      </div>
      <div class="col-xs-10 col-xs-offset-1">
        <button type="button" style="color:#000;background-color:#ffe400;border-color:#ffe400;" class="btn btn-info btn-zhenfan" data-openid="<?php echo ($openid); ?>" data-id="<?php echo ($address["id"]); ?>" onclick="addAddressInfo($(this))">完成保存</button>
      </div>
  </div>
<!--    <div>姓名<input name="name" id="name" value="<?php echo ($value["name"]); ?>"/></div>
    <div>手机号<input name="phone" id="phone"  value="<?php echo ($value["phone"]); ?>"/></div>
    <div>省份<input name="provice" id="provice"  value="<?php echo ($value["provice"]); ?>"/></div>
    <div>城市<input name="city" id="city"  value="<?php echo ($value["city"]); ?>"/></div>
    <div>详细地址<input name="address" id="address" value="<?php echo ($value["address"]); ?>"/></div>
    <div>身份证号<input name="id_no" id="id_no"  value="<?php echo ($value["id_no"]); ?>"/></div>
    <div>
        <button id="frontid" onclick="chooseImage();" data-value="<?php echo ($value["frontid"]); ?>">+正面</button>
        <button onclick="chooseImage();" id="backid" data-value="<?php echo ($value["backid"]); ?>">+反面</button>
    </div>
    <div><button data-openid="<?php echo ($openid); ?>" data-id="<?php echo ($address["id"]); ?>" onclick="addAddressInfo($(this))">完成</button></div>-->
</div>
<?php echo ($addressCss); ?>