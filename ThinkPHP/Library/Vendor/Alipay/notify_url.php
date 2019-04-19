<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */
		//session_start();
		require_once("alipay.config.php");
		require_once("alipay_notify.class.php");

		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if($verify_result) {//验证成功
			
			//商户订单号

			$data['out_trade_no'] = $_POST['out_trade_no'];

			//支付宝交易号

			$data['trade_no'] = $_POST['trade_no'];

			//交易状态
			$data['trade_status'] = $_POST['trade_status'];
			$url = "http://".$_SERVER['HTTP_HOST']."/pay-data&order_id=".$data['out_trade_no']."&trade_no=".$data['trade_no']."&trade_status=".$data['trade_status']; 
			echo "<script language=\"javascript\">";

			echo "document.location=\"{$url}\"";

			echo "</script>";
			//header("Location:http://".$_SERVER['HTTP_HOST']."/pay-data");
				
			echo "验证成功<br />";
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
			echo "支付失败";
		}
?>