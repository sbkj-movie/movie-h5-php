<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>基本参数设置</title>
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
			<span>设置</span>
		</header><!-- /header -->
		<div class="larry-personal-body clearfix">
			<form class="layui-form col-lg-5" action="/setall/docard" method="post" >
                <div class="layui-form-item">
					<label class="layui-form-label">一级分销比例</label>
					<div class="layui-input-block">  
						<input type="text" name="bili_one" id="bili_one"  required class="layui-input " value="<?php echo ($bili_one['VALUE']); ?>" >
					</div>
				</div>
                  <div class="layui-form-item">
					<label class="layui-form-label">二级分销比例</label>
					<div class="layui-input-block">  
						<input type="text" name="bili_two" id="bili_two"  required class="layui-input " value="<?php echo ($bili_two['VALUE']); ?>" >
					</div>
				</div>
                <!-- <div class="layui-form-item">
					<label class="layui-form-label">三级分销比例</label>
					<div class="layui-input-block">  
						<input type="text" name="bili_three" id="bili_three"  required class="layui-input " value="<?php echo ($bili_three['VALUE']); ?>" >
					</div>
				</div>-->
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
		  
		  //监听提交  
		   form.on('submit(demo1)', function(data){  
			$.ajax({
				 type: "post",
				  url: "<?php echo U('shezhi/dosysadd');?>",
				 data: {bili_three:$('#bili_three').val(),bili_one:$('#bili_one').val(),bili_two:$('#bili_two').val()},
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