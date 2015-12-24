<?php
/*
 * 自定义异常。
 */
namespace WxPay\Controller;
class  SDKRuntimeExceptionController extends \Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}
}