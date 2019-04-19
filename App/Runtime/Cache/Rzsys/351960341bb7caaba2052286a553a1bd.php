<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>视频后台登录</title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">	
	<!-- load css -->
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/layui/css/layui.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/css/login.css" media="all">

</head>
<body>
<div class="layui-canvs"></div>
<div class="layui-layout layui-layout-login">
	<h1>
		 <strong>视频管理系统后台</strong>
		 <em>Management System</em>
	</h1>
	<form method="post" name="form1" class="layui-form"  onsubmit="return false;">
	<div class="layui-user-icon larry-login">
		 <input type="text" name="username"  id="username" placeholder="账号" class="login_txtbx"/>
	</div>
	<div class="layui-pwd-icon larry-login">
		 <input type="password" placeholder="密码" name="password" id="pass" class="login_txtbx"/>
	</div>
    <div class="layui-val-icon larry-login">
    	<div class="layui-code-box">
    		<input type="text" id="code" name="verify" placeholder="验证码" maxlength="5" class="login_txtbx">
            <img src="<?php echo U('/Login/verify');?>?time=<?php echo rand(1,1000); ?>" onclick="this.src='/admin.php?m=home&c=login&a=verify&time='+Math.random();" alt="" class="verifyImg" id="verifyImg" >
    	</div>
    </div>
    <div class="layui-submit larry-login">
    	<input type="submit" value="立即登陆" lay-filter="demo1" class="submit_btn"  onclick="return checkall();"/>
    </div>
	</form>
    <div class="layui-login-text">
    	<p>© 2018 Larry 版权所有</p>
        <p>豫xxxxxx</p>
    </div>
</div>
<script type="text/javascript" src="/Public/admin/common/layui/lay/dest/layui.all.js"></script>
<script type="text/javascript" src="/Public/admin/js/login.js"></script>
<script type="text/javascript" src="/Public/admin/jsplug/jparticle.jquery.js"></script>
<script type="text/javascript">

$(function(){
	$(".layui-canvs").jParticle({
		background: "#219788",
		color: "#E6E6E6"
	});
	//登录链接测试，使用时可删除
});
function checkall(){
	var username=document.form1.username.value;
	var password=document.form1.password.value;
	var verify=document.form1.verify.value;
	
	if(username == ''){
		alert('请输入用户名');
		return false;
	}
	
	if(password == ''){
		alert('请输入密码');
		return false;
	}
	
	if(verify == ''){
		alert('请输入验证码');
		return false;
	}
	var url = "<?php echo U('login/dologin');?>";
	$("#login").hide();
	$("#logining").text('登录中...');
	$("#logining").show();
	$.post(url,$("form").serialize(),function(data){
		if(data.status==1){
			$("#logining").text('登录成功，跳转中...');
			location.href="<?php echo U('index/index');?>";
		}else{
			$("#login").show();
			$("#logining").text('');
			$("#logining").hide();
			alert(data.msg);
		}
	})
}
</script>

</body>
</html>