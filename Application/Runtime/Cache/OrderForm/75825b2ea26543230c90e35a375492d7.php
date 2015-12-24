<?php if (!defined('THINK_PATH')) exit();?>
        <a href="javascript:void(0);" id="addressId" data-value="<?php echo ($address["id"]); ?>" class="address-btn" onclick="showAddress($(this))">
          <table class="list-group-item-table">
              <tr>
                  <td class="col-right0"><span class="addressTitle"><?php echo ($address["name"]); ?>&nbsp;&nbsp;<?php echo ($address["phone"]); ?></span></td>
                  <td rowspan="2" class="col-right1">             
                      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  </td>
                      
              </tr>
              <tr>
                  <td class="col-right0">
                      <span class="addressDetail">
                      <?php echo ($address["provice"]); echo ($address["city"]); echo ($address["address"]); ?>
                      </span>
                      <?php if(($address["address"]) == ""): ?><p style="color:red;">添加收货地址</p><?php endif; ?>
                  </td>
              </tr>
          </table>
        </a>