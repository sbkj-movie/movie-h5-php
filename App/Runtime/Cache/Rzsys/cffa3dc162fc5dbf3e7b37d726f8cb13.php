<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>支付账户添加</title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">	
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">	
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/layui/css/layui.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/bootstrap/css/bootstrap.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/global.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/css/personal.css" media="all">
    
</head>
<body>
<section class="layui-larry-box">
	<div class="larry-personal">
		<header class="larry-personal-tit">
			<span>支付账户添加</span>
		</header><!-- /header -->
		<div class="larry-personal-body clearfix">
			<form class="layui-form col-lg-5" method="post" >
             <input type="hidden" name="id" id="upid" value="<?php echo ($data['ID']?$data['ID']:''); ?>">
            <input type="hidden" name="pg" id="pg" value="<?php echo ($data['pg']?$data['pg']:'1'); ?>">
           	    <div class="layui-form-item">
					<label class="layui-form-label">商户号</label>
					<div class="layui-input-block">  
						<input type="text" name="appid" id="appid" required class="layui-input " value="<?php echo ($data['HU_APPID']?$data['HU_APPID']:''); ?>"  >
					</div>
				</div>
                <div class="layui-form-item">
					<label class="layui-form-label">支付平台</label>
                     <input type="hidden" id="typeall" value="<?php echo ($data['HU_TYPE']?$data['HU_TYPE']:'1'); ?>">
					<div class="layui-input-block">
						<input type="radio" name="type" value="1" lay-filter="type"  title="宝付"  <?php if(isset($data['HU_TYPE'])&&$data['HU_TYPE']==1 ): ?>checked="checked"<?php endif; ?> <?php if(!isset($data['HU_TYPE'])): ?>checked="checked"<?php endif; ?>>
                        <div class="layui-unselect layui-form-radio layui-form-radioed"><i class="layui-anim layui-icon"></i><span>宝付</span></div>
						<input type="radio" name="type" value="2" lay-filter="type"  title="青苹果" <?php if(isset($data['HU_TYPE'])&&$data['HU_TYPE']==2): ?>checked="checked"<?php endif; ?>><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><span>青苹果</span></div>
					</div>
				</div>
                 <div class="layui-form-item">
					<label class="layui-form-label">密钥/商家私钥</label>
					<div class="layui-input-block">  
						<input type="text" name="appsecret" id="appsecret" required class="layui-input " value="<?php echo ($data['HU_APP_SECRET']?$data['HU_APP_SECRET']:''); ?>"  >
					</div>
				</div>
                <div class="layui-form-item" id="gongyao" <?php if(isset($data['HU_TYPE'])&&$data['HU_TYPE']==1 ): ?>style="display:none"<?php endif; ?>>
					<label class="layui-form-label">平台公钥</label>
					<div class="layui-input-block">  
						<input type="text" name="appgong" id="appgong" required class="layui-input " value="<?php echo ($data['HU_APP_GONG']?$data['HU_APP_GONG']:''); ?>"  >
					</div>
				</div>
               
                
                <div class="layui-form-item">
					<label class="layui-form-label">最高限额</label>
					<div class="layui-input-block">  
						<input type="text" name="money" id="money" required class="layui-input " value="<?php echo ($data['MAX_MONEY']?$data['MAX_MONEY']:''); ?>"  >
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
						<button type="reset" class="layui-btn layui-btn-primary">重置</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<script type="text/javascript" src="/Public/admin/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="/Public/admin/common/layui/layui.js"></script>
<script type="text/javascript">
	layui.use(['form','upload'],function(){
         var form = layui.form();
         //自定义验证规则 
		  var type=$('#typeall').val(); 
		  form.on('radio(type)', function (data) { 
		 		if(data.value==2){
					type=2;
					$('#gongyao').show();
				}else{
					type=1;
					$('#gongyao').hide();
				}
		   });
		form.on('submit(demo1)', function(data){  
		
				$.ajax({
				 type: "post",
				 url: "<?php echo U('pay/hpdoadd');?>",
				 data: {appid:$('#appid').val(),type:type,appgong:$('#appgong').val(),appsecret:$('#appsecret').val(),money:$('#money').val(),id:$('#upid').val(),pg:$('#pg').val(),},
				 dataType: "json",
				 async:false,
				 success: function(data){
					layer.msg(data.msg);
						setTimeout(function() {
							window.location =data.href;
						}, 1200);
					
				 }
							
			 });
				 return false;  
		  }); 
	});
</script>
</body>
</html>