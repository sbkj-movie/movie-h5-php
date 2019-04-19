<?php
		require_once("alipay.config.php");
		require_once("alipay_notify.class.php");
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {//验证成功
			//商户订单号
			$data['out_trade_no'] = $_GET['out_trade_no'];
			//支付宝交易号
			$data['trade_no'] = $_GET['trade_no'];
			//交易状态
			$data['trade_status'] = $_GET['trade_status'];
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